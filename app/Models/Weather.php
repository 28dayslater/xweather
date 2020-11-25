<?php

/**
 * Created by Reliese Model.
 * Date: Wed, 03 Jul 2019 21:07:39 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Weather
 * 
 * @property int $id
 * @property \Carbon\Carbon $date
 * @property array $location
 * @property array $temperature
 *
 * @package App\Models
 */
class Weather extends Eloquent
{
	public $timestamps = false;

	protected $dates = [
		'date'
	];

	protected $fillable = [
		'date',
		'location',
		'temperature'
	];
}
