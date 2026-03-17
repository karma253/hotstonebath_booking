# Hot Stone Bath System - Testing Guide

## 🚀 Quick Start - Create Test Users

Open terminal in your project directory and run:

```bash
cd c:\xampp\htdocs\hot_stone_bath_system\hot_stone_bath_system
php artisan tinker
```

### 1. Create Admin User
```php
App\Models\User::create([
    'name' => 'Admin User',
    'email' => 'admin@example.com',
    'phone' => '+975-17-000000',
    'password' => bcrypt('password123'),
    'role' => 'admin',
    'status' => 'active'
]);
```

### 2. Create Guest User
```php
App\Models\User::create([
    'name' => 'Guest User',
    'email' => 'guest@example.com',
    'phone' => '+975-17-111111',
    'password' => bcrypt('password123'),
    'role' => 'guest',
    'status' => 'active'
]);
```

Then exit tinker with: `exit`

---

## 🧪 API Testing with cURL

### Test 1: Admin Login

```bash
curl -X POST http://localhost/api/auth/owner/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "admin@example.com",
    "password": "password123"
  }'
```

**Expected Response:**
```json
{
  "message": "Login successful",
  "user": {
    "id": 1,
    "name": "Admin User",
    "email": "admin@example.com",
    "role": "admin",
    "status": "active"
  },
  "token": "eyJ0eXAiOiJKV1QiLCJhbGc..."
}
```

Copy the token for subsequent admin requests.

---

### Test 2: Get Admin Dashboard Statistics

```bash
curl -X GET http://localhost/api/admin/dashboard/stats \
  -H "Authorization: Bearer YOUR_ADMIN_TOKEN" \
  -H "Content-Type: application/json"
```

---

### Test 3: Guest Login

```bash
curl -X POST http://localhost/api/auth/guest/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "guest@example.com",
    "password": "password123"
  }'
```

---

### Test 4: Get All Dzongkhags (Public)

```bash
curl -X GET http://localhost/api/baths/dzongkhags \
  -H "Content-Type: application/json"
```

**Response:**
```json
[
  {
    "id": 1,
    "name": "Thimphu",
    "bhutanese_name": "ཐིམ་ཕུ།"
  },
  {
    "id": 2,
    "name": "Paro",
    "bhutanese_name": "སྤ་རོ།"
  },
  ...
]
```

---

### Test 5: Search Baths (Public)

```bash
curl -X GET "http://localhost/api/baths/search?dzongkhag_id=1&sort_by=newest" \
  -H "Content-Type: application/json"
```

---

### Test 6: Register as Owner (Step by Step)

#### Via Postman (Recommended for file uploads):

1. **Method:** POST
2. **URL:** `http://localhost/api/auth/owner/register`
3. **Headers:**
   - Content-Type: multipart/form-data
4. **Body (form-data):**

```
name: Test Owner
email: owner@example.com
phone: +975-17-222222
password: password123
password_confirmation: password123
role: owner
bath_name: Stone Sanctuary
property_type: hot_stone_bath
dzongkhag_id: 1
full_address: Main Road, Thimphu
latitude: 27.5142
longitude: 89.6432
short_description: Premium hot stone experience
detailed_description: Relax in our natural hot stone baths
tourism_license_number: TLN-2024-001
issuing_authority: Ministry of Tourism
license_issue_date: 2024-01-01
license_expiry_date: 2027-01-01
declaration: 1
tourism_license_doc: (upload PDF/Image)
property_proof_doc: (upload PDF/Image)
```

**Expected Response:**
```json
{
  "message": "Registration successful. Your account is pending verification.",
  "user": {
    "id": 4,
    "name": "Test Owner",
    "email": "owner@example.com",
    "status": "pending_verification"
  },
  "bath": {
    "id": 1,
    "name": "Stone Sanctuary",
    "status": "pending_verification"
  }
}
```

---

### Test 7: View Pending Owners (Admin)

```bash
curl -X GET http://localhost/api/admin/pending-owners \
  -H "Authorization: Bearer YOUR_ADMIN_TOKEN" \
  -H "Content-Type: application/json"
```

---

### Test 8: Approve Owner (Admin)

```bash
curl -X POST http://localhost/api/admin/owner/4/approve \
  -H "Authorization: Bearer YOUR_ADMIN_TOKEN" \
  -H "Content-Type: application/json"
```

**Note:** Replace `4` with the actual owner user ID

---

### Test 9: Owner Login (After Approval)

```bash
curl -X POST http://localhost/api/auth/owner/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "owner@example.com",
    "password": "password123"
  }'
```

Save this token for provider requests.

---

### Test 10: Get Bath Details (Provider)

```bash
curl -X GET http://localhost/api/provider/dashboard \
  -H "Authorization: Bearer YOUR_PROVIDER_TOKEN" \
  -H "Content-Type: application/json"
```

---

### Test 11: Add Bath Service (Provider)

```bash
curl -X POST http://localhost/api/provider/services \
  -H "Authorization: Bearer YOUR_PROVIDER_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "service_type": "Standard Bath Experience",
    "description": "60-minute relaxation",
    "duration_minutes": 60,
    "price": 3000,
    "max_guests": 6
  }'
```

---

### Test 12: Set Availability (Provider)

```bash
curl -X POST http://localhost/api/provider/availability \
  -H "Authorization: Bearer YOUR_PROVIDER_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "availabilities": [
      {
        "day_of_week": 0,
        "opening_time": "09:00",
        "closing_time": "18:00",
        "is_open": true
      },
      {
        "day_of_week": 1,
        "opening_time": "09:00",
        "closing_time": "18:00",
        "is_open": true
      }
    ]
  }'
```

**Day of week:** 0=Sunday, 1=Monday, ..., 6=Saturday

