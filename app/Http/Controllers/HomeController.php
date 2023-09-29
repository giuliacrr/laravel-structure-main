<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/* use App\Models\Example; */

class HomeController extends Controller
{
    public function index()
    {
        $data = [
            /* "exString" => "string", */
            /* "examples" => Example::all(), */];
        return view('home', $data);
    }
}
