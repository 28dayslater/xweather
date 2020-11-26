<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

use App\MyHelpers;

class MyHelpersTest extends TestCase
{
    /**
     * Test parseDate
     *
     * @return void
     */
    public function testParseDate()
    {
        // $this->assertEquals(MyHelpers::parseDate('xyz215'), null);
        $this->assertNotNull(MyHelpers::parseDate('19700113'));
        $this->assertNotNull(MyHelpers::parseDate('2020-06-15'));
    }
}
