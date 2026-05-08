# Report API Documentation

## Overview
Report API menyediakan endpoint untuk mengambil data transaksi yang sudah dikelompokkan berdasarkan periode waktu (hari, minggu, atau bulan) dengan ringkasan pendapatan dan pengeluaran.

---

## Endpoint

### GET `/api/report/transactions`
Mengambil transaksi yang sudah dikelompokkan berdasarkan parameter yang ditentukan.

**Authentication Required:** Yes (Bearer Token)

#### Query Parameters

| Parameter | Type | Required | Values | Default | Deskripsi |
|-----------|------|----------|--------|---------|-----------|
| `group` | string | No | day, week, month | day | Cara pengelompokan data transaksi |
| `start_date` | string | No | Y-m-d format | - | Tanggal awal range (inclusive) |
| `end_date` | string | No | Y-m-d format | - | Tanggal akhir range (inclusive) |

#### Example Requests

**Grouped by Day:**
```
GET /api/report/transactions?group=day
```

**Grouped by Week:**
```
GET /api/report/transactions?group=week
```

**Grouped by Month:**
```
GET /api/report/transactions?group=month
```

**With Date Range:**
```
GET /api/report/transactions?group=day&start_date=2026-05-01&end_date=2026-05-10
```

---

## Response Structure

### Success Response (200 OK)

```json
{
  "success": true,
  "message": "Transaction report retrieved successfully",
  "data": {
    "summary": {
      "income": 10000000,
      "expense": 1000000,
      "balance": 9000000
    },
    "data": [
      {
        "date": "2026-05-08",
        "day": "08",
        "weekday": "Thu",
        "month_year": "May 2026",
        "total_income": 10000000,
        "total_expense": 1000000,
        "total_transaction": 2,
        "items": [
          {
            "id": 1,
            "type": "expense",
            "category": "Makanan dan minuman",
            "note": "Lunch",
            "wallet": "Cash Riyan",
            "amount": 1000000
          },
          {
            "id": 2,
            "type": "income",
            "category": "Gaji Bulanan",
            "note": "Monthly salary",
            "wallet": "Cash Riyan",
            "amount": 10000000
          }
        ]
      }
    ]
  },
  "meta": {}
}
```

### Summary Fields

| Field | Type | Deskripsi |
|-------|------|-----------|
| `income` | number | Total pendapatan pada periode yang dipilih |
| `expense` | number | Total pengeluaran pada periode yang dipilih |
| `balance` | number | Saldo bersih (income - expense) |

### Response Fields - Group by Day

Setiap item dalam array `data`:

| Field | Type | Deskripsi |
|-------|------|-----------|
| `date` | string | Tanggal dalam format Y-m-d |
| `day` | string | Tanggal dalam format dd (01-31) |
| `weekday` | string | Nama hari (Mon, Tue, Wed, dll) |
| `month_year` | string | Format bulan dan tahun (e.g., "May 2026") |
| `total_income` | number | Total pendapatan hari tersebut |
| `total_expense` | number | Total pengeluaran hari tersebut |
| `total_transaction` | number | Jumlah transaksi hari tersebut |
| `items` | array | List transaksi detail |

### Response Fields - Group by Week

Setiap item dalam array `data`:

| Field | Type | Deskripsi |
|-------|------|-----------|
| `week` | string | Nomor minggu (e.g., "2026-W19") |
| `start_date` | string | Tanggal awal minggu (Y-m-d) |
| `end_date` | string | Tanggal akhir minggu (Y-m-d) |
| `month_year` | string | Format bulan dan tahun |
| `total_income` | number | Total pendapatan minggu tersebut |
| `total_expense` | number | Total pengeluaran minggu tersebut |
| `total_transaction` | number | Jumlah transaksi minggu tersebut |
| `items` | array | List transaksi detail |

### Response Fields - Group by Month

Setiap item dalam array `data`:

