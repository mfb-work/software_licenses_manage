<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\LicenseService;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    protected LicenseService $licenseService;

    public function __construct(LicenseService $licenseService)
    {
        $this->licenseService = $licenseService;
    }

    public function verifyDomain(Request $request)
    {
        $request->validate([
            'license_key' => 'required|uuid',
            'domain'      => 'required|string'
        ]);

        $licenseKey = $request->input('license_key');
        $domain     = $request->input('domain');

        $result = $this->licenseService->verifyDomain($licenseKey, $domain);

        if (!$result['success']) {
            return response()->json(['error' => $result['message']], 400);
        }

        return response()->json([
            'message' => $result['message'],
            'status'  => $result['status']
        ], 200);
    }
}
