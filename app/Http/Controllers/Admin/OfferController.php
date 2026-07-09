<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\OfferService;
use App\Models\OfferCategory;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    protected $offerService;

    public function __construct(OfferService $offerService)
    {
        $this->offerService = $offerService;
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $filters = [
            'status'   => $request->input('status'),
            'category' => $request->input('category'),
        ];

        $offers     = $this->offerService->listOffers(15, $search, $filters);
        $categories = OfferCategory::orderBy('name')->get(['id', 'name']);

        return view('offers.index', compact('offers', 'categories', 'search', 'filters'));
    }

    public function show($id)
    {
        $offer = $this->offerService->getOfferById($id);
        return view('offers.show', compact('offer'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:active,rejected,inactive,pending',
        ]);

        $this->offerService->updateStatus($id, $request->status);

        return redirect()->back()->with('success', 'Offer status updated successfully.');
    }

    public function destroy($id)
    {
        $this->offerService->deleteOffer($id);
        return redirect()->route('admin.offers.index')->with('success', 'Offer deleted successfully.');
    }
}