<?php

namespace App\Services;

use App\Repositories\LicenseRepository;
use App\Repositories\ProductRepository;
use Illuminate\Support\Facades\Log;

class LicenseService
{
    protected LicenseRepository $licenseRepository;
    protected ProductRepository $productRepository;
    protected EnvatoService $envatoService;

    public function __construct(
        LicenseRepository $licenseRepository,
        ProductRepository $productRepository,
        EnvatoService $envatoService
    ) {
        $this->licenseRepository = $licenseRepository;
        $this->productRepository = $productRepository;
        $this->envatoService     = $envatoService;
    }

    public function generateLicense(string $purchaseCode, ?string $domain = null): array
    {
        $purchaseData = $this->envatoService->verifyPurchase($purchaseCode);

        if (!$purchaseData) {
            return [
                'success' => false,
                'message' => 'Invalid purchase code.',
            ];
        }

        $existingLicense = $this->licenseRepository->findByPurchaseCode($purchaseCode);
        if ($existingLicense) {
            return [
                'success' => false,
                'message' => 'License already exists for this purchase code.',
                'license' => $existingLicense
            ];
        }

        $envatoItemId = $purchaseData['item']['id'] ?? null;
        if (!$envatoItemId) {
            return [
                'success' => false,
                'message' => 'Could not retrieve Envato item ID from purchase data.'
            ];
        }

        $product = $this->productRepository->findByEnvatoItemId($envatoItemId);
        if (!$product) {
            $itemDetails = $this->envatoService->getItem($envatoItemId);
            if (!$itemDetails) {
                Log::error("Failed to retrieve item details from Envato for item ID: $envatoItemId");
                return [
                    'success' => false,
                    'message' => 'Failed to retrieve item details from Envato.'
                ];
            }
            $product = $this->productRepository->create([
                'envato_item_id' => $envatoItemId,
                'name'           => $itemDetails['name'] ?? 'Unknown Product'
            ]);
        }

        $licenseData = [
            'product_id'     => $product->id,
            'purchase_code'  => $purchaseCode,
            'buyer_username' => $purchaseData['buyer'] ?? null,
            'buyer_email'    => $purchaseData['buyer_email'] ?? null,
            'domain'         => $domain,
            'activated'      => false,
            'expires_at'     => null
        ];

        $license = $this->licenseRepository->create($licenseData);

        return [
            'success' => true,
            'message' => 'License generated successfully.',
            'license' => $license
        ];
    }

    public function activateLicense(string $licenseKey, string $domain): array
    {
        $license = $this->licenseRepository->findByLicenseKey($licenseKey);
        if (!$license) {
            return [
                'success' => false,
                'message' => 'Invalid license key.'
            ];
        }

        $license = $this->licenseRepository->activate($license, $domain);

        return [
            'success' => true,
            'message' => 'License activated successfully.',
            'license' => $license
        ];
    }

    public function deactivateLicense(string $licenseKey): array
    {
        $license = $this->licenseRepository->findByLicenseKey($licenseKey);
        if (!$license) {
            return [
                'success' => false,
                'message' => 'Invalid license key.'
            ];
        }

        $license = $this->licenseRepository->deactivate($license);

        return [
            'success' => true,
            'message' => 'License deactivated successfully.',
            'license' => $license
        ];
    }

    public function verifyDomain(string $licenseKey, string $domain): array
    {
        $license = $this->licenseRepository->findByLicenseKey($licenseKey);
        if (!$license) {
            return [
                'success' => false,
                'message' => 'Invalid license key.',
                'status'  => 'inactive'
            ];
        }

        if (!$license->activated) {
            return [
                'success' => true,
                'message' => 'License is not activated.',
                'status'  => 'inactive'
            ];
        }

        if ($license->domain === $domain ||
            $license->verifiedDomains->contains('domain', $domain)) {
            return [
                'success' => true,
                'message' => 'Domain verified.',
                'status'  => 'active'
            ];
        }

        return [
            'success' => false,
            'message' => 'Domain not verified.',
            'status'  => 'inactive'
        ];
    }
}
