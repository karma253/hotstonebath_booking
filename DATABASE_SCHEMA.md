# 📊 Database Schema & Entity Relationships

## Entity Relationship Diagram

```
┌─────────────────┐
│     USERS       │
├─────────────────┤
│ id (PK)         │
│ name            │
│ email (unique)  │
│ phone           │
│ password        │
│ role            │────────┐
│ status          │         │
│ rejection_reason│         │
│ approved_at     │         │
│ reviewed_at     │         │
│ timestamps      │         │
└─────────────────┘         │
       │                    │
       │ (1)                │ (1)
       │                    │
       ├──────────1:M──────┐│
       │                   ││
       │              ┌────▼──────────────┐
       │              │     BATHS         │
       │              ├───────────────────┤
       │              │ id (PK)           │
       │              │ owner_id (FK→U)   │◄──┤
       │              │ dzongkhag_id (FK) │
       │              │ name              │
       │              │ property_type     │
       │              │ full_address      │
       │              │ latitude/longitude│
       │              │ short_description │
       │              │ detailed_desc     │
       │              │ bath_license_no   │
       │              │ issuing_authority │
       │              │ license_date      │
       │              │ license_expiry    │
       │              │ max_guests        │
       │              │ price_per_hour    │
       │              │ booking_type      │
       │              │ cancellation_policy
       │              │ status            │
       │              │ verified_at       │
       │              │ timestamps        │
       │              └────┬──────────────┘
       │                   │ (1)
       │                   │
       │              ┌────┴──────────────────┐
       │              │                       │
       │              ├─────────1:M──────────┐│
       │              │                      ││
       │          ┌───▼─────────────┐    ┌──▼────────────────┐
       │          │ BATH_SERVICES   │    │ BATH_FACILITIES   │
       │          ├────────────────┤    ├───────────────────┤
       │          │ id (PK)        │    │ id (PK)           │
       │          │ bath_id (FK)   │    │ bath_id (FK)      │
       │          │ service_type   │    │ facility_name     │
       │          │ description    │    │ description       │
       │          │ duration_mins  │    │ is_available      │
       │          │ price          │    │ timestamps        │
       │          │ max_guests     │    └───────────────────┘
       │          │ is_available   │
       │          │ timestamps     │
       │          └───┬────────────┘
       │              │ (1)
       │              │
       │              └─────────1:M──────────┐
       │                                     │
       │              ┌──────────────────────▼──────┐
       │              │      BOOKINGS               │ (Status: pending, confirmed,
       │              ├─────────────────────────────┤  completed, cancelled)
       │              │ id (PK)                    │
       │              │ booking_id (unique)        │
       │              │ guest_id (FK→U)            │
       │              │ bath_id (FK)               │
       │              │ service_id (FK)            │
       │              │ guest_name                 │
       │              │ guest_email                │
       │              │ guest_phone                │
       │              │ booking_date               │
       │              │ start_time                 │
       │              │ end_time                   │
       │              │ number_of_guests           │
       │              │ total_price                │
       │              │ payment_method             │
       │              │ payment_status             │
       │              │ status                     │
       │              │ cancellation_reason        │
       │              │ special_requests           │
       │              │ timestamps                 │
       │              └──────┬────────────────────┘
       │                     │ (1)
       │                     │
       │              ┌──────▼──────────────┐
       │              │    REVIEWS          │
       │              ├─────────────────────┤
       │              │ id (PK)             │
       │              │ booking_id (FK)    │
       │              │ guest_id (FK)      │
       │              │ bath_id (FK)       │
       │              │ rating (1-5)       │
       │              │ comment             │
       │              │ timestamps          │
       │              └─────────────────────┘
       │
       │
       └──────────────1:M──────┐
                              │
      ┌───────────────────────▼────────────────┐
      │   VERIFICATION_DOCUMENTS                │
      ├──────────────────────────────────────┤
      │ id (PK)                               │
      │ bath_id (FK)                          │
      │ document_type (tourism_license,       │
      │               property_ownership,     │
      │               property_lease)         │
      │ document_path                         │
      │ verification_status                   │
      │ verification_notes                    │
      │ verified_at                           │
      │ timestamps                            │
      └──────────────────────────────────────┘


   ┌─────────────────────────┐
   │   DZONGKHAGS            │
   ├─────────────────────────┤
   │ id (PK)                 │
   │ name (unique)           │
   │ bhutanese_name          │
   │ description             │
   │ timestamps              │
   └──────────┬──────────────┘
              │ (1)
              │
              └─────────M:1──┐
                            │
                     (Baths)│


   ┌─────────────────────────┐
   │  AVAILABILITIES         │
   ├─────────────────────────┤
   │ id (PK)                 │
   │ bath_id (FK)            │
   │ day_of_week (0-6)       │
   │ opening_time            │
   │ closing_time            │
   │ is_open                 │
   │ timestamps              │
   └─────────────────────────┘


   ┌─────────────────────────┐
   │   BATH_IMAGES           │
   ├─────────────────────────┤
   │ id (PK)                 │
   │ bath_id (FK)            │
   │ image_path              │
   │ image_type              │
   │ description             │
   │ order                   │
   │ is_primary              │
   │ timestamps              │
   └─────────────────────────┘
```

