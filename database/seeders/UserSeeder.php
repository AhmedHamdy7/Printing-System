<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@printing.com',
            'password' => Hash::make('password'),
        ]);
        $admin->assignRole('admin');

        $employee = User::create([
            'name' => 'Employee User',
            'email' => 'employee@printing.com',
            'password' => Hash::make('password'),
        ]);
        $employee->assignRole('employee');
    }
}
