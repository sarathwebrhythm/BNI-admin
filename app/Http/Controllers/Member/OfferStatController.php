<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use App\Models\OfferStat;
use Illuminate\Http\Request;

class OfferStatController extends Controller
{
    // Record a view
    public function recordView(Request $request, $offerId)
    {
        $member = auth('member')->user();

        $offer = Offer::findOrFail($offerId);


        if ($offer->member_id == $member->id) {
            return response()->json(['success' => true]);
        }

        OfferStat::create([
            'offer_id'  => $offerId,
            'member_id' => $member->id,
            'type'      => 'view',
        ]);

        $offer->increment('views');

        return response()->json(['success' => true]);
    }

    // Record a redemption
    public function recordRedemption(Request $request, $offerId)
    {
        $member = auth('member')->user();

        $offer = Offer::findOrFail($offerId);

        // Owner redeemed own offer → don't count
        if ($offer->member_id == $member->id) {
            return response()->json(['success' => true]);
        }

        OfferStat::create([
            'offer_id'  => $offerId,
            'member_id' => $member->id,
            'type'      => 'redemption',
        ]);

        $offer->increment('redemptions');

        return response()->json(['success' => true]);
    }

    // Toggle save
    public function toggleSave(Request $request, $offerId)
    {
        $member = auth('member')->user();

        $offer = Offer::findOrFail($offerId);

        // Check if logged-in member owns this offer
        $isOwner = $offer->member_id == $member->id;

        $existing = OfferStat::where('offer_id', $offerId)
            ->where('member_id', $member->id)
            ->where('type', 'saved')
            ->first();

        if ($existing) {
            $existing->delete();

            // Decrease save count only for other members
            if (!$isOwner) {
                $offer->decrement('saves');
            }

            return response()->json([
                'success' => true,
                'saved'   => false,
            ]);
        }

        OfferStat::create([
            'offer_id'  => $offerId,
            'member_id' => $member->id,
            'type'      => 'saved',
        ]);

        // Increase save count only for other members
        if (!$isOwner) {
            $offer->increment('saves');
        }

        return response()->json([
            'success' => true,
            'saved'   => true,
        ]);
    }

    // Get stats for a specific offer (for business owner)
    public function getOfferStats($offerId)
    {
        $member = auth('member')->user();

        $offer = Offer::where('id', $offerId)
            ->where('member_id', $member->id)
            ->firstOrFail();

        $rate = $offer->views > 0
            ? round(($offer->redemptions / $offer->views) * 100, 2)
            : 0;

        return response()->json([
            'success'         => true,
            'views'           => $offer->views,
            'redemptions'     => $offer->redemptions,
            'saves'           => $offer->saves,
            'redemption_rate' => $rate,
        ]);
    }

    // Check if current member has saved an offer
    public function checkSaved($offerId)
    {
        $member = auth('member')->user();

        $saved = OfferStat::where('offer_id', $offerId)
            ->where('member_id', $member->id)
            ->where('type', 'saved')
            ->exists();

        return response()->json(['success' => true, 'saved' => $saved]);
    }
    public function recentLeads()
    {
        $member = auth('member')->user();

        $leads = OfferStat::with(['member', 'offer'])
            ->whereHas('offer', function ($q) use ($member) {
                $q->where('member_id', $member->id);
            })
            ->where('member_id', '!=', $member->id)
            ->whereIn('type', ['view', 'saved', 'redemption'])
            ->where('created_at', '>=', now()->subDays(7))
            ->latest()
            ->get()
            ->map(function ($stat) {
                return [
                    'id'        => $stat->member->id,
                    'name'      => $stat->member->name,
                    'photo'     => $stat->member->profile_photo,
                    'discount'  => $stat->offer->discount,
                    'action' => match ($stat->type) {
                        'view' => 'Viewed',
                        'saved' => 'Saved',
                        'redemption' => 'Redeemed',
                        default => ucfirst($stat->type),
                    },
                    'time'      => $stat->created_at->diffForHumans(),
                ];
            })
            ->values();

        return response()->json([
            'success' => true,
            'leads'   => $leads,
        ]);
    }
    // Get all offers the current member has saved
    public function savedOffers()
    {
        $member = auth('member')->user();

        $offers = OfferStat::with(['offer.category', 'offer.member'])
            ->where('member_id', $member->id)
            ->where('type', 'saved')
            ->latest()
            ->get()
            ->pluck('offer')
            ->filter() // drop nulls in case a saved offer was later deleted
            ->map(function ($offer) {
                return [
                    'id'                => $offer->id,
                    'discount'          => $offer->discount,
                    'description'       => $offer->description,
                    'category'          => optional($offer->category)->name,
                    'category_id'       => $offer->offer_category_id,
                    'business_name'     => optional($offer->member)->company,
                    'chapter'           => optional($offer->member)->chapter,
                    'business_address'  => optional($offer->member)->address,
                    'image'             => $offer->image,
                    'contact_number'    => $offer->contact_number,
                    'start_date'        => $offer->start_date,
                    'end_date'          => $offer->end_date,
                    'terms'             => $offer->terms ?? [],
                ];
            })
            ->values();

        return response()->json([
            'success' => true,
            'offers'  => $offers,
        ]);
    }
}
