# Aplikasi Catatan Keuangan

Project Laravel versi terbaru untuk aplikasi pencatatan keuangan sederhana. Clean code, best practice Laravel, dan struktur yang rapi.

---

## 🎯 Project Overview

* Mencatat income, expense, dan transfer antar wallet
* Setiap transaksi memiliki kategori, icon, dan warna
* Wallet memiliki balance (saldo tersimpan)
* Setiap perubahan saldo manual HARUS tercatat sebagai transaksi (audit trail)
* Data siap untuk laporan keuangan sederhana

---

## Quick Setup

### 1. Install Dependencies

```bash
composer install
npm i
npm run build
```

### 2. Configure Environment

```bash
# Copy environment template
cp .env.example .env

# Generate application key
php artisan key:generate
```

Edit the `.env` file and configure:

``` sh
DB_DATABASE=aplikasiku
DB_USERNAME=root
DB_PASSWORD=          # Leave empty if using Laragon
APP_URL=http://localhost
```

### 3. Create Database

```bash
# Create MySQL database first
mysql -u root -e "CREATE DATABASE aplikasiku"

# Then run migrations
php artisan migrate
```

### 4. Seed Initial Data

This will create:

* 1 test user (email: `test@example.com`, password: `password`)
* 10 wallet types (Cash, Bank, Card, E-Wallet, etc.)
* wallets (Uang Belanja, BNI, BRI, ShopeePay)
* 13 categories (9 expense + 4 income)

```bash
php artisan db:seed
```

### 5. Start Development Server

```bash
php artisan serve
```

The API will be available at: <http://localhost:8000>

---

## 🧱 Migration + Model

### 1. users

Gunakan default Laravel authentication.

---

### 2. wallet_types

* id
* user_id (foreignId → constrained → cascadeOnDelete)
* name (string) → contoh: Cash, Bank, E-Wallet, Lainnya
* timestamps

---

### 3. wallets

* id
* user_id (foreignId → constrained → cascadeOnDelete)
* wallet_type_id (foreignId → constrained → cascadeOnDelete)
* name (string)
* balance (decimal 12,2 default 0) ← SALDO DISIMPAN
* timestamps

unique(user_id, name)

---

### 4. categories

* id
* user_id (foreignId → constrained → cascadeOnDelete)
* name (string)
* description (text nullable)
* type (enum: 'income', 'expense')
* icon (string nullable)
* color (string nullable)
* timestamps

unique(user_id, name, type)

---

### 5. transactions

* id
* user_id (foreignId → constrained → cascadeOnDelete)
* wallet_id (foreignId → constrained → cascadeOnDelete)
* to_wallet_id (foreignId nullable → constrained ke wallets.id → nullOnDelete)
* category_id (foreignId nullable → constrained → nullOnDelete)
* type (enum: 'income', 'expense', 'transfer', 'adjustment')
* amount (decimal 12,2)
* balance_after (decimal 12,2)
* note (text nullable)
* transaction_date (date)
* timestamps

index(user_id)
index(wallet_id)
index(category_id)
index(type)
index(transaction_date)

---

## 🔗 Relasi Eloquent

### User

* hasMany(WalletType::class)
* hasMany(Wallet::class)
* hasMany(Category::class)
* hasMany(Transaction::class)

### WalletType

* belongsTo(User::class)
* hasMany(Wallet::class)

### Wallet

* belongsTo(User::class)
* belongsTo(WalletType::class)
* hasMany(Transaction::class)

### Category

* belongsTo(User::class)
* hasMany(Transaction::class)

### Transaction

* belongsTo(User::class)
* belongsTo(Wallet::class)
* belongsTo(Category::class)
* belongsTo(Wallet::class, 'to_wallet_id')

---

## ⚙️ BUSINESS LOGIC

### 🔥 1. Update Balance Otomatis

Setiap transaksi:

