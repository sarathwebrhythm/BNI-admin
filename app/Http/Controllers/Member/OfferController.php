<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOfferRequest;
use App\Models\Offer;
use App\Models\OfferCategory;
use App\Models\OfferStat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OfferController extends Controller
{
    // public function index()
    // {
    //     $member = auth('member')->user();

    //     $offers = Offer::where('member_id', $member->id)
    //         ->with('category')
    //         ->orderBy('order')
    //         ->orderBy('created_at', 'desc')
    //         ->get();

    //     return response()->json([
    //         'success' => true,
    //         'offers'  => $offers,
    //     ]);
    // }

    public function index()
    {
        $member = auth('member')->user();

        // NEW: before fetching the list, fix any offers that expired
        Offer::where('member_id', $member->id)
            ->where('end_date', '<', now())
            ->where('status', 'active')
            ->update(['status' => 'inactive']);

        $offers = Offer::where('member_id', $member->id)
            ->with('category')
            ->orderBy('order')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($offer) {
                $offer->start_date = optional($offer->start_date)->format('Y-m-d H:i:s');
                $offer->end_date = optional($offer->end_date)->format('Y-m-d H:i:s');
                return $offer;
            });

        return response()->json([
            'success' => true,
            'offers' => $offers,
        ]);
    }

    public function store(StoreOfferRequest $request)
    {
        $member = auth('member')->user();

        $imageUrl = null;
        if ($request->hasFile('image')) {
            $path     = $request->file('image')->store('offer-images', 'public');
            $imageUrl = '/storage/' . $path;
        }

        $offer = Offer::create([
            'member_id'         => $member->id,
            'offer_category_id' => $request->offer_category_id,
            'title'             => $request->title,
            'discount'          => $request->discount,
            'description'       => $request->description,
            'start_date'        => $request->start_date,
            'end_date'          => $request->end_date,
            'terms'             => $request->terms ?? [],
            'image'             => $imageUrl,
            'contact_number'    => $request->contact_number,
            'status'            => 'pending',
            'order'             => $request->order ?? 0,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Offer created successfully.',
            'offer'   => $offer->load('category'),
        ]);
    }

    public function update(StoreOfferRequest $request, $id)
    {
        $member = auth('member')->user();

        $offer = Offer::where('id', $id)
            ->where('member_id', $member->id)
            ->firstOrFail();

        $data = [
            'offer_category_id' => $request->offer_category_id,
            'title'             => $request->title,
            'discount'          => $request->discount,
            'description'       => $request->description,
            'start_date'        => $request->start_date,
            'end_date'          => $request->end_date,
            'terms'             => $request->terms ?? [],
            'contact_number'    => $request->contact_number,
            'order'             => $request->order ?? $offer->order,
        ];
        if (
            $offer->status === 'inactive' &&
            now()->lt($request->end_date)
        ) {
            $data['status'] = 'active';
        }

        if ($request->hasFile('image')) {


            if ($offer->image) {
                $oldPath = ltrim(str_replace('/storage/', '', $offer->image), '/');

                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }

            // Upload new image
            $path = $request->file('image')->store('offer-images', 'public');
            $data['image'] = '/storage/' . $path;
        }

        $offer->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Offer updated successfully.',
            'offer'   => $offer->fresh()->load('category'),
        ]);
    }

    public function destroy($id)
    {
        $member = auth('member')->user();
        $offer  = Offer::where('id', $id)
            ->where('member_id', $member->id)
            ->firstOrFail();

        if ($offer->image) {
            $path = ltrim(str_replace('/storage/', '', $offer->image), '/');
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }

        $offer->delete();

        return response()->json([
            'success' => true,
            'message' => 'Offer deleted successfully.',
        ]);
    }

    public function categories()
    {
        $categories = OfferCategory::orderBy('order')
            ->orderBy('name')
            ->get(['id', 'name', 'icon'])
            ->map(function ($cat) {
                $icon = $cat->icon;
                if ($icon && !str_starts_with($icon, '/storage/') && !str_starts_with($icon, 'http')) {
                    $icon = '/storage/' . $icon;
                }
                return [
                    'id'   => $cat->id,
                    'name' => $cat->name,
                    'icon' => $icon,
                ];
            });

        return response()->json([
            'success'    => true,
            'categories' => $categories,
        ]);
    }

    public function allActive(Request $request)
    {
        $member = auth('member')->user();
        $query = Offer::with(['category', 'member'])
            ->where('status', 'active')
            ->whereDate('end_date', '>=', now());
        $savedOfferIds = OfferStat::where('member_id', $member->id)
            ->where('type', 'saved')
            ->pluck('offer_id')
            ->toArray();

        // Filter by category 
        if ($request->filled('category_id')) {
            $query->where('offer_category_id', $request->category_id);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('discount', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('member', function ($q) use ($search) {
                        $q->where('company', 'like', "%{$search}%")
                            ->orWhere('chapter', 'like', "%{$search}%");
                    })
                    ->orWhereHas('category', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $offers = $query->orderBy('created_at', 'desc')


            ->get()
            ->map(function ($offer) use ($savedOfferIds) {
                return [
                    'id'             => $offer->id,
                    'discount'       => $offer->discount,
                    'description'    => $offer->description,
                    'category'       => optional($offer->category)->name,
                    'category_id'    => $offer->offer_category_id,
                    'business_name'  => optional($offer->member)->company,
                    'chapter'        => optional($offer->member)->chapter,
                    'business_address' => optional($offer->member)->address,
                    'image'          => $offer->image,
                    'contact_number' => $offer->contact_number,
                    'start_date'     => $offer->start_date,
                    'end_date'       => $offer->end_date,
                    'terms'          => $offer->terms ?? [],
                    'is_saved' => in_array($offer->id, $savedOfferIds)
                ];
            });

        return response()->json([
            'success' => true,
            'offers'  => $offers,
        ]);
    }
}
