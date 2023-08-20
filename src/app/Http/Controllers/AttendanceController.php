<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;


class AttendanceController extends Controller
{
    public function index()
    {
        return view('index');
    }
    public function confirm()
    {
        return view('date');
    }

}
