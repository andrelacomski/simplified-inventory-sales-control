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
        if (User::where('email', 'admin@cplug.com.br')->first()) return;

        $user = User::create([
            'name' => 'Administrador',
            'email' => 'admin@cplug.com.br',
            'password' => bcrypt('123456'),
        ]);

        $user->save();
    }
}
