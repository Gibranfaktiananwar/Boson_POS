<?php

namespace App\Http\Controllers\AdminToko;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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

    public function generateToken(Request $request)
    {
        $uid = config('services.inventory_api.uid');
        $secret = config('services.inventory_api.secret');
        $tokenUrl = config('services.inventory_api.token_url');

        $response = Http::asForm()->post($tokenUrl, [
            'uuid' => $uid,
            'secret' => $secret,
        ]);

        if ($response->successful() && $response->json('success')) {
            $token = $response->json('content.:token') ?? $response->json('content.token');

            // Simpan ke cache selama 3600 detik (1 jam)
            Cache::put('inventory_token', $token, now()->addSeconds(3600));

            return response()->json([
                'success' => true,
                'token' => $token,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $response->json('message') ?? 'Gagal generate token',
        ], 400);
    }

    public function checkSerial(Request $request)
    {
        $serial = $request->input('sn');
        $token = Cache::get('inventory_token');
        $checkUrl = config('services.inventory_api.check_url');

        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Token tidak tersedia atau sudah expired. Silakan generate ulang.',
            ], 401);
        }

        $response = Http::asForm()->post($checkUrl, [
            'sn' => $serial,
            'token' => $token,
        ]);

        if ($response->successful() && $response->json('success')) {
            return response()->json([
                'success' => true,
                'data' => $response->json(),
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $response->json('message') ?? 'Gagal memeriksa serial number',
        ], 400);
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
