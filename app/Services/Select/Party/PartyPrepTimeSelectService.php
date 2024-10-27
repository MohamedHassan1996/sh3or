<?php

namespace App\Services\Select\Party;

use App\Models\Party\PreparationTime;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PartyPrepTimeSelectService
{
    public function getAllPrepTimes()
    {
        return PreparationTime::select([
            'id as value',
            'start_at',
            'end_at'
        ])->get()->map(function ($time) {
            // Format start_at and end_at without seconds using Carbon
            $formattedStartAt = Carbon::parse($time->start_at)->format('H:i'); // 'H:i' -> hours and minutes
            $formattedEndAt = Carbon::parse($time->end_at)->format('H:i');

            // Add label with formatted times
            $time->label = $formattedStartAt . ' - ' . $formattedEndAt;

            // Remove start_at and end_at
            unset($time->start_at, $time->end_at);

            return $time;
        });
    }

    public function getPartyAllPrepTimes($partyId)
    {
        // Fetch the preparation times from the database
        $prepTimes = PreparationTime::select([
                'preparation_times.id as value',
                'preparation_times.start_at',
                'preparation_times.end_at'
            ])
            ->join('party_preparation_times', 'preparation_times.id', '=', 'party_preparation_times.preparation_time_id')
            ->where('party_preparation_times.party_id', $partyId)
            ->where('party_preparation_times.status', 1)
            ->get();

        // Initialize an empty array to store the formatted result
        $result = [];

        // Loop through each preparation time and format the label
        foreach ($prepTimes as $prepTime) {
            // Format start_at and end_at using Carbon to remove seconds
            $formattedStartAt = Carbon::parse($prepTime->start_at)->format('H:i');
            $formattedEndAt = Carbon::parse($prepTime->end_at)->format('H:i');

            // Add the formatted data to the result array
            $result[] = [
                'value' => $prepTime->value,
                'label' => $formattedStartAt . ' - ' . $formattedEndAt
            ];
        }

        // Return the formatted result array
        return $result;
    }




}

