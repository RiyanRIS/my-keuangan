<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletType;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create a test user
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        // Create wallet types
        $walletTypes = [
            'Cash',
            'Account',
            'Card',
            'Debit Card',
            'Savings',
            'E-Wallet',
            'Investments',
            'Loan',
            'Insurance',
            'Others',
        ];

        foreach ($walletTypes as $typeName) {
            WalletType::create([
                'user_id' => $user->id,
                'name' => $typeName,
            ]);
        }

        // Create wallets
        $cashType = $user->walletTypes()->where('name', 'Cash')->first();
        $cardType = $user->walletTypes()->where('name', 'Card')->first();
        $eWalletType = $user->walletTypes()->where('name', 'E-Wallet')->first();

        Wallet::create([
            'user_id' => $user->id,
            'wallet_type_id' => $cashType->id,
            'name' => 'Uang Belanja',
            'balance' => 0,
        ]);

        Wallet::create([
            'user_id' => $user->id,
            'wallet_type_id' => $cardType->id,
            'name' => 'BNI',
            'balance' => 0,
        ]);

        Wallet::create([
            'user_id' => $user->id,
            'wallet_type_id' => $cardType->id,
            'name' => 'BRI',
            'balance' => 0,
        ]);

        Wallet::create([
            'user_id' => $user->id,
            'wallet_type_id' => $eWalletType->id,
            'name' => 'ShopeePay',
            'balance' => 0,
        ]);

        // Create expense categories
        $expenseCategories = [
            [
                'name' => 'Makanan',
                'icon' => 'utensils',
                'color' => '#FF8C42',
                'description' => 'Pengeluaran untuk makanan dan minuman',
            ],
            [
                'name' => 'Transport',
                'icon' => 'car',
                'color' => '#3498DB',
                'description' => 'Pengeluaran untuk transportasi',
            ],
            [
                'name' => 'Hewan Peliharaan',
                'icon' => 'paw',
                'color' => '#F39C12',
                'description' => 'Pengeluaran untuk hewan peliharaan',
            ],
            [
                'name' => 'Gaya Hidup',
                'icon' => 'mug-hot',
                'color' => '#9B59B6',
                'description' => 'Pengeluaran gaya hidup',
            ],
            [
                'name' => 'Pakaian',
                'icon' => 'shirt',
                'color' => '#E91E63',
                'description' => 'Pengeluaran pakaian',
            ],
            [
                'name' => 'Kecantikan',
                'icon' => 'spa',
                'color' => '#FF69B4',
                'description' => 'Pengeluaran kecantikan',
            ],
            [
                'name' => 'Kebutuhan Rumah',
                'icon' => 'house',
                'color' => '#16A085',
                'description' => 'Pengeluaran kebutuhan rumah',
            ],
            [
                'name' => 'Belanja Bulanan',
                'icon' => 'cart-shopping',
                'color' => '#E74C3C',
                'description' => 'Pengeluaran belanja bulanan',
            ],
            [
                'name' => 'Lainnya',
                'icon' => 'circle-plus',
                'color' => '#95A5A6',
                'description' => 'Pengeluaran lainnya',
            ],
        ];

        foreach ($expenseCategories as $cat) {
            Category::create([
                'user_id' => $user->id,
                'name' => $cat['name'],
                'description' => $cat['description'],
                'type' => 'expense',
                'icon' => $cat['icon'],
                'color' => $cat['color'],
            ]);
        }

        // Create income categories
        $incomeCategories = [
            [
                'name' => 'Gaji',
                'icon' => 'money-bill-wave',
                'color' => '#2ECC71',
                'description' => 'Pendapatan gaji',
            ],
            [
                'name' => 'Bonus',
                'icon' => 'gift',
                'color' => '#F1C40F',
                'description' => 'Pendapatan bonus',
            ],
            [
                'name' => 'Gift',
                'icon' => 'gift',
                'color' => '#F39C12',
                'description' => 'Pendapatan hadiah',
            ],
            [
                'name' => 'Lainnya',
                'icon' => 'circle-plus',
                'color' => '#95A5A6',
                'description' => 'Pendapatan lainnya',
            ],
        ];

        foreach ($incomeCategories as $cat) {
            Category::create([
                'user_id' => $user->id,
                'name' => $cat['name'],
                'description' => $cat['description'],
                'type' => 'income',
                'icon' => $cat['icon'],
                'color' => $cat['color'],
            ]);
        }
    }
}
