<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use DB;
use App\Models\Location;
use App\Models\Temperature;


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

    public function testErase()
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
        print('>>>>>> '. var_export($response1, true));
        $response->assertStatus(200)
                 ->assertJson([]);
    }
}
