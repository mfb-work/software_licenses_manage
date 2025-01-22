<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\EnvatoService;
use Illuminate\Http\Request;

class EnvatoController extends Controller
{
    protected EnvatoService $envatoService;

    public function __construct(EnvatoService $envatoService)
    {
        $this->envatoService = $envatoService;
    }

    public function verifyPurchase(Request $request)
    {
        $request->validate([
            'purchase_code' => 'required|string'
        ]);

        $purchaseCode = $request->input('purchase_code');
        $verificationData = $this->envatoService->verifyPurchase($purchaseCode);

        if (!$verificationData) {
            return response()->json(['error' => 'Invalid purchase code'], 404);
        }

        return response()->json($verificationData, 200);
    }
}