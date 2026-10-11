<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(BaseSeeder::class);

        // Data demo: lokal/testing, atau produksi dengan APP_DEMO=true.
        // Hanya bila belum ada pengguna, supaya aman dijalankan ulang (DemoSeeder tidak idempoten).
        $demo = app()->environment('local', 'testing') || config('app.demo');

        if ($demo && User::count() === 0) {
            $this->call(DemoSeeder::class);
        }
    }
}
