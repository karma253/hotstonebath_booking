@component('mail::message')
# 🆕 New User Registration - Approval Required

Hello Admin,

A new user has registered on the Hot Stone Bath System and is awaiting your approval.

## User Details

**Name:** {{ $user->name }}
**Email:** {{ $user->email }}
**Phone:** {{ $user->phone }}
**Address:** {{ $user->address ?? 'Not provided' }}
**Role:** {{ ucfirst($user->role) }}
**Registration Date:** {{ $user->created_at->format('M d, Y \a\t H:i A') }}

---

## Action Required

Please review this user account and either approve or reject their registration.

@component('mail::button', ['url' => route('admin.users') . '?status=pending_verification'])
Review & Approve User
@endcomponent

---

## Status
**Current Status:** ⏳ Pending Verification

This user cannot access the booking system until you approve their account.

---

Thanks,
{{ config('app.name') }} Team
@endcomponent
