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
     * Test GET all weather points
     */
    public function testGetAll(): void
    {
        Location::query()->delete();

        foreach (['2020-10-19', '2020-10-20', '2020-10-22', '2020-10-23'] as $dt) {
            $loc = new Location($this->cities[0]);
            $loc->date = $dt;
            $loc->save();
            $temps = array_fill(0, 24, 20.5);
            for ($idx = 0; $idx < 24; ++$idx) {
                $temp = new Temperature(['hour' => $idx, 'value' => 20.5]);
                $loc->temps()->save($temp);
            }
        }
        foreach (['2020-11-01', '2020-11-02', '2020-11-03', '2020-11-04', '2020-11-05'] as $dt) {
            $loc = new Location($this->cities[1]);
            $loc->date = $dt;
            $loc->save();
            $temps = array_fill(0, 24, 20.5);
            for ($idx = 0; $idx < 24; ++$idx) {
                $temp = new Temperature(['hour' => $idx, 'value' => 20.5]);
                $loc->temps()->save($temp);
            }
        }

        $response = $this->get('/api/weather');

        $response->assertStatus(200)
            ->assertJsonCount(9);
    }

    /**
     * Test GET with start or end (date)
     */
    public function testDateRange(): void
    {
        Location::query()->delete();
        foreach (['2020-10-19', '2020-10-20', '2020-10-22', '2020-10-23'] as $dt) {
            $loc = new Location($this->cities[0]);
            $loc->date = $dt;
            $loc->save();
            $temps = array_fill(0, 24, 20.5);
            for ($idx = 0; $idx < 24; ++$idx) {
                $temp = new Temperature(['hour' => $idx, 'value' => 20.5]);
                $loc->temps()->save($temp);
            }
        }

        $response = $this->get('/api/weather?start=20221020');
        $response->assertStatus(404);

        $response = $this->get('/api/weather?start=20201020');
        $response->assertJsonCount(3);

        $response = $this->get('/api/weather?end=1971-11-22');
        $response->assertStatus(404);

        $response = $this->get('/api/weather?end=2020-10-22');
        $response->assertJsonCount(3);


        $response = $this->get('/api/weather?start=2020-10-20&end=2022-01-01');
        $response->assertJsonCount(3);
    }

    /**
     * Test GET with lat + lon (both required)
     */
    public function testGetLatLong(): void
    {
        Location::query()->delete();
        foreach (['2020-10-19', '2020-10-20', '2020-10-22', '2020-10-23'] as $dt) {
            $loc = new Location($this->cities[0]);
            $loc->date = $dt;
            $loc->save();
            $temps = array_fill(0, 24, 20.5);
            for ($idx = 0; $idx < 24; ++$idx) {
                $temp = new Temperature(['hour' => $idx, 'value' => 20.5]);
                $loc->temps()->save($temp);
            }
        }
        foreach (['2020-10-19', '2020-10-20', '2020-10-22'] as $dt) {
            $loc = new Location($this->cities[1]);
            $loc->date = $dt;
            $loc->save();
            $temps = array_fill(0, 24, 20.5);
            for ($idx = 0; $idx < 24; ++$idx) {
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

    /**
     * Test GET with date range and lat + lon
     */
    public function testGetLatLongAndDateRange(): void
    {
        Location::query()->delete();
        foreach (['2020-10-19', '2020-10-20', '2020-10-22', '2020-10-23'] as $dt) {
            $loc = new Location($this->cities[0]);
            $loc->date = $dt;
            $loc->save();
            $temps = array_fill(0, 24, 20.5);
            for ($idx = 0; $idx < 24; ++$idx) {
                $temp = new Temperature(['hour' => $idx, 'value' => 20.5]);
                $loc->temps()->save($temp);
            }
        }
        foreach (['2020-10-19', '2020-10-20', '2020-10-22'] as $dt) {
            $loc = new Location($this->cities[1]);
            $loc->date = $dt;
            $loc->save();
            $temps = array_fill(0, 24, 20.5);
            for ($idx = 0; $idx < 24; ++$idx) {
                $temp = new Temperature(['hour' => $idx, 'value' => 20.5]);
                $loc->temps()->save($temp);
            }
        }

        $response = $this->get('/api/weather?lat=31.4428&lon=-100.4503&start=20201020&end=20101020');
        $response->assertJsonCount(1);
    }

    /**
     * Test order by latest date
     */
    public function testGetLatest()
    {
        Location::query()->delete();
        foreach (['2020-10-19', '2020-10-20', '2020-12-02', '2020-10-23'] as $dt) {
            $loc = new Location($this->cities[0]);
            $loc->date = $dt;
            $loc->save();
            $temps = array_fill(0, 24, 20.5);
            for ($idx = 0; $idx < 24; ++$idx) {
                $temp = new Temperature(['hour' => $idx, 'value' => 20.5]);
                $loc->temps()->save($temp);
            }
        }

        $response = $this->get('/api/weather?latest=y');
        $this->assertEquals($response->original[0]['date'], '2020-12-02');
    }


    /**
     * Test erase all weather points
     */
    public function testEraseAll(): void
    {
        $loc = new Location($this->cities[0]);
        $loc->date = '2020-10-20';
        $loc->save();
        $temps = array_fill(0, 24, 20.5);
        for ($idx = 0; $idx < 24; ++$idx) {
            $temp = new Temperature(['hour' => $idx, 'value' => 20.5]);
            $loc->temps()->save($temp);
        }

        $response = $this->delete('/api/erase');
        $response->assertStatus(200);

        $response1 = $this->get('/api/weather');
        $response->assertStatus(200)
            ->assertJson([]);
    }

    /**
     * Test erase specific date range and lat+lon
     */
    public function testEraseByRange(): void
    {
        Location::query()->delete();
        foreach (['2020-10-19', '2020-10-20', '2020-10-22', '2020-10-23'] as $dt) {
            $loc = new Location($this->cities[0]);
            $loc->date = $dt;
            $loc->save();
            $temps = array_fill(0, 24, 20.5);
            for ($idx = 0; $idx < 24; ++$idx) {
                $temp = new Temperature(['hour' => $idx, 'value' => 20.5]);
                $loc->temps()->save($temp);
            }
        }
        $loc = new Location($this->cities[1]);
        $loc->date = '2020-10-21';
        $loc->save();
        $temps = array_fill(0, 24, 20.5);
        for ($idx = 0; $idx < 24; ++$idx) {
            $temp = new Temperature(['hour' => $idx, 'value' => 20.5]);
            $loc->temps()->save($temp);
        }

        $request = $this->delete('/api/erase?start=20201020&end=20201022&lat=31.4428&lon=-100.4503');
        $request->assertStatus(200);
        $count = Location::count();
        $this->assertEquals($count, 3);
    }

    /**
     * Test Add new weather point (POST)
     */
    public function testAddNew(): void
    {
        Location::query()->delete();
        $weatherPoint = [
            'date' => '2020-10-01',
            'location' => [
                'city' => 'test',
                'state' => 'FL',
                'lat' => 20.0,
                'lon' => 25.5
            ],
            'temperature' => array_fill(0, 24, 25.0)
        ];

        $response = $this->post('/api/weather', $weatherPoint);
        $response->assertStatus(201); // Success

        $response1 = $this->get('/api/weather');
        $response1->assertJsonCount(1);
        $this->assertEquals(24, count($response1->original[0]['temperature']));
        $this->assertEquals('25.0', $response1->original[0]['temperature'][5]);

        // Refuse to save "POSTed" data with an id that already exists
        $id = $response1->original[0]['id'];
        $weatherPoint['id'] = $id;
        $weatherPoint['location']['city'] = 'Fubar';
        $response2 = $this->post('/api/weather', $weatherPoint);

        $response2->assertStatus(400);
    }

    /**
     * Test update
     */
    public function testUpdate(): void
    {
        DB::table('locations')->delete();

        $weatherPoint = [
            'date' => '2020-10-01',
            'location' => [
                'city' => 'test',
                'state' => 'FL',
                'lat' => 20.0,
                'lon' => 25.5
            ],
            'temperature' => array_fill(0, 24, 25.0)
        ];
        $response = $this->post('/api/weather', $weatherPoint);

        $id = DB::table('locations')->max('id');
        $weatherPoint['id'] = $id;
        $weatherPoint['location']['city'] =  'fubar';
        $weatherPoint['location']['state'] = 'baz';
        $weatherPoint['temperature'][10] = 101.9;
        $response = $this->patch('/api/weather', $weatherPoint);
        $response->assertStatus(201);

        $response = $this->get('/api/weather');
        $response->assertJsonCount(1);
        $this->assertEquals($response->original[0]['location']['city'], 'fubar');
        $this->assertEquals($response->original[0]['location']['state'], 'baz');
        $this->assertEquals($response->original[0]['temperature'][10], 101.9);

    }
}