---

### Test 13: Add Bath Facility (Provider)

```bash
curl -X POST http://localhost/api/provider/facilities \
  -H "Authorization: Bearer YOUR_PROVIDER_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "facility_name": "Changing Room",
    "description": "Well-equipped changing facilities with lockers"
  }'
```

---

### Test 14: Guest Register

```bash
curl -X POST http://localhost/api/auth/guest/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Alice Johnson",
    "email": "alice@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

---

### Test 15: Search Available Slots

```bash
curl -X GET "http://localhost/api/baths/1/available-slots?date=2024-02-15&service_id=1" \
  -H "Content-Type: application/json"
```

---

### Test 16: Create Booking (Guest)

```bash
curl -X POST http://localhost/api/bookings \
  -H "Authorization: Bearer YOUR_GUEST_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "bath_id": 1,
    "service_id": 1,
    "booking_date": "2024-02-15",
    "start_time": "09:00",
    "number_of_guests": 4,
    "payment_method": "on_site",
    "special_requests": "Please prepare fresh towels"
  }'
```

---

### Test 17: Provider View Pending Bookings

```bash
curl -X GET http://localhost/api/provider/bookings/pending \
  -H "Authorization: Bearer YOUR_PROVIDER_TOKEN" \
  -H "Content-Type: application/json"
```

---

### Test 18: Provider Confirm Booking

```bash
curl -X POST http://localhost/api/provider/bookings/1/confirm \
  -H "Authorization: Bearer YOUR_PROVIDER_TOKEN" \
  -H "Content-Type: application/json"
```

---

### Test 19: Guest View My Bookings

```bash
curl -X GET http://localhost/api/bookings \
  -H "Authorization: Bearer YOUR_GUEST_TOKEN" \
  -H "Content-Type: application/json"
```

---

### Test 20: Submit Review (Guest)

First, mark booking as completed:

```bash
curl -X POST http://localhost/api/provider/bookings/1/complete \
  -H "Authorization: Bearer YOUR_PROVIDER_TOKEN" \
  -H "Content-Type: application/json"
```

Then submit review:

```bash
curl -X POST http://localhost/api/reviews/1 \
  -H "Authorization: Bearer YOUR_GUEST_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "rating": 5,
    "comment": "Amazing experience! Highly recommend!"
  }'
```

---

## 🔍 Testing with Postman

1. **Import Collection:**
   - Create a new Postman collection called "Hot Stone Bath API"

2. **Set Variables:**
   - Right-click collection → Edit
   - Go to Variables tab
   - Add:
     - `base_url`: `http://localhost/api`
     - `admin_token`: (paste token after admin login)
     - `provider_token`: (paste token after provider login)
     - `guest_token`: (paste token after guest login)

3. **Create Requests:**
   - Use `{{base_url}}` in URLs
   - Use `{{admin_token}}` in Authorization headers
   - Save each request

---

## ✅ Testing Checklist

- [ ] Admin user created
- [ ] Guest user created
- [ ] Admin can view dashboard statistics
- [ ] Owner can register (with valid documents)
- [ ] Admin can view pending owners
- [ ] Admin can approve owner
- [ ] Approved owner can login
- [ ] Owner can add services
- [ ] Owner can set availability (all days)
- [ ] Owner can add facilities
- [ ] Guest can search baths
- [ ] Guest can view bath details
- [ ] Guest can get available slots
- [ ] Guest can create booking
- [ ] Provider can view pending bookings
- [ ] Provider can confirm booking
- [ ] Guest can cancel booking
- [ ] Provider can mark booking as completed
- [ ] Guest can submit review
- [ ] Provider can view bookings report

---

## 🐛 Debugging Tips

### Check MySQL Database
```bash
# Open MySQL command line
mysql -u root

# Use the database
use hot_stone_bath_system_db;

# Check tables
show tables;

# View users
select id, name, email, role, status from users;

# View baths
select id, name, owner_id, status from baths;

# View bookings
select id, booking_id, bath_id, guest_id, status from bookings;
```

### Check Laravel Logs
```bash
# Real-time logs
tail -f storage/logs/laravel.log

# Clear logs
php artisan log:clear
```

### Clear Cache
```bash
php artisan cache:clear
php artisan config:cache
```

---

## 📱 File Upload Testing

For owner registration, you need actual files:

1. **Create test files:**
   - License.pdf (or any PDF)
   - PropertyProof.png (or any image)

2. **In Postman:**
   - Use form-data body
   - Set `tourism_license_doc` as File type
   - Upload your test PDF
   - Set `property_proof_doc` as File type
   - Upload your test image

Files will be stored in `storage/app/public/documents/`

---

## 🎯 Expected Workflow

```
1. Admin logs in → Gets admin token
                ↓
2. Owner registers → Status: pending_verification
                ↓
3. Admin views pending owners → Approves owner
                ↓
4. Owner logs in → Gets provider token, can manage bath
                ↓
5. Owner adds: Services, Availability, Facilities, Images
                ↓
6. Guest registers → Gets guest token
                ↓
7. Guest searches baths → Finds owner's bath
                ↓
8. Guest checks availability → Sees available slots
                ↓
9. Guest creates booking → Status: pending/confirmed (based on booking_type)
                ↓
10. Owner confirms booking → Status: confirmed
                ↓
11. Guest cancels OR booking completes
                ↓
12. Guest submits review (if completed)
```

---

## 💾 Database Backup

Before making changes, backup your database:

```bash
# Backup
mysqldump -u root hot_stone_bath_system_db > backup.sql

# Restore
mysql -u root hot_stone_bath_system_db < backup.sql
```

---

**Ready to test!** Follow the testing checklist and refer to API_DOCUMENTATION.md for detailed endpoint specifications.
