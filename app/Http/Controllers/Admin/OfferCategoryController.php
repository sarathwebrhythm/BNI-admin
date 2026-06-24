<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\OfferCategoryService;
use App\Http\Requests\StoreOfferCategoryRequest;
use App\Http\Requests\UpdateOfferCategoryRequest;
use Illuminate\Http\Request;

class OfferCategoryController extends Controller
{
    protected $offerCategoryService;

    public function __construct(OfferCategoryService $offerCategoryService)
    {
        $this->offerCategoryService = $offerCategoryService;
    }

    public function index(Request $request)
    {
        $search = $request->input('search');

        $categories = $this->offerCategoryService->listCategories(15, $search);

        return view('offer-categories.index', compact('categories', 'search'));
    }

    public function create()
    {
        return view('offer-categories.create');
    }

    public function store(StoreOfferCategoryRequest $request)
    {

        $this->offerCategoryService->createCategory(
            $request->validated(),
            $request->file('icon')
        );

        return redirect()
            ->route('admin.offer-categories.index')
            ->with('success', 'Offer category created successfully.');
    }

    public function show($id)
    {
        $category = $this->offerCategoryService->getCategoryById($id);

        return view('offer-categories.show', compact('category'));
    }

    public function edit($id)
    {
        $category = $this->offerCategoryService->getCategoryById($id);

        return view('offer-categories.edit', compact('category'));
    }

    public function update(UpdateOfferCategoryRequest $request, $id)
    {
        $this->offerCategoryService->updateCategory(
            $id,
            $request->validated(),
            $request->file('icon')
        );

        return redirect()
            ->route('admin.offer-categories.index')
            ->with('success', 'Offer category updated successfully.');
    }

    public function destroy($id)
    {
        $this->offerCategoryService->deleteCategory($id);

        return redirect()
            ->route('admin.offer-categories.index')
            ->with('success', 'Offer category deleted successfully.');
    }
}
