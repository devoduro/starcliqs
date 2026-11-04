<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // First administrator, from ADMIN_* in .env. Re-seeding never resets an existing password.
        $email = env('ADMIN_EMAIL', 'admin@starcliqs.com');
        if (! User::where('email', $email)->exists()) {
            $password = env('ADMIN_PASSWORD');
            if (! $password) {
                $this->command?->error('Set ADMIN_PASSWORD in .env before seeding.');

                return;
            }
            User::create(['name' => env('ADMIN_NAME', 'StarCliqs Admin'), 'email' => $email, 'password' => Hash::make($password)]);
        }

        $this->call(ContentSeeder::class);
    }
}
