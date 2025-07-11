<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreUnitRequest;
use App\Services\UnitService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

final class UnitController extends Controller
{
    protected UnitService $unitService;

    public function __construct(UnitService $unitService)
    {
        $this->unitService = $unitService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $units = $this->unitService->getAllUnits();
        return view('pages.unit.index', compact('units'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort(404);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUnitRequest $request)
    {
        DB::beginTransaction();
        try {
            $this->unitService->createUnit($request->all());

            DB::commit();
            return redirect()->back()->withSuccess('Data satuan berhasil dibuat');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->withErrors("Gagal menambahkan satuan: " . $th->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        abort(404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreUnitRequest $request, string $id)
    {
        DB::beginTransaction();
        try {
            $this->unitService->updateUnit((int)$id, $request->all());

            DB::commit();
            return redirect()->back()->withSuccess('Data satuan berhasil diperbaharui');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->withErrors("Gagal memperbaharui satuan: " . $th->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): \Illuminate\Http\RedirectResponse
    {
        $this->unitService->deleteUnit((int)$id);
        return redirect()->back()->withSuccess('Data satuan berhasil dihapus');
    }
}
