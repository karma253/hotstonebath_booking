# Multi-Owner Registration & Login Guide

## Overview
Your Hot Stone Bath System now supports **multiple independent owners** registering and managing their own bath facilities. Each owner has complete isolation - they can only see and manage their own listings and bookings.

## Current Test Owners

5 test owner accounts have been created for demonstration:

| Email | Password | Bath Name | Location |
|-------|----------|-----------|----------|
| sonam.tenzin@baths.com | Password123 | Himalayan Wellness Center | Thimphu |
| dawa.dorji@baths.com | Password123 | Paro Valley Therapeutic Baths | Paro |
| tshering.lhamo@baths.com | Password123 | Punakha Natural Baths | Punakha |
| kinley.wangmo@baths.com | Password123 | Bumthang Healing Sanctuary | Bumthang |
| phuntsho.gyelpo@baths.com | Password123 | Chhukha Mineral Springs | Chhukha |

## How Multiple Owners Work

### 1. **User Registration Process**

Owners can register at: `http://localhost:8000/owner/register`

The registration form collects:
- **Owner Details**: Name, email, phone number
- **Password**: Secure login credentials
- **Bath Information**: Name, location (Dzongkhag), address
- **Operational Details**: Price per session, max guests, operating hours
- **Facilities**: Available amenities (comma-separated)

### 2. **Status After Registration**

When an owner registers:
- ✔️ Their user account is created with `status: 'pending_verification'`
- ✔️ Their bath listing is created with `status: 'pending_verification'`
- ✔️ They receive a confirmation message to check with admin

### 3. **Admin Approval Workflow**

After registration, the **Admin** must approve the owner:

1. Admin logs in at: `http://localhost:8000/admin/login`
2. Goes to **Users** section
3. Reviews pending owner registrations
4. **Approves** (sets status to `'active'`) or **Rejects** (provides reason)

Once approved, the owner can login and manage their bath.

### 4. **Owner Login**

Owners login at: `http://localhost:8000/owner/login`

Using credentials created during registration:
- Email address
- Password

### 5. **Owner Dashboard**

After login, owners access: `http://localhost:8000/owner/dashboard`

Features available:
- 📋 View their bath listing details
- 📸 Upload bath images
- 💼 Add and manage services
- ⏰ Set availability schedules
- 📅 View bookings
- 💰 Track pricing
- 🔧 Update bath information

## Data Isolation

Each owner **only sees**:
- ✅ Their own bath listing
- ✅ Their own services
- ✅ Their own bookings
- ✅ Their own availability schedules

A owner **cannot see**:
- ❌ Other owners' baths
- ❌ Other owners' bookings
- ❌ Other owners' revenue

This isolation is enforced at the **controller level** using `where('owner_id', $user->id)`.

## Adding More Test Owners

### Option 1: Use the Registration Form (Recommended)
1. Visit `http://localhost:8000/owner/register`
2. Fill in all details
3. Submit for admin approval
4. Contact admin to approve the registration

### Option 2: Create via Seeder
Edit `database/seeders/MultipleOwnersSeeder.php` and add new owner entries to the `$ownersData` array:

```php
[
    'name' => 'New Owner Name',
    'email' => 'newemail@baths.com',
    'phone' => '1700000',
    'password' => 'NewPassword123',
    'bath_name' => 'New Bath Name',
    'dzongkhag' => 'Thimphu', // or any valid Dzongkhag
    'address' => 'Full Address Here',
    'description' => 'Bath description',
    'price' => 1000,
    'max_guests' => 6,
]
```

Then run:
```bash
php artisan db:seed --class=MultipleOwnersSeeder
```

### Option 3: Create via Tinker
```bash
php artisan tinker
```

```php
$owner = \App\Models\User::create([
    'name' => 'Owner Name',
    'email' => 'owner@email.com',
    'phone' => '1700000',
    'password' => bcrypt('password'),
    'role' => 'owner',
    'status' => 'active',
    'approved_at' => now(),
]);

$bath = \App\Models\Bath::create([
    'owner_id' => $owner->id,
    'name' => 'Bath Name',
    'dzongkhag_id' => 1, // Valid dzongkhag ID
    'full_address' => 'Address',
    'short_description' => 'Description',
    'detailed_description' => 'Details',
    'tourism_license_number' => 'LICENSE-123',
    'issuing_authority' => 'Tourism Bhutan',
    'license_issue_date' => now()->toDateString(),
    'license_expiry_date' => now()->addYear()->toDateString(),
    'max_guests' => 6,
    'price_per_session' => 1000,
    'status' => 'active',
    'verified_at' => now(),
]);
```

