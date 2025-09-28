<?php

namespace App\Http\Controllers\AdminToko;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class RedeemController extends Controller
{


    public function index()
    {
        return view('admintoko.redeem.index');
    }

    public function generateToken(Request $request)
    {
        // Retrieve the UID, Secret, and URL token from env.
        $uid = config('services.inventory_api.uid');
        $secret = config('services.inventory_api.secret');
        $tokenUrl = config('services.inventory_api.token_url');

        // Send POST request to API token
        $response = Http::asForm()->post($tokenUrl, [
            'uuid' => $uid,
            'secret' => $secret,
        ]);

        // check response
        if ($response->successful() && $response->json('success')) {
            // Retrieve token from response
            $token = $response->json('content.:token') ?? $response->json('content.token');

            // Save token to cache for 1 hour
            Cache::put('inventory_token', $token, now()->addSeconds(3600));

            // Return JSON response on success
            return response()->json([
                'success' => true,
                'token' => $token,
            ]);
        }

        // If the above function fails, reply with a JSON error
        return response()->json([
            'success' => false,
            'message' => $response->json('message') ?? 'Gagal generate token',
        ], 400);
    }

    public function checkSerial(Request $request)
    {
        //Take serial number input
        $serial = $request->input('sn');
        $token = Cache::get('inventory_token'); //Retrieve token from cache
        $checkUrl = config('services.inventory_api.check_url'); // etrieve URL

        //Check token
        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Token tidak tersedia atau sudah expired. Silakan generate ulang.',
            ], 401);
        }

        // Send request to API check serial
        $response = Http::asForm()->post($checkUrl, [
            'sn' => $serial,
            'token' => $token,
        ]);

        //Check response
        if ($response->successful() && $response->json('success')) {
            return response()->json([
                'success' => true,
                'data' => $response->json(),
            ]);
        }

        // If the above fails, send an error response
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
