<?php

namespace Tests;

use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        DB::delete('Delete from reviews');
        DB::delete('Delete from products');
        DB::delete('Delete from categories');
        DB::delete('Delete from virtual_accounts');
        DB::delete('Delete from wallets');
        DB::delete('Delete from customers');
    }
}
