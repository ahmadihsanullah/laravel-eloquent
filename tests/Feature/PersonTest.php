<?php

namespace Tests\Feature;

use App\Models\Person;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PersonTest extends TestCase
{
    public function testPerson()
    {
        $person = new Person();
        $person->first_name = 'Ahmad';
        $person->last_name = 'Ihsan';
        $person->save();

        self::assertEquals('AHMAD Ihsan', $person->full_name);

        $person->full_name = "Joko Moro";

        self::assertEquals("JOKO", $person->first_name);
        self::assertEquals("Moro", $person->last_name);
    }
}
