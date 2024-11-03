<?php

namespace Database\Seeders;

use App\Models\Party\PreparationTime;
use App\Models\Party\PriceList;
use Illuminate\Database\Seeder;


class PriceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        //$usersCount = max((int) $this->command->ask('How many users would you like?', 10),1);

        $preparationTime1 = PriceList::create([
            'name' => 'سعر اساسى',
            'start_at' => null,
            'end_at' => null,
            'price' => 300,
            'vendor_id' => 2,
        ]);


        //$role = Role::findByName('مدير');

        //$user->assignRole($role);

    }
}
