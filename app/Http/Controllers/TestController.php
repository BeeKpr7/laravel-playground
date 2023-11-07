<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Enums\ServerStatus;

class TestController extends Controller
{
    public function index()
    {
        dd(
           ServerStatus::cases(), // Collection of all cases (ONLINE, OFFLINE, MAINTENANCE)
           ServerStatus::ONLINE->value, // 1
           ServerStatus::OFFLINE->name, // OFFLINE
           ServerStatus::MAINTENANCE, //Instance of ServerStatus enum
           ServerStatus::from(1), // Instance of ServerStatus enum with value 1 (ONLINE) return error if not found
           ServerStatus::tryFrom(1)?->label(), // Online
           ServerStatus::ONLINE->is(ServerStatus::from(3)), // false
        );
    }
}
