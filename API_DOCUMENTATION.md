# Hot Stone Bath Booking System - API Documentation

## System Overview

This is a complete hot stone bath booking system with:
- **Owner/Provider Registration & Verification**
- **Admin Approval System**
- **Guest Search & Booking**
- **Booking Management**
- **Review System**

## Database Setup ✓

All migrations have been run successfully. The following tables are created:
- users (with role and status fields)
- dzongkhags (all 17 Bhutanese districts)
- baths
- bath_services
- bath_facilities
- bath_images
- availabilities
- verification_documents
- bookings
- reviews

## API Base URL
```
http://localhost/api
```

## Authentication

The system uses **Laravel Sanctum** for API authentication. Include the token in the header:
```
Authorization: Bearer {token}
```

---

## 1️⃣ OWNER / PROVIDER REGISTRATION & FLOW

### 1.1 Register as Owner/Provider

**Endpoint:** `POST /api/auth/owner/register`

**Request:**
```json
{
  "name": "John Doe",
  "email": "owner@example.com",
  "phone": "+975-17-123456",
  "password": "password123",
  "password_confirmation": "password123",
  "role": "owner",
  
  "bath_name": "Hot Stone Paradise Bath",
  "property_type": "hot_stone_bath",
  "dzongkhag_id": 1,
  "full_address": "Main Street, Thimphu",
  "latitude": 27.5142,
  "longitude": 89.6432,
  "short_description": "Premium hot stone bath experience",
  "detailed_description": "Relax in our natural hot stone baths with beautiful views",
  
  "tourism_license_number": "TLN-2024-001",
  "issuing_authority": "Ministry of Tourism",
  "license_issue_date": "2024-01-01",
  "license_expiry_date": "2027-01-01",
  
  "tourism_license_doc": <file>,
  "property_proof_doc": <file>,
  "declaration": true
}
```

**Response:**
```json
{
  "message": "Registration successful. Your account is pending verification.",
  "user": { ... },
  "bath": { ... }
}
```

**Status:** `201 Created`

---

### 1.2 Owner Login

**Endpoint:** `POST /api/auth/owner/login`

**Request:**
```json
{
  "email": "owner@example.com",
  "password": "password123"
}
```

**Response:**
```json
{
  "message": "Login successful",
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "owner@example.com",
    "role": "owner",
    "status": "active"
  },
  "token": "eyJ0eXAiOiJKV1QiLCJhbGc..."
}
```

---

## 2️⃣ ADMIN VERIFICATION FLOW

### 2.1 Admin Login
Same as owner login but with admin credentials.

### 2.2 Get Pending Owners for Verification

**Endpoint:** `GET /api/admin/pending-owners`

**Headers:**
```
Authorization: Bearer {admin_token}
```

**Response:**
```json
{
  "pending_count": 5,
  "owners": [
    {
      "id": 2,
      "name": "Jane Smith",
      "email": "jane@example.com",
      "phone": "+975-17-654321",
      "status": "pending_verification",
      "baths": [
        {
          "id": 1,
          "name": "Stone Sanctuary",
          "documents": [...]
        }
      ]
    }
  ]
}
```

### 2.3 Get Owner Details for Verification

**Endpoint:** `GET /api/admin/owner/{userId}`

### 2.4 Approve Owner Registration

**Endpoint:** `POST /api/admin/owner/{userId}/approve`

**Response:**
```json
{
  "message": "Owner approved successfully",
  "user": {
    "id": 2,
    "status": "active",
    "approved_at": "2024-01-15T10:30:00Z"
  }
}
```

### 2.5 Reject Owner Registration

**Endpoint:** `POST /api/admin/owner/{userId}/reject`

**Request:**
```json
{
  "rejection_reason": "Incomplete documentation"
}
```

### 2.6 Admin Dashboard Statistics

**Endpoint:** `GET /api/admin/dashboard/stats`

**Response:**
```json
{
  "pending_owners": 3,
  "approved_owners": 12,
  "active_baths": 12,
  "pending_baths": 3
}
```

---

## 3️⃣ PROVIDER DASHBOARD

After approval, provider can manage their bath.

### 3.1 Get Bath Details

