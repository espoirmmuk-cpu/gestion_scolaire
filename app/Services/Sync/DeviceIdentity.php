<?php

namespace App\Services\Sync;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DeviceIdentity
{
    public function get(): string
    {
        $path = 'sync/device_id.txt';

        if (Storage::disk('local')->exists($path)) {
            return trim(Storage::disk('local')->get($path));
        }

        $deviceId = (string) Str::uuid();

        Storage::disk('local')->put($path, $deviceId);

        return $deviceId;
    }
}