<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function __invoke(Request $request)
    {
        $token = $request->user()->github_token;

        try {
            $token = Crypt::decrypt($token);
        } catch (DecryptException $e) {
            $token = '';
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());
        }

        return view('home')->with(['token' => $token]);
    }
}