| Field | Type | Deskripsi |
|-------|------|-----------|
| `month` | string | Bulan dalam format Y-m (e.g., "2026-05") |
| `month_year` | string | Format bulan dan tahun (e.g., "May 2026") |
| `start_date` | string | Tanggal awal bulan (Y-m-d) |
| `end_date` | string | Tanggal akhir bulan (Y-m-d) |
| `total_income` | number | Total pendapatan bulan tersebut |
| `total_expense` | number | Total pengeluaran bulan tersebut |
| `total_transaction` | number | Jumlah transaksi bulan tersebut |
| `items` | array | List transaksi detail |

### Transaction Item Fields

| Field | Type | Deskripsi |
|-------|------|-----------|
| `id` | number | ID transaksi |
| `type` | string | Tipe transaksi (income, expense, transfer, adjustment) |
| `category` | string | Nama kategori |
| `note` | string | Catatan transaksi |
| `wallet` | string | Nama dompet |
| `amount` | number | Jumlah transaksi |

---

## Error Responses

### Invalid Group Parameter (422)
```json
{
  "success": false,
  "message": "Invalid group parameter. Must be: day, week, or month",
  "data": null,
  "meta": {}
}
```

### Invalid Date Format (422)
```json
{
  "success": false,
  "message": "Invalid start_date format. Use Y-m-d",
  "data": null,
  "meta": {}
}
```

### Unauthorized (401)
```json
{
  "success": false,
  "message": "Unauthorized",
  "data": null,
  "meta": {}
}
```

---

## Usage Examples

### 1. Get Daily Report for Current Month
```bash
curl -X GET "http://localhost/api/report/transactions?group=day" \
  -H "Authorization: Bearer YOUR_TOKEN"
```

### 2. Get Weekly Report
```bash
curl -X GET "http://localhost/api/report/transactions?group=week" \
  -H "Authorization: Bearer YOUR_TOKEN"
```

### 3. Get Monthly Report for Year 2026
```bash
curl -X GET "http://localhost/api/report/transactions?group=month&start_date=2026-01-01&end_date=2026-12-31" \
  -H "Authorization: Bearer YOUR_TOKEN"
```

### 4. Get Daily Report for Specific Date Range
```bash
curl -X GET "http://localhost/api/report/transactions?group=day&start_date=2026-05-01&end_date=2026-05-31" \
  -H "Authorization: Bearer YOUR_TOKEN"
```

---

## Endpoint: Category Breakdown

### GET `/api/report/category-breakdown`
Mengambil breakdown kategori transaksi untuk chart/grafik berdasarkan tipe dan periode.

**Authentication Required:** Yes (Bearer Token)

#### Query Parameters

| Parameter | Type | Required | Values | Default | Deskripsi |
|-----------|------|----------|--------|---------|-----------|
| `type` | string | No | income, expense | expense | Tipe transaksi yang dianalisis |
| `period` | string | No | week, month, year | month | Periode analisis |
| `start_date` | string | No | Y-m-d format | - | Tanggal awal range (override period) |
| `end_date` | string | No | Y-m-d format | - | Tanggal akhir range (override period) |

#### Example Requests

**Expense by Month (Default):**
```
GET /api/report/category-breakdown?type=expense&period=month
```

**Income by Year:**
```
GET /api/report/category-breakdown?type=income&period=year
```

**Expense by Week:**
```
GET /api/report/category-breakdown?type=expense&period=week
```

**With Custom Date Range:**
```
GET /api/report/category-breakdown?type=expense&period=month&start_date=2026-05-01&end_date=2026-05-31
```

#### Success Response (200 OK)

