@component('mail::message')
# 👋 Welcome to Hot Stone Bath System!

Hello {{ $user->name }},

Thank you for registering with **Hot Stone Bath System**! We're excited to have you on board.

---

## Account Status
**Status:** ⏳ Pending Admin Approval

Your account is currently under review by our admin team. This is a normal security procedure to ensure the quality and safety of our platform.

---

## What's Next?

Once your account is **approved** by our admins, you'll be able to:
- ✅ Browse all available hot stone baths
- ✅ Make bookings at your favorite locations
- ✅ Manage your reservations
- ✅ Write reviews and ratings

We typically approve accounts within **24-48 hours**.

---

## Your Information
- **Name:** {{ $user->name }}
- **Email:** {{ $user->email }}
- **Phone:** {{ $user->phone }}
- **Registration Date:** {{ $user->created_at->format('M d, Y \a\t H:i A') }}

---

## Questions?
If you have any questions or concerns, please don't hesitate to contact our support team.

---

Best regards,
{{ config('app.name') }} Team

**Note:** Please do not reply to this email. For support, please visit our contact page.
@endcomponent
