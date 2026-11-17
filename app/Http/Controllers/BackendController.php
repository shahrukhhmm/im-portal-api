<?php

namespace App\Http\Controllers;

use App\Models\Backend;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use ParagonIE\ConstantTime\Base64UrlSafe;

class BackendController extends Controller
{
//
//    public function generateToken(Request $request)
//    {
//        $backendName = $request->name;
//        $backend = Backend::query()->where('title', $backendName)->first();
//        if (is_null($backend))
//            return response()->json(['message' => 'Backend not found!'], 404);
//        else {
//            $date = Carbon::now();
//            $date->addDays(2);
//            Backend::query()->where('id', $backend->id)
//                ->update(['token' => Base64UrlSafe::encode(Str::random(128)), 'expires_at' => $date->toDateTimeString()]);
//            return response()->json(['message' => 'Backend updated'], 200);
//        }
//    }
//
//    public function verifyToken(Request $request)
//    {
//        $backendToken = $request->token;
//        $backend = Backend::query()
//            ->where('token', $backendToken)
//          //  ->where('expires_at', '>=', Carbon::now())
//            ->first();
//        if (is_null($backend))
//            return response()->json(['message' => 'Invalid'], 404);
//        else
//            return response()->json(['message' => 'Valid'], 200);
//    }
//
//    public function refreshToken(Request $request)
//    {
//        $backendName = $request->token;
//        $backend = Backend::query()->where('title', $backendName)->first();
//        if (is_null($backend))
//            return response()->json(['message' => 'Backend not found!'], 404);
//        else {
//            $date = Carbon::now();
//            $date->addDays(2);
//            Backend::query()->where('id', $backend->id)
//                ->update(['token' => Base64UrlSafe::encode(Str::random(128)), 'expires_at' => $date->toDateTimeString()]);
//            return response()->json(['message' => 'Backend updated'], 200);
//        }
//    }

}
