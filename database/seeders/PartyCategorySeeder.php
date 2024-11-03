<?php

namespace Database\Seeders;

use App\Enums\Facility\FStatus;
use App\Enums\Party\CategoryStatus;
use App\Enums\Party\PartyCancelStatus;
use App\Models\Facility\Facility;
use App\Models\Party\PartyCategory;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;


class PartyCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        //$usersCount = max((int) $this->command->ask('How many users would you like?', 10),1);

        $cat1 = PartyCategory::create([
            'name' => 'حفل زفاف',
            'status' => CategoryStatus::ACTIVE->value,
            'path' => null
        ]);

        $cat2 =PartyCategory::create([
            'name' => 'خطوبة',
            'status' => CategoryStatus::ACTIVE->value,
            'path' => null
        ]);



        //$role = Role::findByName('مدير');

        //$user->assignRole($role);

    }
}
