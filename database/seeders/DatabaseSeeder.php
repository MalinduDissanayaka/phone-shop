<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $adminRole = Role::updateOrCreate(
            ['name' => 'Admin'],
            ['is_admin' => true, 'permissions' => null]
        );

        $headOffice = Branch::firstOrCreate(
            ['name' => 'Head Office'],
            ['address' => null, 'phone' => null]
        );

        User::updateOrCreate(
            ['email' => 'adminphone@gmail.com'],
            [
                'name' => 'Admin Phone',
                'password' => 'password',
                'role_id' => $adminRole->id,
                'branch_id' => $headOffice->id,
            ]
        );
    }
}