**Endpoint:** `GET /api/provider/dashboard`

**Headers:**
```
Authorization: Bearer {provider_token}
```

**Response:**
```json
{
  "id": 1,
  "name": "Hot Stone Paradise",
  "services": [...],
  "facilities": [...],
  "images": [...],
  "availabilities": [...],
  "status": "active"
}
```

### 3.2 Update Bath Details

**Endpoint:** `PUT /api/provider/dashboard`

**Request:**
```json
{
  "name": "Updated Bath Name",
  "short_description": "Updated description",
  "booking_type": "instant"
}
```

### 3.3 Add Bath Service

**Endpoint:** `POST /api/provider/services`

**Request:**
```json
{
  "service_type": "Standard Bath Experience",
  "description": "60-minute relaxation bath",
  "duration_minutes": 60,
  "price": 3000,
  "max_guests": 6
}
```

### 3.4 Update Service

**Endpoint:** `PUT /api/provider/services/{serviceId}`

### 3.5 Delete Service

**Endpoint:** `DELETE /api/provider/services/{serviceId}`

### 3.6 Add Facility

**Endpoint:** `POST /api/provider/facilities`

**Request:**
```json
{
  "facility_name": "Changing Room",
  "description": "Well-equipped changing facilities"
}
```

### 3.7 Set Availability Schedule

**Endpoint:** `POST /api/provider/availability`

**Request:**
```json
{
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
}
```

**Note:** `day_of_week` = 0 (Sunday) to 6 (Saturday)

### 3.8 Upload Bath Images

**Endpoint:** `POST /api/provider/images`

**Request (multipart/form-data):**
```
images[0][image]: <file>
images[0][image_type]: bath_area
images[0][description]: Main bath area

images[1][image]: <file>
images[1][image_type]: stones
```

**image_type options:** `bath_area`, `stones`, `seating`, `exterior`, `facilities`

### 3.9 Publish Bath (Verify All Requirements)

**Endpoint:** `POST /api/provider/dashboard/publish`

**Requirements:**
- At least one service must be added
- Availability schedule must be set
- At least one image must be uploaded

### 3.10 Get Dashboard Statistics

**Endpoint:** `GET /api/provider/dashboard/stats`

**Response:**
```json
{
  "total_bookings": 25,
  "confirmed_bookings": 20,
  "completed_bookings": 15,
  "cancelled_bookings": 2,
  "total_revenue": 75000
}
```

---

## 4️⃣ PROVIDER BOOKING MANAGEMENT

### 4.1 Get All Bookings

**Endpoint:** `GET /api/provider/bookings`

**Query Parameters:**
- `page`: Page number (default: 1)
- `per_page`: Items per page (default: 20)

### 4.2 Get Pending Bookings (Awaiting Approval)

**Endpoint:** `GET /api/provider/bookings/pending`

### 4.3 Confirm Booking

**Endpoint:** `POST /api/provider/bookings/{bookingId}/confirm`

### 4.4 Reject Booking

**Endpoint:** `POST /api/provider/bookings/{bookingId}/reject`

**Request:**
```json
{
  "cancellation_reason": "Fully booked on that date"
}
```

### 4.5 Mark Booking as Completed

**Endpoint:** `POST /api/provider/bookings/{bookingId}/complete`

### 4.6 Mark Booking as No-Show

**Endpoint:** `POST /api/provider/bookings/{bookingId}/no-show`

### 4.7 Get Reports

**Endpoint:** `GET /api/provider/reports`

**Query Parameters:**
- `start_date`: Start date (YYYY-MM-DD)
- `end_date`: End date (YYYY-MM-DD)

**Response:**
```json
{
  "daily_bookings": [
    {
      "date": "2024-01-15",
      "count": 3,
      "revenue": 10000
    }
  ],
  "status_summary": [
    {"status": "confirmed", "count": 20},
    {"status": "completed", "count": 15}
  ]
}
```

---

## 5️⃣ GUEST / USER FLOW

### 5.1 Register as Guest

**Endpoint:** `POST /api/auth/guest/register`

