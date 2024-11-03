<?php

namespace Database\Seeders;

use App\Enums\Party\PartyCancelStatus;
use App\Enums\Party\PartyStatus;
use App\Enums\Party\PriceListStatus;
use App\Enums\Party\PriceListType;
use App\Models\Party\Party;
use App\Models\Party\PartyFacility;
use App\Models\Party\PartyPreparationTime;
use App\Models\Party\PartyPriceList;
use App\Models\Party\PreparationTime;
use Illuminate\Database\Seeder;


class PartySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        //$usersCount = max((int) $this->command->ask('How many users would you like?', 10),1);

        $party = Party::create([
            'name' => 'حفل الهنا',
            'description' => 'وصف حغل الهنا',
            'category_id' => 1,
            'city_id' => 1,
            'vendor_id' => 2,
            'status' => PartyStatus::ACTIVE->value,
            'allow_cancel' => PartyCancelStatus::NON_CANCELLABLE->value
        ]);

        $partPrepTime = PartyPreparationTime::create([
            'party_id' => $party->id,
            'preparation_time_id' => 1,
            'status' => 1
        ]);

        $partPrepTime2 = PartyPreparationTime::create([
            'party_id' => $party->id,
            'preparation_time_id' => 1,
            'status' => 1
        ]);

        $partyPriceList = PartyPriceList::create([
            'party_id' => $party->id,
            'pricelist_id' => 1,
            'status' => PriceListStatus::ACTIVE->value,
            'type' => PriceListType::MAIN->value
        ]);

        $partyFacility = PartyFacility::create([
            'party_id' => $party->id,
            'facility_id' => 1,
            'status' => 1
        ]);

        $partyFacility2 = PartyFacility::create([
            'party_id' => $party->id,
            'facility_id' => 2,
            'status' => 1
        ]);


        //$role = Role::findByName('مدير');

        //$user->assignRole($role);

    }
}
