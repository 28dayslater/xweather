<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use DB;
use App\Models\Location;
use App\Models\Temperature;
use Illuminate\Support\Carbon;

use App\MyHelpers;


class APITest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setup();

        $this->cities = [];
        $handle = fopen("tests/cities.txt", "r");
        $this->assertNotEquals($handle, FALSE);
        while (($data = fgetcsv($handle)) !== FALSE) {
            $attrs = [
                'city' => trim($data[0]),
                'state' => trim($data[1]),
                'lat' => floatval(trim($data[2])),
                'lon' => floatval(trim($data[3]))
            ];
            $this->cities[] = $attrs;
        }
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testGetAll()
    {
        $loc = new Location($this->cities[0]);
        $loc->date = '2020-10-20';
        $loc->save();
        $temps = array_fill(0, 24, 20.5);
        for ($idx=0; $idx<24; ++$idx) {
            $temp = new Temperature(['hour' => $idx, 'value' => 20.5]);
            $loc->temps()->save($temp);
        }

        $response = $this->get('/api/weather');

        $response->assertStatus(200)
                 ->assertJsonCount(1);
    }

    public function testGetLatLong()
    {
        foreach (['2020-10-19', '2020-10-20', '2020-10-22', '2020-10-23'] as $dt) {
            $loc = new Location($this->cities[0]);
            $loc->date = $dt;
            $loc->save();
            $temps = array_fill(0, 24, 20.5);
            for ($idx=0; $idx<24; ++$idx) {
                $temp = new Temperature(['hour' => $idx, 'value' => 20.5]);
                $loc->temps()->save($temp);
            }
        }
        foreach (['2020-10-19', '2020-10-20', '2020-10-22'] as $dt) {
            $loc = new Location($this->cities[1]);
            $loc->date = $dt;
            $loc->save();
            $temps = array_fill(0, 24, 20.5);
            for ($idx=0; $idx<24; ++$idx) {
                $temp = new Temperature(['hour' => $idx, 'value' => 20.5]);
                $loc->temps()->save($temp);
            }
        }

        $response = $this->get('/api/weather?lat=33.333&lon=11.1111');
        $response->assertStatus(404);

        $response = $this->get('/api/weather?lat=31.4428&lon=-100.4503');
        $response->assertStatus(200)
                 ->assertJsonCount(4);
    }

    public function testEraseAll()
    {
        $loc = new Location($this->cities[0]);
        $loc->date = '2020-10-20';
        $loc->save();
        $temps = array_fill(0, 24, 20.5);
        for ($idx=0; $idx<24; ++$idx) {
            $temp = new Temperature(['hour' => $idx, 'value' => 20.5]);
            $loc->temps()->save($temp);
        }

        $response = $this->delete('/api/erase');
        $response->assertStatus(200);

        $response1 = $this->get('/api/weather');
        $response->assertStatus(200)
                 ->assertJson([]);
    }

    public function testEraseByRange()
    {
        Location::query()->delete();

        foreach (['2020-10-19', '2020-10-20', '2020-10-22', '2020-10-23'] as $dt) {
            $loc = new Location($this->cities[0]);
            $loc->date = $dt;
            $loc->save();
            $temps = array_fill(0, 24, 20.5);
            for ($idx=0; $idx<24; ++$idx) {
                $temp = new Temperature(['hour' => $idx, 'value' => 20.5]);
                $loc->temps()->save($temp);
            }
        }

        $loc = new Location($this->cities[1]);
        $loc->date = '2020-10-21';
        $loc->save();
        $temps = array_fill(0, 24, 20.5);
        for ($idx=0; $idx<24; ++$idx) {
            $temp = new Temperature(['hour' => $idx, 'value' => 20.5]);
            $loc->temps()->save($temp);
        }

        // $request = $this->delete('/api/erase?start=20201020&end=20201022&lat=31.4428&lon=-100.4503');
        $request = $this->delete('/api/erase?start=20201020&end=20201022&lat=31.4428&lon=-100.4503');
        $request->assertStatus(200);
        $count = Location::count();
        $this->assertEquals($count, 3);
    }

}