**Request:**
```json
{
  "name": "Alice Johnson",
  "email": "guest@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

### 5.2 Guest Login

**Endpoint:** `POST /api/auth/guest/login`

**Request:**
```json
{
  "email": "guest@example.com",
  "password": "password123"
}
```

### 5.3 Guest Logout

**Endpoint:** `POST /api/auth/logout`

**Headers:**
```
Authorization: Bearer {guest_token}
```

---

## 6️⃣ GUEST SEARCH & DISCOVERY

### 6.1 Get All Dzongkhags

**Endpoint:** `GET /api/baths/dzongkhags`

**Response:**
```json
[
  {
    "id": 1,
    "name": "Thimphu",
    "bhutanese_name": "ཐིམ་ཕུ།"
  },
  ...
]
```

### 6.2 Search Hot Stone Baths

**Endpoint:** `GET /api/baths/search`

**Query Parameters:**
- `dzongkhag_id`: Filter by dzongkhag (optional)
- `booking_date`: Date for booking (YYYY-MM-DD, optional)
- `number_of_guests`: Number of guests (optional)
- `min_price`: Minimum price (optional)
- `max_price`: Maximum price (optional)
- `search`: Search by name/description (optional)
- `sort_by`: `price_asc`, `price_desc`, `rating`, `newest` (default: newest)
- `page`: Page number (default: 1)
- `per_page`: Items per page (default: 12)

**Example:**
```
GET /api/baths/search?dzongkhag_id=1&number_of_guests=4&sort_by=rating&page=1
```

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "name": "Hot Stone Paradise",
      "short_description": "Premium experience",
      "price_per_hour": 3000,
      "reviews_count": 45,
      "reviews_avg_rating": 4.5,
      "dzongkhag": {...},
      "services": [...],
      "images": [...]
    }
  ],
  "current_page": 1,
  "total": 25,
  "per_page": 12
}
```

### 6.3 Get Bath Details

**Endpoint:** `GET /api/baths/{bathId}`

**Response:**
```json
{
  "id": 1,
  "name": "Hot Stone Paradise",
  "description": "...",
  "facilities": [
    {"facility_name": "Changing Room"},
    {"facility_name": "Shower"},
    {"facility_name": "Towels"}
  ],
  "services": [
    {
      "id": 1,
      "service_type": "Standard Bath",
      "duration_minutes": 60,
      "price": 3000
    }
  ],
  "images": [...],
  "reviews": [
    {
      "guest_name": "John",
      "rating": 5,
      "comment": "Excellent experience!"
    }
  ]
}
```

### 6.4 Get Available Time Slots

**Endpoint:** `GET /api/baths/{bathId}/available-slots`

**Query Parameters:**
- `date`: Booking date (YYYY-MM-DD, required)
- `service_id`: Service ID (required)

**Response:**
```json
{
  "date": "2024-02-15",
  "available_slots": [
    {"start_time": "09:00", "end_time": "10:00"},
    {"start_time": "10:00", "end_time": "11:00"},
    {"start_time": "14:00", "end_time": "15:00"}
  ]
}
```

---

## 7️⃣ GUEST BOOKING MANAGEMENT

### 7.1 Create Booking

**Endpoint:** `POST /api/bookings`

**Headers:**
```
Authorization: Bearer {guest_token}
```

**Request:**
```json
{
  "bath_id": 1,
  "service_id": 1,
  "booking_date": "2024-02-15",
  "start_time": "09:00",
  "number_of_guests": 4,
  "payment_method": "online",
  "special_requests": "Please prepare fresh towels"
}
```

**Response:**
```json
{
  "message": "Booking created successfully",
  "booking": {
    "id": 5,
    "booking_id": "BOOKING-20240115-00005",
    "bath_id": 1,
    "booking_date": "2024-02-15",
    "start_time": "09:00",
    "end_time": "10:00",
    "total_price": 3000,
    "status": "confirmed",
    "payment_status": "pending"
  },
  "action_required": "payment"
}
```

### 7.2 Process Payment

**Endpoint:** `POST /api/bookings/{bookingId}/payment`

**Request:**
```json
{
  "payment_details": {
    "card_number": "xxxx-xxxx-xxxx-1234",
    "expiry": "12/25",
    "cvv": "123"
  }
}
```

### 7.3 Get My Bookings

