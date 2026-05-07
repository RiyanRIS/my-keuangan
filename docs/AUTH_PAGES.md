# 🔐 Authentication Pages Documentation

## Overview

Halaman login dan register yang mobile-friendly dengan modern UI menggunakan stack:

- **Tailwind CSS** - Styling & Layout
- **Font Awesome** - Icons
- **SweetAlert2** - Alerts & Modals
- **jQuery** - Form Handling & AJAX

---

## 📁 File Structure

``` rest
resources/views/
├── auth/
│   ├── login.blade.php      # Halaman login
│   └── register.blade.php   # Halaman registrasi
└── dashboard.blade.php      # Halaman dashboard (placeholder)
```

---

## 🎨 Features

### Login Page (`/login`)

✅ **UI Features:**

- Gradient background mobile-friendly
- Max width container (max-w-sm = 448px)
- Password toggle visibility
- Remember me checkbox
- Responsive card design

✅ **Functionality:**

- Email & password validation
- Error messages display
- Loading spinner
- AJAX form submission
- Token storage in localStorage
- Redirect to dashboard on success

✅ **Error Handling:**

- Validation errors dari API
- Unauthorized (401) handling
- Generic error handling
- Form error display inline

**Route:** `GET /login`

**Fields:**

- Email (required, email format)
- Password (required, min 8 characters)
- Remember Me (checkbox)

**Response Handling:**

```javascript
// Success: Simpan token & user data
localStorage.setItem('api_token', response.data.token);
localStorage.setItem('user', JSON.stringify(response.data.user));
// Redirect ke /dashboard

// Error: Tampilkan SweetAlert2
```

---

### Register Page (`/register`)

✅ **UI Features:**

- Gradient background mobile-friendly
- Max width container
- Password toggle visibility (2 fields)
- Terms & Conditions checkbox
- Same card design as login

✅ **Functionality:**

- Name, Email, Password validation
- Password confirmation matching
- AJAX form submission
- Client-side terms validation
- Token storage & redirect

✅ **Error Handling:**

- Per-field error messages
- Terms agreement validation
- API validation errors

**Route:** `GET /register`

**Fields:**

- Name (required, string, max 255)
- Email (required, email, unique)
- Password (required, min 8, confirmed)
- Password Confirmation
- Terms & Conditions (required)

**Validation:**

```php
'name' => 'required|string|max:255',
'email' => 'required|string|email|max:255|unique:users',
'password' => 'required|string|min:8|confirmed',
```

---

### Dashboard Page (`/dashboard`)

✅ **Features:**

- User info display (ID, Name, Email)
- Logout button
- Token validation check
- Auto redirect to login if no token

**Route:** `GET /dashboard`

---

## 🚀 Usage

### Access Login

``` rest
GET http://localhost/login
```

### Access Register

``` rest
GET http://localhost/register
```

### Access Dashboard (Protected)

``` rest
GET http://localhost/dashboard
```

Memerlukan valid token di localStorage

---

## 📱 Mobile Responsive

**Breakpoints:**

- **Mobile:** `w-full max-w-sm` (max 448px)
- **Padding:** `p-4` mobile, `p-6 sm:p-8` desktop
- **Font Size:** Responsive dengan Tailwind classes

**Tested on:**

- ✅ iPhone SE (375px)
- ✅ iPhone 12/13/14 (390px)
- ✅ Pixel 5 (393px)
- ✅ iPad (768px)

---

## 🔗 API Integration

### Login Endpoint

``` json
POST /api/auth/login
Content-Type: application/json

{
  "email": "user@example.com",
  "password": "password123"
}

Response:
{
  "success": true,
  "message": "Login successful",
  "data": {
    "user": { ... },
    "token": "1|abcdef...",
    "token_type": "Bearer"
  },
  "meta": null
}
```

### Register Endpoint

``` json
POST /api/auth/register
Content-Type: application/json

{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}

Response: Same as login
```

---

## 🛠️ How to Use

### 1. Login Flow

1. User opens `/login`
2. Enter email & password
3. Click "Masuk"
4. AJAX POST to `/api/auth/login`
5. Success: Token stored → Redirect to `/dashboard`
6. Error: Show SweetAlert2 error

