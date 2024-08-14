<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\MetroStation;
use Illuminate\Support\Facades\Http;

class UpdateData extends Command
{
    protected $signature = 'update:data';
    protected $description = 'Update data from API';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
{
    $response = Http::get('https://api.hh.ru/metro/1');

    $lines = $response->json()['lines'];

    foreach ($lines as $line) {
        $lineName = $line['name'];
        $hexColor = $line['hex_color'];

        foreach ($line['stations'] as $station) {
            $stationName = $station['name'];

            $existingStation = MetroStation::where('name_station', $stationName)->first();

            if ($existingStation) {
                $existingStation->update([
                    'line' => $lineName,
                    'hex_color' => $hexColor,
                ]);
            } else {
                MetroStation::create([
                    'name_station' => $stationName,
                    'line' => $lineName,
                    'hex_color' => $hexColor,
                ]);
            }
        }
    }
}
}