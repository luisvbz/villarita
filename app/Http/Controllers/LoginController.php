<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\User;
use Carbon\Carbon;

class LoginController extends Controller
{
    public function register(RegisterFormRequest $request)
    {
        User::create([
            'name' => $request->json('name'),
            'username' => $request->json('username'),
            'password' => bcrypt($request->json('password')),
        ]);
    }

    public function signin(Request $request)
    {
        try {
            $token = JWTAuth::attempt($request->only('username', 'password'), [
                'exp' => Carbon::now()->addWeek()->timestamp,
            ]);
        } catch (JWTException $e) {
            return response()->json([
                'error' => 'Could not authenticate',
            ], 500);
        }

        if (!$token) {
            return response()->json([
                'error' => 'Could not authenticate',
            ], 401);
        } else {
            $data = [];
            $meta = [];

            $data['name'] = $request->user()->name;
            $meta['token'] = $token;

            return response()->json([
                'data' => $data,
                'meta' => $meta
            ]);
        }
    }

    public function user(Request $request)
    {
        $data = [];
        $data['name'] = $request->user()->name;
        $data['username'] = $request->user()->username;
        $data['rol'] = $request->user()->role_id;
        $data['casa'] = $request->user()->casa;
        return response()->json([
            'data' => $data,
        ]);
    }}
