@component('mail::message')
# ❌ Account Rejected

Hello {{ $user->name }},

Unfortunately, your registration request on **Hot Stone Bath System** has been **rejected** by our admin team.

---

## Reason for Rejection

{{ $reason }}

---

## What Now?

If you believe this decision is incorrect or would like to provide additional information, please contact our support team.

---

## Contact Support
Feel free to reach out to us if you have any questions or concerns.

---

We appreciate your interest in Hot Stone Bath System!

{{ config('app.name') }} Team
@endcomponent
