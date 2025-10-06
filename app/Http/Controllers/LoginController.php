<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function destroy()
    {
        Auth::logout();

        return response()->noContent();
    }

    public function store(StoreLoginRequest $request)
    {
        $credentials = $request->safe()
            ->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return response()->json([
                'data' => Auth::user(),
            ]);
        }

        return response(status: 422);
    }
}