* income → tambah balance wallet
* expense → kurangi balance wallet
* transfer:

  * kurangi wallet asal
  * tambah wallet tujuan
* adjustment → tergantung selisih (lihat bawah)

Gunakan DB transaction (atomic) untuk semua operasi.

---

### 🔥 2. Manual Update Balance (FITUR PENTING)

Jika user mengubah balance wallet secara manual:

1. Hitung selisih:

   * difference = new_balance - current_balance

2. Buat transaksi otomatis:

   * type = 'adjustment'
   * amount = ABS(difference)
   * note = "Difference"

3. Jika:

   * difference > 0 → dianggap income
   * difference < 0 → dianggap expense

4. Update wallet balance sesuai nilai baru

---

### 🔥 3. Konsistensi Data

* Semua perubahan balance HARUS melalui transaksi
* Tidak boleh ada update balance tanpa transaksi
* Gunakan service layer (contoh: WalletService, TransactionService)

---

## ⚙️ Aturan Teknis

* Gunakan:

  * foreignId()
  * constrained()
  * cascadeOnDelete()
  * nullOnDelete()
* Gunakan enum Laravel
* Gunakan fillable atau guarded pada model
* Gunakan eager loading
* Gunakan Response Standart misal
  
  ``` json
  {
    "success": true,
    "data": {},
    "payload": {},
    "message": "Transaction created"
  }
  ```

* Gunakan Laravel API Resource
* Gunakan Laravel Form Request (Jangan validasi di controller)

---

## 🌱 Seeder / Factory

Buat data awal:

* 1 user
* wallet_types:

  * Cash
  * Account
  * Card
  * Debit Card
  * Savings
  * E-Wallet
  * Investments
  * Loan
  * Inssurance
  * Others

* wallets:

  * Uang Belanja (Cash, balance = 0)
  * BNI (Card, balance = 0)
  * BRI (Card, balance = 0)
  * ShopeePay (E-Wallet, balance = 0)

* categories:

  * Makanan (expense, icon: utensils, color: #FF8C42)
  * Transport (expense, icon: car, color: #3498DB)
  * Hewan Peliharaan (expense, icon: paw, color: #F39C12)
  * Gaya Hidup (expense, icon: mug-hot, color: #9B59B6)
  * Pakaian (expense, icon: shirt, color: #E91E63)
  * Kecantikan (expense, icon: spa, color: #FF69B4)
  * Kebutuhan Rumah (expense, icon: house, color: #16A085)
  * Belanja Bulanan (expense, icon: cart-shopping, color: #E74C3C)
  * Lainya (expense, icon: circle-plus, color: #95A5A6)

  * Gaji (income, icon: money-bill-wave, color: #2ECC71)
  * Bonus (income, icon: gift, color: #F1C40F)
  * Gift (income, icon: gift, color: #F39C12)
  * Lainnya (income, icon: circle-plus, color: #95A5A6)

## 💡 Tips & Adjustment

### Cara pakai categories.icon di Blade

`<i class="fa-solid fa-{{ $category->icon }}"></i>`

### Tips UX (ini subtle tapi impactful)

Expense → warna hangat (merah/orange) ✅
Income → hijau ✅
Icon beda-beda → otak langsung kenali tanpa baca ✅

### CHECK / VALIDATION RULE (LOGIC PENTING)

Walaupun di DB tidak semua support check constraint, tetap enforce di code

| type       | rule               |
| ---------- | ------------------ |
| income     | category_id wajib  |
| expense    | category_id wajib  |
| transfer   | to_wallet_id wajib |
| adjustment | category_id NULL   |

---

## ❗ Catatan Penting

* Balance di wallet adalah CACHE + STATE, bukan source utama
* Source of truth tetap transactions
* Hindari race condition (gunakan DB transaction)
* Pastikan transfer tidak double count
* Gunakan naming konsisten dan jelas dalam bahasa inggris
* Gunakan Pint untuk standard kerapian kode

---
