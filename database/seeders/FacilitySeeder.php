<?php

namespace Database\Seeders;

use App\Enums\Facility\FStatus;
use App\Models\Facility\Facility;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;


class FacilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        //$usersCount = max((int) $this->command->ask('How many users would you like?', 10),1);

        $fac1 = Facility::create([
            'name' => 'بالونات',
            'status' => FStatus::ACTIVE->value,
            'path' => null
        ]);

        $fac2 = Facility::create([
            'name' => 'العاب نارية',
            'status' => FStatus::ACTIVE->value,
            'path' => null
        ]);



        //$role = Role::findByName('مدير');

        //$user->assignRole($role);

    }
}
