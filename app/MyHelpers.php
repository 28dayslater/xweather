<?php

namespace App;

use Illuminate\Support\Carbon;

/**
 * Various helper routines
 */
class MyHelpers
{
    /**
     * parse date using YYYYMMDD or YYYY-MM-DD format.
     * @return Carbon (the parsed date) or null if data not in the above format
     */
    public static function parseDate(string $value): ?Carbon
    {
        try {
            return Carbon::createFromFormat('Ymd', $value);
        }
        catch (\Throwable $e) {
            try {
                return Carbon::createFromFormat('Y-m-d', $value);
            }
            catch (\Throwable $e) {

            }
        }
        return null;
    }
}
