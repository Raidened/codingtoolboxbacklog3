<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CommonLifeController extends Controller
{
    public function index() {
        return view('pages.commonLife.index');
    }
}
