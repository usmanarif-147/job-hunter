<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function test()
    {
        $count = session()->increment('refresh_count');
        return $count;
    }
}
