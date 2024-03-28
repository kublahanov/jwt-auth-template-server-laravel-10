<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\DB;

/**
 * TestController.
 */
class TestController extends ApiController
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function migrations()
    {
        return DB::table('_migrations')->get();
    }
}
