<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MultipleGuestsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Array of test guest customers
        $guests = [
            [
                'name' => 'Tenzin Dorji',
                'email' => 'tenzin.dorji@guest.com',
                'phone' => '17531270',
                'address' => 'Thimphu, Bhutan',
                'password' => 'Password123',
                'role' => 'guest',
                'status' => 'active',
            ],
            [
                'name' => 'Dawa Thinley',
                'email' => 'dawa.thinley@guest.com',
                'phone' => '17531271',
                'address' => 'Paro, Bhutan',
                'password' => 'Password123',
                'role' => 'guest',
                'status' => 'active',
            ],
            [
                'name' => 'Pemba Yangchen',
                'email' => 'pemba.yangchen@guest.com',
                'phone' => '17531272',
                'address' => 'Punakha, Bhutan',
                'password' => 'Password123',
                'role' => 'guest',
                'status' => 'active',
            ],
            [
                'name' => 'Kelden Wangmo',
                'email' => 'kelden.wangmo@guest.com',
                'phone' => '17531273',
                'address' => 'Bumthang, Bhutan',
                'password' => 'Password123',
                'role' => 'guest',
                'status' => 'active',
            ],
            [
                'name' => 'Sonam Tenzin',
                'email' => 'sonam.tenzin@guest.com',
                'phone' => '17531274',
                'address' => 'Chhukha, Bhutan',
                'password' => 'Password123',
                'role' => 'guest',
                'status' => 'active',
            ],
        ];

        // Create each guest customer
        foreach ($guests as $guest) {
            User::firstOrCreate(
                ['email' => $guest['email']],
                [
                    'name' => $guest['name'],
                    'phone' => $guest['phone'],
                    'address' => $guest['address'],
                    'password' => Hash::make($guest['password']),
                    'role' => $guest['role'],
                    'status' => $guest['status'],
                ]
            );
        }

        $this->command->info('✅ Multiple guest customers created successfully!');
        $this->command->info('--- Test Guest Accounts ---');
        foreach ($guests as $guest) {
            $this->command->line("📧 Email: {$guest['email']} | 🔑 Password: {$guest['password']}");
        }
    }
}