---

## Data Types & Constraints

### USERS Table
| Column | Type | Constraint | Notes |
|--------|------|-----------|-------|
| id | BIGINT | PK, AUTO_INCREMENT | |
| name | VARCHAR(255) | NOT NULL | |
| email | VARCHAR(255) | UNIQUE, NOT NULL | |
| phone | VARCHAR(20) | NULLABLE | |
| password | VARCHAR(255) | NOT NULL | Hashed |
| role | ENUM | NOT NULL | guest, owner, manager, admin |
| status | ENUM | NOT NULL | pending_verification, approved, rejected, active, inactive, deregistered |
| rejection_reason | TEXT | NULLABLE | |
| approved_at | TIMESTAMP | NULLABLE | |
| reviewed_at | TIMESTAMP | NULLABLE | |
| email_verified_at | TIMESTAMP | NULLABLE | |

### BATHS Table
| Column | Type | Constraint | Notes |
|--------|------|-----------|-------|
| id | BIGINT | PK, AUTO_INCREMENT | |
| owner_id | BIGINT | FK→users | |
| dzongkhag_id | BIGINT | FK→dzongkhags | |
| name | VARCHAR(255) | NOT NULL | |
| property_type | ENUM | DEFAULT: hot_stone_bath | hot_stone_bath, hot_spring, thermal_pool |
| latitude | DECIMAL(10,7) | NULLABLE | |
| longitude | DECIMAL(10,7) | NULLABLE | |
| full_address | TEXT | NOT NULL | |
| short_description | TEXT | NOT NULL | |
| detailed_description | LONGTEXT | NULLABLE | |
| tourism_license_number | VARCHAR(255) | NOT NULL | |
| issuing_authority | VARCHAR(255) | NOT NULL | |
| license_issue_date | DATE | NOT NULL | |
| license_expiry_date | DATE | NOT NULL | |
| license_status | ENUM | DEFAULT: pending | valid, expired, pending |
| max_guests | INT | DEFAULT: 10 | |
| price_per_hour | DECIMAL(10,2) | DEFAULT: 0 | |
| booking_type | ENUM | DEFAULT: approval_required | instant, approval_required |
| cancellation_policy | TEXT | NULLABLE | |
| status | ENUM | DEFAULT: pending_verification | pending_verification, active, inactive, suspended |
| verified_at | TIMESTAMP | NULLABLE | |
| verification_notes | TEXT | NULLABLE | |