```json
{
  "success": true,
  "message": "Category breakdown report retrieved successfully",
  "data": {
    "filter": {
      "type": "expense",
      "period": "month",
      "label": "May 2026",
      "start_date": "2026-05-01",
      "end_date": "2026-05-31"
    },
    "summary": {
      "total": 15000000,
      "transaction_count": 120
    },
    "categories": [
      {
        "category_id": 3,
        "category": "Belanja",
        "icon": "shopping-bag",
        "color": "#8B5CF6",
        "amount": 7000000,
        "percentage": 46.67,
        "transaction_count": 55
      },
      {
        "category_id": 1,
        "category": "Makanan",
        "icon": "utensils",
        "color": "#3B82F6",
        "amount": 5000000,
        "percentage": 33.33,
        "transaction_count": 45
      },
      {
        "category_id": 2,
        "category": "Transportasi",
        "icon": "car",
        "color": "#EF4444",
        "amount": 3000000,
        "percentage": 20,
        "transaction_count": 20
      }
    ]
  },
  "meta": {}
}
```

#### Response Fields

**Filter Object:**

| Field | Type | Deskripsi |
|-------|------|-----------|
| `type` | string | Tipe transaksi yang dianalisis (income/expense) |
| `period` | string | Periode analisis (week/month/year) |
| `label` | string | Label periode dalam bentuk readable (e.g., "May 2026") |
| `start_date` | string | Tanggal awal analisis (Y-m-d) |
| `end_date` | string | Tanggal akhir analisis (Y-m-d) |

**Summary Object:**

| Field | Type | Deskripsi |
|-------|------|-----------|
| `total` | number | Total jumlah transaksi dalam periode |
| `transaction_count` | number | Jumlah transaksi dalam periode |

**Category Object (dalam array categories):**

| Field | Type | Deskripsi |
|-------|------|-----------|
| `category_id` | number | ID kategori |
| `category` | string | Nama kategori |
| `icon` | string | Icon/emoji kategori |
| `color` | string | Hex color kategori |
| `amount` | number | Total amount kategori |
| `percentage` | number | Persentase dari total (2 decimal places) |
| `transaction_count` | number | Jumlah transaksi kategori |

#### Error Responses

**Invalid Type Parameter (422):**
```json
{
  "success": false,
  "message": "Invalid type parameter. Must be: income or expense",
  "data": null,
  "meta": {}
}
```

**Invalid Period Parameter (422):**
```json
{
  "success": false,
  "message": "Invalid period parameter. Must be: week, month, or year",
  "data": null,
  "meta": {}
}
```

**Invalid Date Format (422):**
```json
{
  "success": false,
  "message": "Invalid start_date format. Use Y-m-d",
  "data": null,
  "meta": {}
}
```

#### Usage Examples

**1. Get expense breakdown untuk bulan ini:**
```bash
curl -X GET "http://localhost/api/report/category-breakdown?type=expense&period=month" \
  -H "Authorization: Bearer YOUR_TOKEN"
```

**2. Get income breakdown untuk tahun ini:**
```bash
curl -X GET "http://localhost/api/report/category-breakdown?type=income&period=year" \
  -H "Authorization: Bearer YOUR_TOKEN"
```

**3. Get expense breakdown untuk minggu ini:**
```bash
curl -X GET "http://localhost/api/report/category-breakdown?type=expense&period=week" \
  -H "Authorization: Bearer YOUR_TOKEN"
```

**4. Get expense breakdown untuk periode custom:**
```bash
curl -X GET "http://localhost/api/report/category-breakdown?type=expense&period=month&start_date=2026-05-01&end_date=2026-05-31" \
  -H "Authorization: Bearer YOUR_TOKEN"
```

---

## Notes

- Transaksi yang ditampilkan hanya milik user yang sedang login
- Data diurutkan descending berdasarkan `transaction_date`
- Transaksi transfer tidak menambah `total_income` atau `total_expense` tetap muncul di items
- Jika tidak ada transaksi pada periode yang ditentukan, endpoint akan mengembalikan array kosong
- Format tanggal harus sesuai dengan format Y-m-d (2026-05-08)
- Category breakdown menyertakan icon dan color dari kategori untuk keperluan chart visualization
- Percentage dihitung berdasarkan jumlah amount relatif terhadap total periode
