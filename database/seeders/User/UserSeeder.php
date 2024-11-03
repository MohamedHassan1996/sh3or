<?php

namespace Database\Seeders\User;

use App\Enums\User\UserRole;
use App\Enums\User\UserStatus;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        //$usersCount = max((int) $this->command->ask('How many users would you like?', 10),1);

        $user = User::create([
            'name' => 'Mohamed Khaled',
            'email'=> 'customer@gmail.com',
            'status' => UserStatus::ACTIVE->value,
            'phone' => '+201018557045',
            'password' => 'M@Ns123456',
            'role' => UserRole::CUSTOMER->value,
            'avatar' => null
        ]);

        $user = User::create([
            'name' => 'vendor',
            'email'=> 'vendor@gmail.com',
            'status' => UserStatus::ACTIVE->value,
            'phone' => '+201018558045',
            'password' => 'M@Ns123456',
            'role' => UserRole::VENDOR->value,
            'avatar' => null
        ]);


        //$role = Role::findByName('مدير');

        //$user->assignRole($role);

    }
}