## Important Notes

### ✅ What's Fully Implemented
- Owner registration form
- Owner login system
- Individual owner dashboards
- Data isolation per owner
- Admin approval workflow
- Service management per owner
- Booking management per owner

### 📋 Owner Workflow
1. Register at `/owner/register`
2. Admin approves at `/admin/dashboard`
3. Login at `/owner/login`
4. Manage bath at `/owner/dashboard`
5. Add services at `/owner/service/add`
6. Update listing at `/owner/listing`

### 🔐 Key Database Relationships
```
User (role: 'owner')
  ├─ has_one: Bath
  │    ├─ has_many: BathService
  │    ├─ has_many: BathFacility
  │    ├─ has_many: BathImage
  │    ├─ has_many: Availability
  │    └─ has_many: Booking
  └─ has_many: Booking (through Bath)
```

### 🚀 Current System Status
✅ **Multiple owners can:**
- Register independently
- Login with their credentials
- Create and manage their own bath listing
- Add multiple services
- Set operating hours and availability
- View only their own bookings
- Track their own revenue (through admin)

✅ **Admin can:**
- View all pending registrations
- Approve or reject owners
- View all baths and bookings across all owners
- Manage users and transactions

## Testing the System

### Test Scenario 1: Login as Different Owners
```
Try logging in as each test owner:
1. sonam.tenzin@baths.com / Password123
2. dawa.dorji@baths.com / Password123
3. Different dashboards should show different baths
```

### Test Scenario 2: Data Isolation
```
1. Login as Owner 1 → See only their bath and bookings
2. Logout
3. Login as Owner 2 → See only their bath and bookings
4. Confirm no cross-contamination of data
```

### Test Scenario 3: Registration Process
```
1. Go to /owner/register
2. Register as a new owner with unique email
3. Try to login immediately (should fail - pending approval)
4. Admin approves at /admin/dashboard
5. Owner can now login successfully
```

## Troubleshooting

### Owner Can't Login
- ✓ Check email and password are correct
- ✓ Check status is 'active' (admin approval required)
- ✓ Check role is 'owner' in users table

### Owner Sees Other Owners' Data
- ⚠️ This indicates code issue - report immediately
- Database queries should always filter by `owner_id`

### Registration Form Not Loading
- ✓ Ensure Dzongkhag seeder has run: `php artisan migrate:fresh --seed`
- ✓ Check database connection
- ✓ Clear cache: `php artisan cache:clear`

## File References

### Key Controllers
- [app/Http/Controllers/Web/OwnerPortalController.php](../../app/Http/Controllers/Web/OwnerPortalController.php) - Owner logic
- [app/Http/Controllers/Auth/OwnerAuthController.php](../../app/Http/Controllers/Auth/OwnerAuthController.php) - Authentication

### Key Models
- [app/Models/User.php](../../app/Models/User.php) - User model with role checking
- [app/Models/Bath.php](../../app/Models/Bath.php) - Bath listing model

### Key Views
- [resources/views/web/auth/owner-register.blade.php](../../resources/views/web/auth/owner-register.blade.php) - Registration form
- [resources/views/web/auth/owner-login.blade.php](../../resources/views/web/auth/owner-login.blade.php) - Login form
- [resources/views/web/owner/dashboard.blade.php](../../resources/views/web/owner/dashboard.blade.php) - Owner dashboard

### Key Routes
- [routes/web.php](../../routes/web.php) - Owner routes (lines 43-56)

## Support Commands

### Create Admin User
```bash
php artisan tinker
> \App\Models\User::create(['name' => 'Admin', 'email' => 'admin@test.com', 'password' => bcrypt('password'), 'role' => 'admin', 'status' => 'active', 'approved_at' => now()])
```

### View All Owners
```bash
php artisan tinker
> \App\Models\User::where('role', 'owner')->get()
```

### Approve Pending Owner
```bash
php artisan tinker
> $user = \App\Models\User::where('email', 'owner@email.com')->first()
> $user->update(['status' => 'active', 'approved_at' => now()])
```

### Reset Owner Password
```bash
php artisan tinker
> $user = \App\Models\User::where('email', 'owner@email.com')->first()
> $user->update(['password' => bcrypt('newpassword')])
```
