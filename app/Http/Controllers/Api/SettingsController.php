<?php

namespace App\Http\Controllers\Api;

use App\Facades\Settings;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SettingsController extends Controller
{
    /**
     * Get all public settings
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $settings = Settings::public();

        $formattedSettings = $settings->mapWithKeys(function ($setting) {
            return [$setting->key => $setting->formatted_value];
        });

        return response()->json($formattedSettings);
    }

    /**
     * Get a specific public setting
     *
     * @param string $key
     * @return JsonResponse
     */
    public function show(string $key): JsonResponse
    {
        $settings = Settings::public();
        $setting = $settings->get($key);

        if (!$setting) {
            return response()->json(['error' => 'Setting not found or not public'], 404);
        }

        return response()->json([
            'key' => $setting->key,
            'value' => $setting->formatted_value
        ]);
    }
}
