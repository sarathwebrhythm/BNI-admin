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

        OfferStat::create([
            'offer_id'  => $offerId,
            'member_id' => $member->id,
            'type'      => 'view',
        ]);

        // Increment counter
        Offer::where('id', $offerId)->increment('views');

        return response()->json(['success' => true]);
    }

    // Record a redemption
    public function recordRedemption(Request $request, $offerId)
    {
        $member = auth('member')->user();

        OfferStat::create([
            'offer_id'  => $offerId,
            'member_id' => $member->id,
            'type'      => 'redemption',
        ]);

        // Increment counter
        Offer::where('id', $offerId)->increment('redemptions');

        return response()->json(['success' => true]);
    }

    // Toggle save (save/unsave)
    public function toggleSave(Request $request, $offerId)
    {
        $member = auth('member')->user();

        $existing = OfferStat::where('offer_id', $offerId)
            ->where('member_id', $member->id)
            ->where('type', 'saved')
            ->first();

        if ($existing) {
            $existing->delete();
            // Decrement counter
            Offer::where('id', $offerId)->decrement('saves');
            return response()->json(['success' => true, 'saved' => false]);
        }

        OfferStat::create([
            'offer_id'  => $offerId,
            'member_id' => $member->id,
            'type'      => 'saved',
        ]);

        // Increment counter
        Offer::where('id', $offerId)->increment('saves');

        return response()->json(['success' => true, 'saved' => true]);
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
}