**Endpoint:** `GET /api/bookings`

**Headers:**
```
Authorization: Bearer {guest_token}
```

### 7.4 Get Booking Details

**Endpoint:** `GET /api/bookings/{bookingId}`

### 7.5 Cancel Booking

**Endpoint:** `POST /api/bookings/{bookingId}/cancel`

**Request:**
```json
{
  "cancellation_reason": "Change of plans"
}
```

---

## 8️⃣ GUEST PROFILE & REVIEWS

### 8.1 Get Profile

**Endpoint:** `GET /api/profile`

**Headers:**
```
Authorization: Bearer {guest_token}
```

### 8.2 Update Profile

**Endpoint:** `PUT /api/profile`

**Request:**
```json
{
  "name": "Updated Name",
  "phone": "+975-17-999999",
  "email": "newemail@example.com"
}
```

### 8.3 Submit Review for Completed Booking

**Endpoint:** `POST /api/reviews/{bookingId}`

**Request:**
```json
{
  "rating": 5,
  "comment": "Amazing experience! Highly recommend!"
}
```

**Response:**
```json
{
  "message": "Review submitted successfully",
  "review": {
    "id": 10,
    "booking_id": 5,
    "rating": 5,
    "comment": "Amazing experience!",
    "created_at": "2024-01-20T15:30:00Z"
  }
}
```

### 8.4 Get My Reviews

**Endpoint:** `GET /api/reviews`

---

## 🧪 QUICK START / TESTING

### 1. Create Admin User (via tinker)

```bash
cd c:\xampp\htdocs\hot_stone_bath_system\hot_stone_bath_system
php artisan tinker
```

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

### 2. Test Owner Registration
- POST `http://localhost/api/auth/owner/register`
- Fill in all required fields with test data
- Verify success response

### 3. Test Admin Approval
- Create admin user first
- Login with admin credentials: `admin@example.com` / `password123`
- GET `http://localhost/api/admin/pending-owners` to see pending registrations
- POST `http://localhost/api/admin/owner/{userId}/approve` to approve

### 4. Test Provider Services
- Login with provider email after approval
- POST `/api/provider/services` to add services
- POST `/api/provider/availability` to set schedule
- POST `/api/provider/images` to upload bath images

### 5. Test Guest Booking
- Register as guest with `/api/auth/guest/register`
- Search baths with `/api/baths/search`
- Get available slots with `/api/baths/{bathId}/available-slots`
- Create booking with `POST /api/bookings`

---

## 🔐 Role-Based Access

| Endpoint | Guest | Owner | Admin |
|----------|-------|-------|-------|
| `/api/auth/*` | ✓ | ✓ | ✓ |
| `/api/baths/search` | ✓ | ✓ | ✓ |
| `/api/bookings` | ✓ | ✗ | ✗ |
| `/api/provider/*` | ✗ | ✓ | ✗ |
| `/api/admin/*` | ✗ | ✗ | ✓ |

---

## Error Responses

### 400 Bad Request
```json
{
  "error": "Validation error or bad request"
}
```

### 401 Unauthorized
```json
{
  "error": "The provided credentials are incorrect."
}
```

### 403 Forbidden
```json
{
  "error": "Unauthorized. Admin access required."
}
```

### 404 Not Found
```json
{
  "error": "Resource not found"
}
```

### 500 Internal Server Error
```json
{
  "error": "Internal server error message"
}
```

---

## 📝 Notes

1. **Booking Statuses:** `pending`, `confirmed`, `completed`, `cancelled`, `no_show`
2. **Payment Statuses:** `pending`, `paid`, `failed`, `refunded`
3. **User Statuses:** `pending_verification`, `approved`, `rejected`, `active`, `inactive`, `deregistered`
4. **Bath Statuses:** `pending_verification`, `active`, `inactive`, `suspended`
5. **Booking IDs** are auto-generated in format: `BOOKING-YYYYMMDD-##### `
6. **Dates** should be in `YYYY-MM-DD` format
7. **Times** should be in `HH:MM` format (24-hour)
8. **Timestamps** are returned in ISO 8601 format

---

## 📞 Support

For API issues or questions, refer to the Laravel and Sanctum documentation.
