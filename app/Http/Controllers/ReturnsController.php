<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Returns;
use Illuminate\Http\Request;

class ReturnsController extends Controller
{
    public function requestReturn(Request $request)
    {
        $return = new Returns();
    }

    public function respondReturn(Request $request)
    {

    }

    public function processReturn(Request $request)
    {

    }
}
