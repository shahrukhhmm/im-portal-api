<?php

namespace App\Http\Controllers;

use App\Models\Log;
use Illuminate\Http\Request;

class LogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * @OA\Post(
     *   path="/log",
     *   security={{"bearerAuth":{}}},
     *   tags={"Log"},
     *   operationId="CreateLog",
     *   @OA\Response(response="201",
     *     description="Create Log",
     *     @OA\JsonContent(ref="#/components/schemas/Log")
     *   ),
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(ref="#/components/schemas/Log")
     *   )
     * )
     */
    public function store(Request $request)
    {
        $log = new Log();
        $log->app_id = $request->app_id;
        $log->user_id = \auth()->user()->id;
        $log->role_id = $request->role_id;
        $log->brand_id = $request->brand_id;
        $log->ip_address = $request->ip_address;
        $log->user_agent = $request->user_agent;
        $log->route = $request->route;
        $log->method = $request->method_type;
        $log->action = $request->action;
        $log->objects = $request->objects;
    //    $log->login_at = $request->login_at;
        $log->save();
        return response()->json($log, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Log  $log
     * @return \Illuminate\Http\Response
     */
    public function show(Log $log)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Log  $log
     * @return \Illuminate\Http\Response
     */
    public function edit(Log $log)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Log  $log
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Log $log)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Log  $log
     * @return \Illuminate\Http\Response
     */
    public function destroy(Log $log)
    {
        //
    }
}
