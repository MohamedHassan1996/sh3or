<?php

namespace Database\Seeders;

use App\Enums\City\CityStatus;
use App\Enums\Party\CategoryStatus;
use App\Models\City\City;
use App\Models\Party\PartyCategory;
use App\Models\Party\PreparationTime;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;


class PrepTimeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        //$usersCount = max((int) $this->command->ask('How many users would you like?', 10),1);

        $preparationTime1 = PreparationTime::create([
            'start_at' => '06:00:00',
            'end_at' => '08:00:00'
        ]);
        $preparationTime2 = PreparationTime::create([
            'start_at' => '09:00:00',
            'end_at' => '11:00:00'
        ]);



        //$role = Role::findByName('مدير');

        //$user->assignRole($role);

    }
}
