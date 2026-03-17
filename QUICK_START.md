# ⚡ Quick Start Card

## Start Development Server

**Terminal 1 - API Server**
```bash
cd c:\xampp\htdocs\hot_stone_bath_system\hot_stone_bath_system
php artisan serve
```
API will be available at: `http://localhost:8000/api`

---

## Create Admin User (First Time Setup)

```bash
php artisan tinker
```

Then paste:
```php
App\Models\User::create([
    'name' => 'Admin',
    'email' => 'admin@example.com',
    'phone' => '+975-17-000000',
    'password' => bcrypt('password123'),
    'role' => 'admin',
    'status' => 'active'
]);
exit
```

---

## API Base URLs

**Login Endpoint:**
```
POST http://localhost:8000/api/auth/owner/login
or
POST http://localhost:8000/api/auth/guest/login
```

**Credentials:**
- Email: `admin@example.com`
- Password: `password123`

---

## Test API (Using cURL)

### 1. Admin Login
```bash
curl -X POST http://localhost:8000/api/auth/owner/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@example.com","password":"password123"}'
```

### 2. Get Dzongkhags (Public)
```bash
curl -X GET http://localhost:8000/api/baths/dzongkhags
```

### 3. Admin Stats
```bash
curl -X GET http://localhost:8000/api/admin/dashboard/stats \
  -H "Authorization: Bearer YOUR_TOKEN"
```

---

## Key Files & What They Do

| File | Purpose |
|------|---------|
| `app/Http/Controllers/` | All API logic |
| `app/Models/` | Database relationships |
| `routes/api.php` | All API endpoints |
| `database/migrations/` | Database schema |
| `API_DOCUMENTATION.md` | Complete API reference |
| `TESTING_GUIDE.md` | Detailed testing instructions |

---

## Database Info

**MySQL Access:**
```bash
mysql -u root hot_stone_bath_system_db
```

**Check Tables:**
```sql
show tables;
select * from users;
select * from baths;
select * from bookings;
```

---

## Common Commands

```bash
# Clear cache
php artisan cache:clear

# View logs
tail -f storage/logs/laravel.log

# Reset database
php artisan migrate:refresh --seed

# Create new migration
php artisan make:migration create_table_name

# Create new model
php artisan make:model ModelName
```

---

## Three User Roles

1. **Guest** - Search & book baths
2. **Owner/Manager** - Manage bath properties
3. **Admin** - Verify & approve owners

---

## Typical Workflow

```
1. Owner registers → status: pending_verification
2. Admin approves → status: active
3. Owner adds services, availability, images
4. Guest searches & finds bath
5. Guest books & pays
6. Owner confirms
7. Booking completed → Guest reviews
```

---

## Get Help

1. **API Endpoints:** See `API_DOCUMENTATION.md`
2. **Setup Issues:** See `SETUP_GUIDE.md`
3. **Testing System:** See `TESTING_GUIDE.md`
4. **Project Overview:** See `BUILD_SUMMARY.md`

---

## Project Status ✅

- ✅ Database ready
- ✅ All APIs implemented
- ✅ All routes configured
- ✅ Authentication working
- ✅ Role-based access ready
- ✅ Ready for frontend development

**Next:** Start testing API endpoints using TESTING_GUIDE.md

---

**API Base URL:** `http://localhost:8000/api`  
**Admin Login:** `admin@example.com` / `password123`
