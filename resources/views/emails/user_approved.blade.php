@component('mail::message')
# ✅ Account Approved!

Hello {{ $user->name }},

Great news! Your account on **Hot Stone Bath System** has been **approved** by our admin team!

---

## 🎉 You're All Set!

You can now start using our platform to:
- 🛁 Browse and book hot stone baths
- 📅 Manage your reservations
- ⭐ Write reviews and ratings
- 💬 Contact bath owners

---

## Get Started

@component('mail::button', ['url' => route('home')])
Browse Hot Stone Baths
@endcomponent

---

## Your Account Details
- **Name:** {{ $user->name }}
- **Email:** {{ $user->email }}
- **Status:** ✅ **Active**
- **Approval Date:** {{ now()->format('M d, Y \a\t H:i A') }}

---

## Need Help?
Visit our platform or contact our support team if you have any questions.

---

Happy exploring!
{{ config('app.name') }} Team
@endcomponent
