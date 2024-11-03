<?php

namespace Database\Seeders;

use App\Enums\City\CityStatus;
use App\Enums\Party\CategoryStatus;
use App\Models\City\City;
use App\Models\Party\PartyCategory;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;


class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        //$usersCount = max((int) $this->command->ask('How many users would you like?', 10),1);

        $city1 = City::create([
            'name' => 'جدة',
            'status' => CityStatus::ACTIVE->value,
            'path' => null
        ]);

        $city2 =PartyCategory::create([
            'name' => 'المدينة',
            'status' => CityStatus::ACTIVE->value,
            'path' => null
        ]);



        //$role = Role::findByName('مدير');

        //$user->assignRole($role);

    }
}
