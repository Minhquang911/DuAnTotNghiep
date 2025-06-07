<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lấy ID của các vai trò
        $adminRoleId = Role::where('name', 'admin')->first()->id;
        $userRoleId = Role::where('name', 'user')->first()->id;
        
        // Tạo tài khoản admin
        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin@gmail.com'),
            'role_id' => $adminRoleId,
            'gender' => 'male',
            'birthday' => '1990-01-01',
            'phone' => '0909090909',
            'avatar' => null,
        ]);
        
        // Tạo tài khoản user
        User::create([
            'name' => 'User',
            'email' => 'user@gmail.com',
            'password' => Hash::make('user@gmail.com'),
            'role_id' => $userRoleId,
            'gender' => 'male',
            'birthday' => '1990-01-01',
            'phone' => '0909090908',
            'avatar' => null,
        ]);

        // Tạo thêm nhiều user random
        for ($i = 1; $i <= 8; $i++) {
            $gender = rand(0, 1) ? 'male' : 'female';
            $name = fake()->name($gender);
            $email = "user{$i}@gmail.com";
            $phone = '09' . rand(10000000, 99999999);

            User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make('12345678'),
                'role_id' => $userRoleId,
                'gender' => $gender,
                'birthday' => fake()->date('Y-m-d', '2005-12-31'),
                'phone' => $phone,
                'avatar' => null,
            ]);
        }
    }
} 