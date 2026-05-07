# 🎯 API Response - Quick Reference

## Standard Format

```json
{
  "success": true,
  "message": "Description of what happened",
  "data": { /* or [] */ },
  "meta": { /* pagination, filter, summary, etc */ }
}
```

---

## Usage Examples

### GET List dengan Pagination

```php
$transactions = Transaction::where('user_id', $userId)
    ->paginate(15);

return ApiResponse::success(
    $transactions,
    'Transactions fetched successfully',
    PaginationHelper::meta($transactions)
);
```

**Response:**

```json
{
  "success": true,
  "message": "Transactions fetched successfully",
  "data": [ {...}, {...} ],
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

---

### POST Create

```php
$wallet = Wallet::create($validated);

return ApiResponse::created(
    $wallet,
    'Wallet created successfully'
);
```

**Response:**

```json
{
  "success": true,
  "message": "Wallet created successfully",
  "data": { "id": 1, "name": "My Wallet", ... },
  "meta": null
}
```

---

### GET Single

```php
$transaction = Transaction::findOrFail($id);

return ApiResponse::success(
    $transaction,
    'Transaction retrieved successfully'
);
```

**Response:**

```json
{
  "success": true,
  "message": "Transaction retrieved successfully",
  "data": { "id": 1, "amount": 50000, ... },
  "meta": null
}
```

---

### GET dengan Filter & Summary

```php
$transactions = Transaction::where('user_id', $userId)
    ->where('type', 'expense')
    ->get();

$total = $transactions->sum('amount');

return ApiResponse::success(
    $transactions,
    'Expenses retrieved successfully',
    PaginationHelper::summary($total, $transactions->count())
);
```

**Response:**

```json
{
  "success": true,
  "message": "Expenses retrieved successfully",
  "data": [ {...}, {...} ],
  "meta": {
    "total": 500000,
    "count": 5
  }
}
```

---

### Validation Error

```php
return ApiResponse::validationError(
    $validator->errors(),
    'Validation failed'
);
```

**Response:**

```json
{
  "success": false,
  "message": "Validation failed",
  "data": null,
  "meta": {
    "email": ["Email already exists"],
    "password": ["Password must be at least 8 characters"]
  }
}
```

---

### Error Responses

**Unauthorized:**

```php
return ApiResponse::unauthorized('Invalid token');
```

**Not Found:**

```php
return ApiResponse::notFound('Transaction not found');
```

**Generic Error:**

```php
return ApiResponse::error('Something went wrong', null, null, 500);
```

---

## Meta Field Types

### 📄 Pagination

```php
PaginationHelper::meta($paginated)
// Returns: { total, per_page, current_page, last_page, from, to }
```

### 📊 Summary

```php
PaginationHelper::summary($total, $count, $average)
// Returns: { total, count, average }
```

### 🔍 Filter

```php
PaginationHelper::filter(['type' => 'expense', 'date' => '2026-05'], $total)
// Returns: { filter: {...}, total_filtered }
```

### 🔗 Merge Multiple Meta

```php
PaginationHelper::merge(
    PaginationHelper::meta($paginated),
    PaginationHelper::summary($total, $count)
)
```

---

## Status Codes

| Code | Usage |
| ------ | ------- |
| 200 | Success (GET, PUT, PATCH) |
| 201 | Created (POST) |
| 400 | Bad Request |
| 401 | Unauthorized |
| 404 | Not Found |
| 422 | Validation Failed |
| 500 | Server Error |

---

## Tips 💡

✅ Always include meaningful `message`  
✅ Use `meta` for pagination, filter, summary  
✅ Keep response structure consistent  
✅ Use correct HTTP status codes  
✅ Include `timestamp` in meta if tracking is needed  
✅ Use ResourceCollection for transformed data  

❌ Don't use `payload` (it's `meta` now!)  
❌ Don't forget status code  
❌ Don't mix response formats  
❌ Don't leak sensitive data  

---

**Updated:** 2026-05-07  
**Version:** 2.0 (Using `meta` instead of `payload`)
