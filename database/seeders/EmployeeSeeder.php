<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name'      => 'Admin',
            'email'     => 'admin@example.com',
            'password'  => Hash::make('password'),
            'role_id'   => 1,
        ]);

        // DB::table('users')->insert([
        //     'name'      => 'Admin',
        //     'email'     => 'admin@admin.com',
        //     'password'  => Hash::make('password'),
        //     'role_id'   => 1,
        // ]);
    }
}
