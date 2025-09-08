<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder {
    /**
     * Run the database seeds.
     * Start permissions, group and user
     */
    public function run(): void {
        $user = User::create([
            'name' => 'Administrador',
            'email' => 'admin@cplug.com.br',
            'password' => bcrypt('@cplug#1234!'),
        ]);

        $user->save();
    }
}
