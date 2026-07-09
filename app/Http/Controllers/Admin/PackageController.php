<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\PackageService;
use App\Http\Requests\StorePackageRequest;
use App\Http\Requests\UpdatePackageRequest;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    protected $packageService;

    public function __construct(PackageService $packageService)
    {
        $this->packageService = $packageService;
    }

    public function index(Request $request)
    {
        $search = $request->input('search');

        $packages = $this->packageService->listPackages(15, $search);

        return view('packages.index', compact('packages', 'search'));
    }

    public function create()
    {
        return view('packages.create');
    }

    public function store(StorePackageRequest $request)
    {
        $this->packageService->createPackage(
            $request->validated()
        );

        return redirect()
            ->route('admin.packages.index')
            ->with('success', 'Package created successfully.');
    }

    public function show($id)
    {
        $package = $this->packageService->getPackageById($id);

        return view('packages.show', compact('package'));
    }

    public function edit($id)
    {
        $package = $this->packageService->getPackageById($id);

        return view('packages.edit', compact('package'));
    }

    public function update(UpdatePackageRequest $request, $id)
    {
        $this->packageService->updatePackage(
            $id,
            $request->validated()
        );

        return redirect()
            ->route('admin.packages.index')
            ->with('success', 'Package updated successfully.');
    }

    public function destroy($id)
    {
        $this->packageService->deletePackage($id);

        return redirect()
            ->route('admin.packages.index')
            ->with('success', 'Package deleted successfully.');
    }
}