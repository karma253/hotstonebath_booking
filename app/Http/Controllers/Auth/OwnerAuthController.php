<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Bath;
use App\Models\VerificationDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class OwnerAuthController extends Controller
{
    /**
     * Register a new owner/provider.
     */
    public function registerOwner(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'phone' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:owner,manager',
            
            // Bath Details
            'bath_name' => 'required|string|max:255',
            'property_type' => 'required|in:hot_stone_bath,hot_spring,thermal_pool',
            'dzongkhag_id' => 'required|exists:dzongkhags,id',
            'full_address' => 'required|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'short_description' => 'required|string',
            'detailed_description' => 'nullable|string',
            
            // Legal Details
            'tourism_license_number' => 'required|string',
            'issuing_authority' => 'required|string',
            'license_issue_date' => 'required|date',
            'license_expiry_date' => 'required|date|after:license_issue_date',
            
            // Document Uploads
            'tourism_license_doc' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'property_proof_doc' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'declaration' => 'required|accepted',
        ]);

        try {
            // Create user
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'password' => Hash::make($validated['password']),
                'role' => $validated['role'],
                'status' => 'pending_verification',
            ]);

            // Create bath
            $bath = Bath::create([
                'owner_id' => $user->id,
                'name' => $validated['bath_name'],
                'property_type' => $validated['property_type'],
                'dzongkhag_id' => $validated['dzongkhag_id'],
                'full_address' => $validated['full_address'],
                'latitude' => $validated['latitude'],
                'longitude' => $validated['longitude'],
                'short_description' => $validated['short_description'],
                'detailed_description' => $validated['detailed_description'],
                'tourism_license_number' => $validated['tourism_license_number'],
                'issuing_authority' => $validated['issuing_authority'],
                'license_issue_date' => $validated['license_issue_date'],
                'license_expiry_date' => $validated['license_expiry_date'],
                'status' => 'pending_verification',
            ]);

            // Upload documents
            if ($request->hasFile('tourism_license_doc')) {
                $path = $request->file('tourism_license_doc')->store('documents/tourism_license', 'public');
                VerificationDocument::create([
                    'bath_id' => $bath->id,
                    'document_type' => 'tourism_license',
                    'document_path' => $path,
                    'verification_status' => 'pending',
                ]);
            }

            if ($request->hasFile('property_proof_doc')) {
                $path = $request->file('property_proof_doc')->store('documents/property_proof', 'public');
                $docType = $request->input('property_type_doc', 'property_ownership');
                VerificationDocument::create([
                    'bath_id' => $bath->id,
                    'document_type' => $docType,
                    'document_path' => $path,
                    'verification_status' => 'pending',
                ]);
            }

            return response()->json([
                'message' => 'Registration successful. Your account is pending verification.',
                'user' => $user,
                'bath' => $bath,
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Owner login.
     */
    public function loginOwner(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        if (!$user->isOwner()) {
            return response()->json(['error' => 'Not authorized as owner'], 403);
        }

        if (!$user->isApproved()) {
            return response()->json([
                'error' => 'Account not approved',
                'status' => $user->status,
                'message' => $user->status === 'rejected' 
                    ? 'Your account has been rejected. Reason: ' . $user->rejection_reason
                    : 'Your account is still pending verification.'
            ], 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'user' => $user,
            'token' => $token,
        ]);
    }
}