### BOOKINGS Table
| Column | Type | Constraint | Notes |
|--------|------|-----------|-------|
| id | BIGINT | PK | |
| booking_id | VARCHAR(255) | UNIQUE | BOOKING-YYYYMMDD-##### |
| guest_id | BIGINT | FK→users | |
| bath_id | BIGINT | FK→baths | |
| service_id | BIGINT | FK→bath_services | |
| guest_name | VARCHAR(255) | NOT NULL | |
| guest_email | VARCHAR(255) | NOT NULL | |
| guest_phone | VARCHAR(20) | NOT NULL | |
| booking_date | DATE | NOT NULL | |
| start_time | TIME | NOT NULL | HH:MM |
| end_time | TIME | NOT NULL | HH:MM |
| number_of_guests | INT | NOT NULL | |
| total_price | DECIMAL(10,2) | NOT NULL | |
| payment_method | ENUM | NOT NULL | online, on_site |
| payment_status | ENUM | DEFAULT: pending | pending, paid, failed, refunded |
| payment_date | TIMESTAMP | NULLABLE | |
| status | ENUM | DEFAULT: pending | pending, confirmed, completed, cancelled, no_show |

---

## Relationships Summary

### One-to-Many (1:M)
- Users → Baths (One user can own many baths)
- Users → Bookings (One guest can have many bookings)
- Users → Reviews (One guest can make many reviews)
- Baths → Services (One bath offers many services)
- Baths → Facilities (One bath has many facilities)
- Baths → Images (One bath has many images)
- Baths → Bookings (One bath has many bookings)
- Baths → Reviews (One bath receives many reviews)
- Baths → Availabilities (One bath has 7 availability slots)
- BathServices → Bookings (One service can be booked many times)
- Dzongkhags → Baths (One district has many baths)

### One-to-One (1:1)
- Bookings → Reviews (One booking has at most one review)

### Many-to-Many (Through Table)
- Verification Documents acts as a junction for Baths → Document Types

---

## Indexes Created

**Primary Keys:**
- users.id
- baths.id
- bath_services.id
- bookings.id
- reviews.id
- etc.

**Foreign Keys:**
- baths.owner_id → users.id
- baths.dzongkhag_id → dzongkhags.id
- bookings.guest_id → users.id
- bookings.bath_id → baths.id
- All other FK relationships

**Unique Constraints:**
- users.email
- bookings.booking_id
- dzongkhags.name
- availabilities (bath_id, day_of_week)
- reviews (booking_id, guest_id)

---

## Sample Data Types

### Dzongkhags (17 districts)
- Thimphu
- Paro
- Punakha
- Wangdue Phodrang
- Trongsa
- Bumthang
- Haa
- Gasa
- Chhukha
- Zhemgang
- Samdrup Jongkhar
- Tashi Yangtse
- Mongar
- Lhuentse
- Dagana
- Phobjikha
- Sambang

### Booking Statuses
```
pending        → Awaiting provider approval/payment
confirmed      → Approved and confirmed
completed      → Service rendered
cancelled      → Cancelled by guest or provider
no_show        → Guest didn't show up
```

### Payment Statuses
```
pending        → Not yet paid (on_site payment)
paid           → Payment received
failed         → Payment failed
refunded       → Refund issued
```

### User Statuses
```
pending_verification → Awaiting admin approval
approved            → Admin approved
rejected            → Admin rejected
active              → Can use system
inactive            → Account disabled
deregistered        → Deregistration request approved
```

---

## Soft Deletes

**BATHS table** uses soft deletes:
- Deleted baths remain in database with deleted_at timestamp
- Query automatically excludes soft-deleted records
- Restore deleted records if needed

---

## Migration Files Order

1. modify_users_table (add role, status)
2. create_dzongkhags_table
3. create_baths_table
4. create_bath_services_table
5. create_bath_facilities_table
6. create_bath_images_table
7. create_availabilities_table
8. create_verification_documents_table
9. create_bookings_table
10. create_reviews_table

All migrations have been run successfully ✅

---

## Database Statistics

- **Total Tables:** 10
- **Total Columns:** 130+
- **Relationships:** 20+
- **Constraints:** 30+
- **Seeded Data:** 17 dyonkhags

**Status:** ✅ Ready for production use
