<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;
use App\Models\Location;
use App\Models\Temperature;
use Illuminate\Support\Carbon;


class WeatherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cities = [];
        $handle = fopen("tests/cities.txt", "r");

        while (($data = fgetcsv($handle)) !== FALSE) {
            $attrs = [
                'city' => trim($data[0]),
                'state' => trim($data[1]),
                'lat' => floatval(trim($data[2])),
                'lon' => floatval(trim($data[3]))
            ];
            $cities[] = $attrs;
        }

        $dates = ['2020-05-01', '2020-05-02', '2020-05-03', '2020-05-04', '2020-05-05', '2020-05-06',];

        foreach ($cities as $city) {
            foreach ($dates as $dt) {
                $loc = new Location($city);
                $loc->date = $dt;
                $loc->save();
                $temps = array_fill(0, 24, 20.5);
                for ($idx = 0; $idx < 24; ++$idx) {
                    $temp = new Temperature(['hour' => $idx, 'value' => 20.5]);
                    $loc->temps()->save($temp);
                }
            }
        }
    }
}
