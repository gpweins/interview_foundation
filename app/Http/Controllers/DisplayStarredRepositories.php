<?php

namespace App\Http\Controllers;

use App\Services\GitHubService;
use Exception;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

class DisplayStarredRepositories extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, GitHubService $github)
    {
        $token = $request->user()->github_token;

        try {
            $token = Crypt::decrypt($token);
            $repositories = $github->getStarredRepositories($token);
        } catch (DecryptException $e) {
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());

            return response()->json(['error' => 'Invalid credentials'], 401);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());

            return response()->json(['error' => 'Failed to return starred repositories'], 500);
        }

        return response()->json(['data' => $repositories], 200);
    }
}
