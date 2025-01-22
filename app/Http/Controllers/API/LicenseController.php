<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\LicenseService;
use Illuminate\Http\Request;

class LicenseController extends Controller
{
    protected LicenseService $licenseService;

    public function __construct(LicenseService $licenseService)
    {
        $this->licenseService = $licenseService;
    }

    public function generate(Request $request)
    {
        $request->validate([
            'purchase_code' => 'required|string',
            'domain'        => 'nullable|string'
        ]);

        $purchaseCode = $request->input('purchase_code');
        $domain       = $request->input('domain');

        $result = $this->licenseService->generateLicense($purchaseCode, $domain);

        if (!$result['success']) {
            return response()->json(['error' => $result['message']], 400);
        }

        return response()->json([
            'message' => $result['message'],
            'license' => $result['license']
        ], 201);
    }

    public function activate(Request $request)
    {
        $request->validate([
            'license_key' => 'required|uuid',
            'domain'      => 'required|string'
        ]);
        $licenseKey = $request->input('license_key');
        $domain     = $request->input('domain');
        $result = $this->licenseService->activateLicense($licenseKey, $domain);
        if (!$result['success']) {
            return response()->json(['error' => $result['message']], 400);
        }

        return response()->json([
            'message' => $result['message'],
            'license' => $result['license']
        ], 200);
    }

    public function deactivate(Request $request)
    {
        $request->validate([
            'license_key' => 'required|uuid'
        ]);

        $licenseKey = $request->input('license_key');
        $result     = $this->licenseService->deactivateLicense($licenseKey);

        if (!$result['success']) {
            return response()->json(['error' => $result['message']], 400);
        }

        return response()->json([
            'message' => $result['message'],
            'license' => $result['license']
        ], 200);
    }
}