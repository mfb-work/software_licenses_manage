<?php

namespace App\Repositories;

use App\Models\License;
use Illuminate\Support\Str;

class LicenseRepository
{
    public function create(array $data)
    {
        $data['license_key'] = Str::uuid()->toString();
        return License::create($data);
    }

    public function findByLicenseKey(string $licenseKey)
    {
        return License::where('license_key', $licenseKey)->first();
    }

    public function findByPurchaseCode(string $purchaseCode)
    {
        return License::where('purchase_code', $purchaseCode)->first();
    }

    public function activate(License $license, string $domain = null)
    {
        $license->activated = true;
        if ($domain) {
            $license->domain = $domain;
        }
        $license->save();

        return $license;
    }

    public function deactivate(License $license)
    {
        $license->activated = false;
        $license->save();

        return $license;
    }

    public function addVerifiedDomain(License $license, string $domain)
    {
        return $license->verifiedDomains()->create(['domain' => $domain]);
    }

    public function removeVerifiedDomain(License $license, string $domain)
    {
        return $license->verifiedDomains()->where('domain', $domain)->delete();
    }
}
