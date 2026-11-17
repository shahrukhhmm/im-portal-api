<?php

namespace App\Http\Controllers;

use App\Http\Resources\ActionResource;
use Illuminate\Support\Facades\DB;

class ActionController extends Controller
{
    /**
     * @OA\Get(
     *   path="/action",
     *   security={{"bearerAuth":{}}},
     *   operationId="actionList",
     *   tags={"Action"},
     *   @OA\Response(response="200",
     *     description="Action Collection",
     *     @OA\JsonContent(
     *       @OA\Property(
     *           property="data",
     *           type="array",
     *           @OA\Items(ref="#/components/schemas/ActionResource")
     *       ),
     *     )
     *   )
     * )
     */
    public function index()
    {
        $actions = DB::table('actions')
            ->select(['id', 'name'])
            ->orderBy('id')
            ->get();
        return ActionResource::collection($actions);
    }
}
