<?php

namespace App\Http\Controllers;

use App\Http\Resources\BrandUserResource;
use App\Http\Resources\UserResource;
use App\Models\ApplicationUser;
use App\Models\Brand;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Str;

class UserController extends Controller
{
    private const PERSONAL_TOKEN_STRING = 'Portal-User';

    /**
     * @OA\Get(path="/user",
     *   security={{"bearerAuth":{}}},
     *   tags={"User"},
     *   operationId="UserList",
     *   @OA\Response(response="200",
     *     description="User Collection",
     *     @OA\JsonContent(
     *       @OA\Property(
     *           property="data",
     *           type="array",
     *           @OA\Items(ref="#/components/schemas/UserResource")
     *       ),
     *     )
     *   )
     * )
     */
    public function index()
    {
        $list = DB::table('users')
            ->select(['id', 'name', 'first_name', 'last_name', 'email', 'level'])
            ->orderBy('id')
            ->get();
        return UserResource::collection($list);
    }

    /**
     * @OA\Post(
     *   path="/user/register",
     *   security={{"bearerAuth":{}}},
     *   tags={"User"},
     *   operationId="AddUser",
     *   @OA\Response(response="200",
     *     description="Register",
     *     @OA\JsonContent(ref="#/components/schemas/UserResource")
     *   ),
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(ref="#/components/schemas/User")
     *   )
     * )
     */
    public function register(Request $request)
    {
        $userLevel = strtolower($request->level);
        if ($userLevel == "admin") {
            return response()->json(['message' => 'Admin level already exist!'], 403);
        } else {
            $user = new User();
            $user->name = $request->first_name . ' ' . $request->last_name;
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->level = $request->level;
            $success['access_token'] = $user->createToken(self::PERSONAL_TOKEN_STRING)->accessToken;
            $user->save();
            return new UserResource($user);
        }
    }

    /**
     * @OA\Get(path="/user/{id}",
     *   security={{"bearerAuth":{}}},
     *   tags={"User"},
     *   operationId="getUser",
     *   @OA\Response(response="200",
     *     description="Find a user",
     *     @OA\JsonContent(
     *       @OA\Property(
     *           property="data",
     *           type="object",
     *           ref="#/components/schemas/UserResource"
     *       )
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="id",
     *     description="User ID",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *        type="integer"
     *     )
     *   )
     * )
     */
    public function show($id)
    {
        $user = User::find($id);
        if (is_null($user)) {
            return response()->json(['message' => 'User not found!'], 404);
        } else {

            $data = [
                'user_id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'full_name' => $user->name,
                'email' => $user->email,
                'level' => $user->level
            ];

            $result = [
                'data' => $data
            ];

            return response()->json($result, 200);
        }
    }

    /**
     * @OA\Put(
     *   path="/user/{id}",
     *   security={{"bearerAuth":{}}},
     *   tags={"User"},
     *   operationId="UpdateUser",
     *   @OA\Response(response="202",
     *     description="User Update",
     *     @OA\JsonContent(ref="#/components/schemas/UserResource"),
     *   ),
     *   @OA\Parameter(
     *     name="id",
     *     description="User ID",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *        type="integer"
     *     )
     *   ),
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(ref="#/components/schemas/User")
     *   )
     * )
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if (is_null($user)) {
            return response()->json(['message' => 'User not found!'], 404);
        } else {
            $user->name = $request->first_name . ' ' . $request->last_name;
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->email = $request->email;
            $password = $request->password;
            if ($password != "")
                $user->password = bcrypt($request->password);
            $user->level = $request->level;
            $user->save();
        }
        return response()->json($user, 200);
    }

    /**
     * @OA\Delete(path="/user/{id}",
     *   security={{"bearerAuth":{}}},
     *   tags={"User"},
     *   operationId="DeleteUser",
     *   @OA\Response(response="204",
     *     description="User Delete",
     *     @OA\JsonContent()
     *   ),
     *   @OA\Parameter(
     *     name="id",
     *     description="User ID",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *        type="integer"
     *     )
     *   ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Resource Not Found"
     *      )
     * )
     */
    public function destroy($id)
    {
        $user = User::find($id);
        if (is_null($user)) {
            return response()->json(['message' => 'User not found!'], 404);
        } else {
            if ($user->level != 'Admin') {
                $count = ApplicationUser::where('user_id', $id)->count();
                if ($count == 0) {
                    $user->delete();
                    return response()->json(['message' => 'User deleted successfully!'], 200);
                } else {
                    return response()->json(['message' => 'This User is used!'], 403);
                }
            } else {
                return response()->json(['message' => 'Not Permit!'], 403);
            }
        }
    }

