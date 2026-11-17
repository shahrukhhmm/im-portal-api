<?php

namespace App\Http\Controllers;

use App\Http\Resources\RoleResource;
use App\Models\Application;
use App\Models\EntityRole;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    /**
     * @OA\Get(
     *     path="/role",
     *     security={{"bearerAuth":{}}},
     *     operationId="roleList",
     *     tags={"Role"},
     *     @OA\Response(response="200", description="Get Role list"),
     * )
     */
    public function index()
    {
        $data = DB::table('roles')
            ->select(['id', 'name'])
            ->orderBy('id')
            ->get();
        return response()->json($data, 200);
    }

    /**
     * @OA\Post(
     *     path="/role",
     *     security={{"bearerAuth":{}}},
     *     operationId="AddRole",
     *     tags={"Role"},
     *   @OA\Response(response="201",
     *     description="Role Create",
     *     @OA\JsonContent(ref="#/components/schemas/Role")
     *   ),
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(ref="#/components/schemas/Role")
     *   )
     * )
     */
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $role = new Role();
        $role->name = $request->name;
        $role->save();
        return response()->json($role, 200);
    }

    /**
     * @OA\Get(
     *     path="/role/{id}",
     *     security={{"bearerAuth":{}}},
     *     operationId="FindRole",
     *     tags={"Role"},
     *     @OA\Response(response="200",
     *       description="Find a user",
     *       @OA\JsonContent(
     *         @OA\Property(
     *             property="data",
     *             type="object",
     *             ref="#/components/schemas/RoleResource"
     *         )
     *       )
     *     ),
     *      @OA\Parameter(
     *          name="id",
     *          description="Role id",
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
        $role = Role::find($id);
        if (is_null($role)) {
            return response()->json(['message' => 'Role not found!'], 404);
        } else {
            return new RoleResource($role);
        }
    }

    /**
     * @OA\Put(
     *     path="/role/{id}",
     *     security={{"bearerAuth":{}}},
     *     operationId="updateRole",
     *     tags={"Role"},
     *     @OA\Response(response="200",
     *       description="Update a Role.",
     *       @OA\JsonContent(ref="#/components/schemas/Role")
     *     ),
     *     @OA\Parameter(
     *       name="id",
     *       description="Role ID",
     *       in="path",
     *       required=true,
     *       @OA\Schema(
     *          type="integer"
     *       )
     *     ),
     *     @OA\RequestBody(
     *       required=true,
     *       @OA\JsonContent(ref="#/components/schemas/Role")
     *     )
     * )
     */
    public function update(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        $role = Role::find($id);
        if (is_null($role)) {
            return response()->json(['message' => 'Role not found!'], 404);
        } else {
            $role->name = $request->name;
            $role->save();
        }
        return response()->json($role, 200);
    }

    /**
     * @OA\Delete(path="/role/{id}",
     *   security={{"bearerAuth":{}}},
     *   tags={"Role"},
     *   operationId="deleteRole",
     *   @OA\Response(response="204",
     *     description="Delete Role",
     *     @OA\JsonContent()
     *   ),
     *   @OA\Parameter(
     *     name="id",
     *     description="Role ID",
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
        $role = Role::find($id);
        if (is_null($role)) {
            return response()->json(['message' => 'Role not found!'], 404);
        } else {
            $role->delete();
            return response()->json(['message' => 'Role deleted successfully'], 200);
        }
    }

    /**
     * @OA\Put(
     *   path="/role/setRoleAccess",
     *   security={{"bearerAuth":{}}},
     *   tags={"Role"},
     *   operationId="setRoleAccess",
     *   @OA\Response(response="202",
     *     description="Set Role Access",
     *     @OA\JsonContent(ref="#/components/schemas/RoleAccess")
     *   ),
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(ref="#/components/schemas/RoleAccess")
     *   )
     * )
     */
    public function setRoleAccess(Request $request)
    {
        $appId = $request->app_id;
        $roleId = $request->role_id;
        $app = Application::find($appId);
        $role = Role::find($roleId);
        if (is_null($app) || is_null($role))
            return response()->json(['message' => 'Application or Role not found!'], 404);
        else {
            $objects = $request->entities;
            if ($objects != null) {
                //delete old role access
                DB::table('entity_role')
                    ->where([
                        ['application_id', '=', $appId],
                        ['role_id', '=', $roleId]
                    ])->delete();
                //set new role access
                foreach ($objects as $object) {
                    $entityId = $object['entity_id'];
                    $actionIds = $object['action_ids'];
                    if (!is_null($actionIds)) {
                        foreach ($actionIds as $actionId) {
                            $er = new EntityRole();
                            $er->application_id = $appId;
                            $er->role_id = $roleId;
                            $er->entity_id = $entityId;
                            $er->action_id = $actionId;
                            $er->save();
                        }
                    }
                }
            }
            return response()->json(['message' => 'Saved changes!'], 200);
        }
    }

    /**
     * @OA\Get(
     *   path="/role/getRoleAccess/{appId}/{roleId}",
     *   security={{"bearerAuth":{}}},
     *   tags={"Role"},
     *   operationId="getRoleAccess",
     *   @OA\Response(response="202",
     *     description="Get Role Access",
     *     @OA\JsonContent(
     *       @OA\Property(
     *           property="data",
     *           type="object",
     *           ref="#/components/schemas/RoleAccess"
     *       )
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="appId",
     *     description="Application ID",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *        type="integer"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="roleId",
     *     description="Role ID",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *        type="integer"
     *     )
     *   )
     * )
     */
    public function getRoleAccess($appId, $roleId)
    {
        $app = Application::find($appId);
        $role = Role::find($roleId);
        if (is_null($app) || is_null($role))
            return response()->json(['message' => 'Application or Role not found!'], 404);
        else {
            $roles = EntityRole::query()->distinct()
                ->select('entity_id')
                ->where([
                    ['application_id', '=', $appId],
                    ['role_id', '=', $roleId]
                ])->get();

            $result = [];
            foreach ($roles as $role) {
                $entityId = $role->entity_id;
                $actionIds = EntityRole::query()
                    ->select('action_id')
                    ->where([
                        ['application_id', '=', $appId],
                        ['role_id', '=', $roleId],
                        ['entity_id', '=', $entityId]
                    ])->get();
                $ids = [];
                foreach ($actionIds as $actionId) {
                    $ids[] = $actionId->action_id;
                }
                $obj = [
                    'entity_id'    => $entityId,
                    'action_ids'   => $ids
                ];
                $result[] = $obj;
            }
            $data = [
                'data' => [
                    'app_id'   => (int) $appId,
                    'role_id'  => (int) $roleId,
                    'entities' => $result
                ]
            ];
            return response()->json($data, 200);
        }
    }
}
