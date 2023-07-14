<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\UserRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ManagerSeeder extends Seeder
{
    protected $roles = [
        ['name' => 'Admin'],
        ['name' => 'Manager'],
        ['name' => 'Member']
    ];

    protected $managers = [
        [
            'name' => "Manager One",
            'email' => "manager1@gmail.com",
        ],
        [
            'name' => "Manager Two",
            'email' => "manager2@gmail.com",
        ]
    ];

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        foreach ($this->roles as $role) {
            Role::create($role);
        }

        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('Password')
        ]);
        UserRole::create(['role_id' => 1, 'user_id' => $admin->id]);

        foreach ($this->managers as $manager) {
            $manager['password'] = Hash::make('Password');
            $user = User::create($manager);
            UserRole::create(['role_id' => 2, 'user_id' => $user->id]);
        }
    }
}
