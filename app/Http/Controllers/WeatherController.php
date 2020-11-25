<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Location;
use App\Models\Temperature;

class WeatherController extends Controller
{
    /**
     * Return a list of all weather temperature points ordered by id.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $results = [];
        $dbData = Location::orderBy('id')
            ->with('temps')
            ->cursor();
        // TODO: move this to a method as it will be reused
        foreach ($dbData as $location) {
            $item = [
                'id' => $location->id,
                'date' => $location->date,
                'location' => [
                    'lat' => $location->lat,
                    'lon' => $location->lon,
                    'city' => $location->city,
                    'state' => $location->state
                ],
                'temperature' => array_fill(0,24,null)
            ];
            foreach ($location->temps as $temp) {
                if ($temp->hour >= 0 && $temp->hour < 24) {
                    $item['temperature'][$temp->hour] = $temp->value;
                }
            }

            $results[] = $item;
        }
        return response()->json($results);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