### 2. Register Flow

1. User opens `/register`
2. Fill: Name, Email, Password, Confirmation
3. Check Terms & Conditions
4. Click "Daftar"
5. AJAX POST to `/api/auth/register`
6. Success: Token stored → Redirect to `/dashboard`
7. Error: Show inline validation errors

### 3. Logout Flow

1. User di `/dashboard`
2. Click "Logout"
3. AJAX POST to `/api/auth/logout` with token
4. Clear localStorage
5. Redirect to `/login`

---

## 🎯 Customization

### Change Colors

**Tailwind classes untuk ubah warna:**

- `from-blue-600 to-indigo-600` → Gradient
- `bg-gradient-to-br from-blue-50 to-indigo-100` → Background

**Contoh ubah ke green:**

```html
<!-- Ubah dari: -->
<div class="from-blue-600 to-indigo-600">

<!-- Menjadi: -->
<div class="from-green-600 to-emerald-600">
```

### Add Additional Fields

```html
<!-- Tambah di form: -->
<div>
    <label class="block text-sm font-semibold text-gray-700 mb-2">
        <i class="fas fa-phone mr-2 text-blue-600"></i>Phone
    </label>
    <input type="tel" name="phone" class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg">
</div>
```

### Change Icons

**Font Awesome icons:**

```html
<!-- List of commonly used icons: -->
<i class="fas fa-user"></i>           <!-- User -->
<i class="fas fa-envelope"></i>       <!-- Email -->
<i class="fas fa-lock"></i>           <!-- Password -->
<i class="fas fa-eye"></i>            <!-- Eye -->
<i class="fas fa-wallet"></i>         <!-- Wallet -->
<i class="fas fa-sign-in-alt"></i>    <!-- Sign In -->
<i class="fas fa-user-plus"></i>      <!-- User Plus -->
```

---

## ⚡ Performance Tips

✅ **Lazy Load Libraries:**

- Font Awesome dari CDN
- jQuery dari CDN
- SweetAlert2 dari CDN

✅ **Client-side Validation:**

- Cek sebelum POST ke server
- Kurangi network requests

✅ **Error Handling:**

- Show inline errors untuk UX lebih baik
- SweetAlert2 hanya untuk critical errors

---

## 🔒 Security Notes

⚠️ **Important:**

1. **Token Storage**: Stored in localStorage (not most secure)
   - Alternatif: HttpOnly Cookies (better)
2. **API Endpoint**: Ensure HTTPS in production
3. **CORS**: Configure allowed origins
4. **Input Validation**: Always validate on server side
5. **Password**: Use strong hashing (already in code)

**Recommendation untuk production:**

```javascript
// Gunakan HttpOnly Cookies instead:
// Server set: Set-Cookie: token=...; HttpOnly; Secure; SameSite=Strict

// Client AJAX akan auto include cookies
// Jangan localStorage untuk sensitive data
```

---

## 📚 Dependencies

Sudah included di CDN:

```html
<!-- Tailwind CSS (via @vite) -->
<!-- Font Awesome 6.4.0 -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<!-- SweetAlert2 11 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- jQuery 3.6.0 -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
```

---

## 🐛 Troubleshooting

### "CSRF token mismatch"

**Solution:** Tambahkan @csrf di form (sudah ada)

### API tidak merespons

**Check:**

1. Server running (`php artisan serve`)
2. API token valid
3. CORS configured
4. Network tab di DevTools

### Token tidak tersimpan

**Check:**

1. localStorage.getItem('api_token') di console
2. API response include token field
3. Browser allow localStorage

### Password toggle tidak bekerja

**Check:**

1. Font Awesome loaded
2. JavaScript tidak ada error di console
3. Icon class `fa-eye` vs `fa-eye-slash`

---

## 📞 Support

**Untuk questions atau issues:**

- Check browser console untuk error messages
- Use Network tab untuk check API responses
- Verify localStorage content di DevTools
- Test dengan Postman sebelum frontend

---

**Last Updated:** 2026-05-07  
**Version:** 1.0
