<?php

namespace App\Http\Controllers\AdminToko;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ExternalInventoryService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class RedeemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admintoko.index');
    }

    protected $inventoryService;

    public function __construct(ExternalInventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    // Untuk API cek SN
    public function cekSN(Request $request)
    {
        $request->validate([
            'sn' => 'required|string'
        ]);

        $result = $this->inventoryService->checkSn($request->sn);

        return response()->json($result);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
