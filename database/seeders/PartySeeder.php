<?php

namespace Database\Seeders;

use App\Enums\Party\PartyCancelStatus;
use App\Enums\Party\PartyStatus;
use App\Enums\Party\PriceListStatus;
use App\Enums\Party\PriceListType;
use App\Enums\Party\Reservation\PayType;
use App\Enums\Party\Reservation\ReservationStatus;
use App\Models\Chat\Chat;
use App\Models\Chat\ChatMessage;
use App\Models\Party\Party;
use App\Models\Party\PartyFacility;
use App\Models\Party\PartyPreparationTime;
use App\Models\Party\PartyPriceList;
use App\Models\Party\PartyReservation;
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
            'description' => 'وصف حفل الهنا',
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

        $partyReservation = PartyReservation::create([
            'party_id' => $party->id,
            'reservation_number' => 'res-586544',
            'customer_id' => 1,
            'vendor_id' => 2,
            'date' => '2025-01-01',
            'start_prep' => '06:00:00',
            'end_prep' => '08:00:00',
            'status' => ReservationStatus::RESERVED->value,
            'pay_type' => PayType::CARD->value,
            'price' => 300,
            'price_after_discount' => 270
        ]);

        $chat = Chat::create([
            'chat_number' => 'ch-2568',
            'customer_id' => 1,
            'vendor_id' => 2,
        ]);

        $chatMessage = ChatMessage::create([
            'chat_id' => $chat->id,
            'sender_id' => 2,
            'message' => 'اهلا بك لقد قمت بحجز الحفلة',
            'read_at' => null
        ]);





        //$role = Role::findByName('مدير');

        //$user->assignRole($role);

    }
}
