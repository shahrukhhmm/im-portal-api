<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Entity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EntityController extends Controller
{
    /**
     * @OA\Get(path="/entity",
     *   security={{"bearerAuth":{}}},
     *   tags={"Entity"},
     *   operationId="entityList",
     *   @OA\Response(response="200",
     *     description="Entity Collection",
     *     @OA\JsonContent(
     *       @OA\Property(
     *           property="data",
     *           type="array",
     *           @OA\Items(ref="#/components/schemas/Entity")
     *       ),
     *     )
     *   )
     * )
     */
    public function index()
    {
        $list = DB::table('entities')
            ->orderBy('id')
            ->get();
        return response()->json($list, 200);
    }

    /**
     * @OA\Get(
     *     path="/entity/getEntities/{appId}",
     *     security={{"bearerAuth":{}}},
     *     operationId="entityListByAppId",
     *     tags={"Entity"},
     *     @OA\Response(response="200",
     *     description="Get Entity list By App Id",
     *     @OA\JsonContent(
     *       @OA\Property(
     *           property="data",
     *           type="object",
     *           ref="#/components/schemas/EntityResource"
     *       )
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="appId",
     *     description="Application Id",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *        type="integer"
     *     )
     *   )
     * )
     */
    public function getEntities($appId)
    {
        $app = Application::find($appId);
        if (is_null($app))
            return response()->json(['message' => 'Application not found!'], 404);
        else {
            $appName = $app->name;
            $entities = $app->entities;
            $result = [
                'app_id' => $appId,
                'app_name' => $appName,
                'entities' => $entities,
            ];

            $data = [
                'data' => $result
            ];

            return response()->json($data, 200);
        }
    }

    /**
     * @OA\Post(
     *     path="/entity",
     *     security={{"bearerAuth":{}}},
     *     operationId="AddEntity",
     *     tags={"Entity"},
     *   @OA\Response(response="201",
     *     description="Entity Create",
     *     @OA\JsonContent(ref="#/components/schemas/Entity")
     *   ),
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(ref="#/components/schemas/Entity")
     *   )
     * )
     */
    public function store(Request $request)
    {
        $entity = new Entity();
        $entity->name = $request->name;
        $entity->application_id = $request->application_id;
        $entity->save();
        return response()->json($entity, 200);
    }

    /**
     * @OA\Get(
     *     path="/entity/{id}",
     *     security={{"bearerAuth":{}}},
     *     operationId="FindEntity",
     *     tags={"Entity"},
     *     @OA\Response(response="200",
     *       description="Find a user",
     *       @OA\JsonContent(
     *         @OA\Property(
     *             property="data",
     *             type="object",
     *             ref="#/components/schemas/Entity"
     *         )
     *       )
     *     ),
     *      @OA\Parameter(
     *          name="id",
     *          description="Entity id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     * )
     */
    public function show($id)
    {
        $entity = Entity::find($id);
        if (is_null($entity)) {
            return response()->json(['message' => 'Entity not found!'], 404);
        } else {
            $data = [
                'data' => $entity
            ];
            return response()->json($data, 200);
        }
    }

    /**
     * @OA\Put(
     *     path="/entity/{id}",
     *     security={{"bearerAuth":{}}},
     *     operationId="updateEntity",
     *     tags={"Entity"},
     *     @OA\Response(response="200",
     *       description="Update a Entity.",
     *       @OA\JsonContent(ref="#/components/schemas/Entity")
     *     ),
     *     @OA\Parameter(
     *       name="id",
     *       description="Entity ID",
     *       in="path",
     *       required=true,
     *       @OA\Schema(
     *          type="integer"
     *       )
     *     ),
     *     @OA\RequestBody(
     *       required=true,
     *       @OA\JsonContent(ref="#/components/schemas/Entity")
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $entity = Entity::find($id);
        if (is_null($entity)) {
            return response()->json(['message' => 'Entity not found!'], 404);
        } else {
            $entity->name = $request->name;
            $entity->application_id = $request->application_id;
            $entity->save();
        }
        return response()->json($entity, 200);
    }

    /**
     * @OA\Delete(path="/entity/{id}",
     *   security={{"bearerAuth":{}}},
     *   tags={"Entity"},
     *   operationId="deleteEntity",
     *   @OA\Response(response="204",
     *     description="Delete Entity",
     *     @OA\JsonContent()
     *   ),
     *   @OA\Parameter(
     *     name="id",
     *     description="Entity ID",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *        type="integer"
     *     )
     *   )
     * )
     */
    public function destroy($id)
    {
        $entity = Entity::find($id);
        if (is_null($entity)) {
            return response()->json(['message' => 'Entity not found!'], 404);
        } else {
            $count =2;
            //$count = Media::where('type_id', '=', $id)->count();
            if ($count == 0) {
                $entity->delete();
                return response()->json(['message' => 'Entity deleted successfully'], 200);
            } else {
                return response()->json(['message' => 'This Entity is used!'], 403);
            }
        }
    }
}
