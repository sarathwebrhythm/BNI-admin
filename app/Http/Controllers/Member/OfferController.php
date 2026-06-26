<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOfferRequest;
use App\Models\Offer;
use App\Models\OfferCategory;
use Illuminate\Support\Facades\Storage;

class OfferController extends Controller
{
    // Get all offers for logged in member
    public function index()
    {
        /** @var \App\Models\Member $member */
        $member = auth('member')->user();

        $offers = Offer::where('member_id', $member->id)
            ->with('category')
            ->orderBy('order')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'offers'  => $offers,
        ]);
    }

    // Create new offer
    public function store(StoreOfferRequest $request)
    {
        /** @var \App\Models\Member $member */
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

    // Delete offer
    public function destroy($id)
    {
        /** @var \App\Models\Member $member */
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

    // Get offer categories from DB
    public function categories()
    {
        $categories = OfferCategory::orderBy('name')->get(['id', 'name']);

        return response()->json([
            'success'    => true,
            'categories' => $categories,
        ]);
    }
}