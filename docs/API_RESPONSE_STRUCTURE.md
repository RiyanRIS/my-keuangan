# API Response Structure

## Standard Response Format

Semua API response mengikuti format standard ini:

```json
{
  "success": true,
  "message": "Operation successful",
  "data": [],
  "meta": {}
}
```

## Field Penjelasan

| Field | Type | Deskripsi |
| ------- | ------ | ----------- |
| `success` | boolean | Status keberhasilan request (true/false) |
| `message` | string | Pesan deskriptif tentang hasil operasi |
| `data` | any | Data hasil operasi (bisa null, object, array) |
| `meta` | object | Metadata tambahan seperti pagination, filter, summary, etc |

---

## Contoh Response

### ✅ Success Response - GET List

```json
{
  "success": true,
  "message": "Transactions fetched successfully",
  "data": [
    {
      "id": 1,
      "amount": 50000,
      "type": "expense",
      "created_at": "2026-05-07T10:30:00Z"
    }
  ],
  "meta": {
    "total": 100,
    "per_page": 15,
    "current_page": 1,
    "last_page": 7,
    "from": 1,
    "to": 15
  }
}
```

### ✅ Success Response - POST Create

```json
{
  "success": true,
  "message": "User registered successfully",
  "data": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "token": "1|abcdefghijklmnopqrstuvwxyz",
    "token_type": "Bearer"
  },
  "meta": {
    "created_at": "2026-05-07T10:30:00Z"
  }
}
```

### ✅ Success Response - GET Single

```json
{
  "success": true,
  "message": "Transaction retrieved successfully",
  "data": {
    "id": 1,
    "wallet_id": 1,
    "category_id": 1,
    "amount": 50000,
    "type": "expense",
    "note": "Lunch",
    "created_at": "2026-05-07T10:30:00Z"
  },
  "meta": {}
}
```

### ❌ Error Response - Validation Error

```json
{
  "success": false,
  "message": "Validation failed",
  "data": null,
  "meta": {
    "errors": {
      "email": ["Email sudah terdaftar"],
      "password": ["Password minimal 8 karakter"]
    }
  }
}
```

### ❌ Error Response - Unauthorized

```json
{
  "success": false,
  "message": "Unauthorized",
  "data": null,
  "meta": null
}
```

### ❌ Error Response - Not Found

```json
{
  "success": false,
  "message": "Resource not found",
  "data": null,
  "meta": null
}
```

### ❌ Error Response - Login Failed

```json
{
  "success": false,
  "message": "The provided credentials are incorrect",
  "data": null,
  "meta": null
}
```

---

## HTTP Status Codes

| Status Code | Penggunaan |
| ------------- | ----------- |
| `200` | Success - GET, PUT, PATCH |
| `201` | Created - POST (resource created) |
| `400` | Bad Request - Invalid input |
| `401` | Unauthorized - Missing/invalid token |
| `404` | Not Found - Resource doesn't exist |
| `422` | Validation Failed - Input validation error |
| `500` | Server Error |

---

## Penggunaan di Controller

### Success Response

```php
// Simple success
ApiResponse::success($data, 'Data retrieved successfully');

// With pagination meta
ApiResponse::success(
    $data,
    'Transactions fetched successfully',
    [
        'total' => $total,
        'per_page' => $perPage,
        'current_page' => $currentPage,
        'last_page' => $lastPage,
    ]
);

// Created
ApiResponse::created($user, 'User registered successfully');

// Paginated
ApiResponse::paginated($data, 'Transactions fetched', $paginationMeta);
```

### Error Response

```php
// Simple error
ApiResponse::error('Something went wrong', null, null, 400);

// Validation error
ApiResponse::validationError($errors, 'Validation failed');

// Unauthorized
ApiResponse::unauthorized('Invalid token');

// Not found
ApiResponse::notFound('Transaction not found');
```

---

## Meta Field - Standard Keys

Beberapa meta key yang umum digunakan:

### Pagination Meta

```json
{
  "meta": {
    "total": 100,
    "per_page": 15,
    "current_page": 1,
    "last_page": 7,
    "from": 1,
    "to": 15
  }
}
```

### Filter Meta

```json
{
  "meta": {
    "filter": {
      "type": "income",
      "date_from": "2026-05-01",
      "date_to": "2026-05-31"
    },
    "total_filtered": 50
  }
}
```

### Summary Meta

```json
{
  "meta": {
    "summary": {
      "total_income": 5000000,
      "total_expense": 500000,
      "balance": 4500000
    }
  }
}
```

### Timestamp Meta

```json
{
  "meta": {
    "timestamp": "2026-05-07T10:30:00Z",
    "query_time_ms": 45
  }
}
```

---

## Best Practices

✅ **DO:**

- Selalu sertakan `message` yang deskriptif
- Gunakan `meta` untuk pagination, filter, summary
- Consistent response format untuk semua endpoint
- Gunakan HTTP status code yang tepat
- Sertakan `timestamp` di meta jika diperlukan

❌ **DON'T:**

- Jangan gunakan `payload` (gunakan `meta` instead)
- Jangan forget status code yang sesuai
- Jangan mixing format response antar endpoint
- Jangan include data sensitive di response
