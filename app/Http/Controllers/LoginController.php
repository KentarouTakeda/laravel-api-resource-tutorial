<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function destroy()
    {
        Auth::logout();

        return response()->noContent();
    }
}
