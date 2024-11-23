<?php

namespace App\Http\Controllers;

use App\Models\Preference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PreferencesController extends Controller
{
    /**
     * Store user preferences.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function setPreferences(Request $request)
    {
        // Validate input data
        $validated = $request->validate([
            'preferred_sources' => 'nullable|array',
            'preferred_sources.*' => 'string',
            'preferred_categories' => 'nullable|array',
            'preferred_categories.*' => 'string',
            'preferred_authors' => 'nullable|array',
            'preferred_authors.*' => 'string',
        ]);

        // Get the authenticated user
        $user = Auth::user();

        // Create or update preferences for the user
        $preference = $user->preferences()->updateOrCreate(
            ['user_id' => $user->id],
            $validated
        );

        return response()->json($preference);
    }

    /**
     * Retrieve user preferences.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPreferences()
    {
        // Get the authenticated user
        $user = Auth::user();

        // Retrieve preferences for the user
        $preference = $user->preference;

        // Return preferences if they exist
        if (!$preference) {
            return response()->json(['message' => 'Preferences not set'], 404);
        }

        return response()->json($preference);
    }
}
