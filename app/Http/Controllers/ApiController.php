<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class ApiController extends BaseController
{
    use ValidatesRequests;

    /**
     * @return true
     */
    public function ping()
    {
        return true;
    }
}