    /**
     * @OA\Put(
     *   path="/user/setUserApplication/{userId}",
     *   security={{"bearerAuth":{}}},
     *   tags={"User"},
     *   operationId="setUserApplication",
     *   @OA\Response(response="202",
     *     description="Set User Application",
     *     @OA\JsonContent(
     *            @OA\Property(property="application_id", type="integer"),
     *            @OA\Property(property="brand_id", type="integer"),
     *            @OA\Property(property="role_id", type="integer"),
     *            @OA\Property(property="access", type="integer", description="1: attach, 0: detach")
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="userId",
     *     description="User ID",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *        type="integer"
     *     )
     *   ),
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *            @OA\Property(property="app_id", type="integer"),
     *            @OA\Property(property="brand_id", type="integer"),
     *            @OA\Property(property="role_id", type="integer"),
     *            @OA\Property(property="access", type="integer", description="1: attach, 0: detach")
     *     )
     *   )
     * )
     */
    public function setUserApplication(Request $request, $userId)
    {
        $user = User::find($userId);
        if (is_null($user))
            return response()->json(['message' => 'User not found!'], 404);

        $access = $request->access;
        if ($access == 1) {
            $au = new ApplicationUser();
            $au->user_id = $userId;
            $au->application_id = $request->app_id;
            $au->brand_id = $request->brand_id;
            $au->role_id = $request->role_id;
            $au->save();
        } else {
            DB::table('application_user')
                ->where([
                    ['application_id', '=', $request->app_id],
                    ['user_id', '=', $userId],
                    ['brand_id', '=', $request->brand_id],
                    ['role_id', '=', $request->role_id]
                ])->delete();
        }

        return response()->json(['message' => 'Saved changes!'], 200);
    }

    /**
     * @OA\Put(
     *   path="/user/setUserApplications/{userId}",
     *   security={{"bearerAuth":{}}},
     *   tags={"User"},
     *   operationId="SetUserAccess",
     *   @OA\Response(response="202",
     *     description="Set User Access",
     *     @OA\JsonContent(ref="#/components/schemas/UserAccess")
     *   ),
     *   @OA\Parameter(
     *     name="userId",
     *     description="User ID",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *        type="integer"
     *     )
     *   ),
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(ref="#/components/schemas/UserAccess")
     *   )
     * )
     */
    public function setUserApplications(Request $request, $userId)
    {
        $user = User::find($userId);
        if (is_null($user))
            return response()->json(['message' => 'User not found!'], 404);
        else {
            $objects = $request->applications;
            if ($objects != null) {
                //delete old user access
                DB::table('application_user')
                    ->where('user_id', $userId)
                    ->delete();
                //set new user access
                foreach ($objects as $object) {
                    $appUser = DB::table('application_user')
                        ->where([
                            ['application_id', '=', $object['app_id']],
                            ['user_id', '=', $userId]
                        ])->first();
                    if (is_null($appUser)) {
                        $publicKey = Str::orderedUuid()->getHex();
                    } else {
                        $publicKey = $appUser->public_key;
                    }
                    $au = new ApplicationUser();
                    $au->user_id = $userId;
                    $au->application_id = $object['app_id'];
                    $au->brand_id = $object['brand_id'];
                    $au->role_id = $object['role_id'];
                    $au->public_key = $publicKey;
                    $au->save();
                }
            }
        }
        return response()->json(['message' => 'Saved changes!'], 200);
    }

    /**
     * @OA\Get(path="/user/getUserApplications/{userId}",
     *   security={{"bearerAuth":{}}},
     *   tags={"User"},
     *   operationId="getUserApplications",
     *   @OA\Response(response="200",
     *     description="get user application list",
     *     @OA\JsonContent(
     *       @OA\Property(
     *           property="data",
     *           type="object",
     *           ref="#/components/schemas/UserAccess"
     *       )
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="userId",
     *     description="User ID",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *        type="integer"
     *     )
     *   )
     * )
     */
    public function getUserApplications($userId)
    {
        $user = User::find($userId);
        if (is_null($user)) {
            return response()->json(['message' => 'User not found!'], 404);
        } else {
            $apps = DB::table('application_user')
                ->select('application_id', 'brand_id', 'role_id')
                ->where('user_id', $userId)->get();

            $applications = [];
            foreach ($apps as $app) {
                $application = [
                    'app_id'    => $app->application_id,
                    'brand_id'  => $app->brand_id,
                    'role_id'   => $app->role_id,
                ];
                array_push($applications, $application);
            }
            $data = [
                'applications' => $applications
            ];
            return response()->json($data, 200);
        }
    }

    /**
     * @OA\Get(path="/user/getBrandUsers/{brandId}",
     *   security={{"bearerAuth":{}}},
     *   tags={"User"},
     *   operationId="getBrandUsers",
     *   @OA\Response(response="200",
     *     description="get brand user list",
     *     @OA\JsonContent(
     *       @OA\Property(
     *           property="data",
     *           type="object",
     *           ref="#/components/schemas/UserResource"
     *       )
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="brandId",
     *     description="Brand ID",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *        type="integer"
     *     )
     *   )
     * )
     */
    public function getBrandUsers(Brand $brand)
    {
        $users = User::whereHas('applicationUsers' , fn($q) => $q->where('brand_id' , $brand->id ) )->get();
        return UserResource::collection($users);
    }

}
