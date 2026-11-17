<?php

namespace App\Http\Controllers;

use App\Http\Requests\ResetRequest;
use App\Jobs\SendEmailJob;
use App\Models\Action;
use App\Models\Application;
use App\Models\ApplicationUser;
use App\Models\Brand;
use App\Models\Entity;
use App\Models\EntityRole;
use App\Models\PortalAccessToken;
use App\Models\Role;
use App\Models\User;
use App\Models\UserAccess;
use App\Models\Utils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Mockery\Exception;

class AuthController extends Controller
{
    private const PERSONAL_TOKEN_STRING = 'Portal User';

    /**
     * @OA\Post(
     *     path="/auth/login",
     *     tags={"Auth"},
     *     operationId="AuthLogin",
     *     @OA\Response(response="200",
     *     description="User login",
     *     @OA\JsonContent(ref="#/components/schemas/LoginResource")
     * ),
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(ref="#/components/schemas/Login")
     *   )
     * )
     */
    public function login(Request $request)
    {
        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();
            $this->user = User::find($user->id);
            $tokenResult = $user->createToken(self::PERSONAL_TOKEN_STRING);
            $token = $tokenResult->token;

            $userId = \auth()->user()->id;
            $apps = UserAccess::where('user_id', $userId)->get();

            $applications = [];
            foreach ($apps as $app) {
                $appId = $app->application_id;
                $appObject = Application::find($appId);
                $application = [
                    'id' => $appId,
                    'name' => $appObject->name,
                ];

                $brandId = $app->brand_id;
                $brandObject = Brand::find($brandId);
                $brand = [
                    'id' => $brandId,
                    'name' => $brandObject->name
                ];

                $roleId = $app->role_id;
                $roleObject = Role::find($roleId);
                $role = [
                    'id' => $roleId,
                    'name' => $roleObject->name
                ];

                $aub = [
                    'application' => $application,
                    'brand'       => $brand,
                    'role'        => $role
                ];
                array_push($applications, $aub);
            }

            $data = [
                'user_id' => $this->user->id,
                'name' => $this->user->name,
                'email' => $this->user->email,
                'level' => $this->user->level,
                'access_token' => $tokenResult->accessToken,
                'token_type' => 'Bearer',
                'expires_at' => $token->expires_at,
                'applications' => $applications
            ];

            return response()->json($data, 200);
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }

    /**
     * @OA\Get(
     *     path="/auth/inquiry/{publicKey}",
     *     tags={"Auth"},
     *     operationId="UserInquiry",
     *     @OA\Response(response="200",
     *     description="User Inquiry",
     *     @OA\JsonContent(ref="#/components/schemas/InquiryResource")
     * ),
     *   @OA\Parameter(
     *     name="publicKey",
     *     description="public Key",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *        type="string"
     *     )
     *   )
     * )
     */
    public function userInquiry($publicKey)
    {
        $appCode = substr($publicKey, 0, 9);
        $publicKey = substr($publicKey, 9);
        $userId = PortalAccessToken::where('public_key', $publicKey)->value('user_id');
        $appId = Application::where('code', $appCode)->value('id');
        if (is_null($userId)) {
            return response()->json(['message' => 'Inquiry Failed!'], 404);
        } else {
            $roleBrands = DB::table('application_user')
                ->where([
                    ['application_id', '=', $appId],
                    ['user_id', '=', $userId]
                ])->get();

            $brandRoles = [];
            foreach ($roleBrands as $roleBrand) {
                $brandId = $roleBrand->brand_id;
                $brandObject = Brand::find($brandId);
                $brand = [
                    'id' => $brandId,
                    'name' => $brandObject->name
                ];

                $roleId = $roleBrand->role_id;
                $roleObject = Role::find($roleId);
                $role = [
                    'id' => $roleId,
                    'name' => $roleObject->name
                ];

                $rb = [
                    'brand' => $brand,
                    'role'  => $role
                ];
                array_push($brandRoles, $rb);
            }

            $app = Application::find($appId);
            $application = [
                'name'       => $app->name,
                'brands'     => $brandRoles
            ];

            $token = PortalAccessToken::where('user_id', $userId)->value('user_token');

            $user = User::find($userId);
            $data = [
                'access'      => true,
                'user_name'   => $user->name,
                'level'       => $user->level,
                'application' => $application,
                'user_token'  => $token
            ];

            //remove public_key
            PortalAccessToken::where('user_id', $userId)->delete();

            return response()->json($data, 200);
        }
    }

    /**
     * @OA\Get(
     *     path="/auth/appInquiry/{publicKey}",
     *     tags={"Auth"},
     *     operationId="appInquiry",
     *     @OA\Response(response="200",
     *     description="App Inquiry",
     *     @OA\JsonContent(ref="#/components/schemas/InquiryResource")
     * ),
     *   @OA\Parameter(
     *     name="publicKey",
     *     description="public Key",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *        type="string"
     *     )
     *   )
     * )
     */
    public function appInquiry($publicKey)
    {
        $appCode = substr($publicKey, 0, 9);
        $publicKey = substr($publicKey, 9);
        $userId = PortalAccessToken::where('public_key', $publicKey)->value('user_id');
        $appId = Application::where('code', $appCode)->value('id');
        if (is_null($userId)) {
            return response()->json(['message' => 'Inquiry Failed!'], 404);
        } else {
            $userBrands = DB::table('application_user')
                ->where([
                    ['application_id', '=', $appId],
                    ['user_id', '=', $userId]
                ])->get();

            $brandList = [];
            foreach ($userBrands as $userBrand) {
                $roleId  = $userBrand->role_id;
                $brandId = $userBrand->brand_id;
                $brandObject = Brand::find($brandId);
                $myBrand = [
                    'id' => $brandId,
                    'name' => $brandObject->name
                ];
                $brandList[] = $myBrand;
            }

            $roleObject = Role::find($roleId);
            $role = [
                'id' => $roleId,
                'name' => $roleObject->name
            ];

            $app = Application::find($appId);
            $application = [
                'id'    => $appId,
                'name'  => $app->name,
            ];

            $entities = EntityRole::query()->distinct()
                ->select('entity_id')
                ->where('role_id', $roleId)->get();

            $permission = [];
            foreach ($entities as $entity) {
                $entityId = $entity->entity_id;
                $actionIds = EntityRole::query()
                    ->select('action_id')
                    ->where('role_id', $roleId)
                    ->where('entity_id', $entityId)->get();
                $actions = [];
                foreach ($actionIds as $actionId) {
                    $action = [
                        'id'    => $actionId->action_id,
                        'name'  => Action::find($actionId->action_id)->name
                    ];
                    $actions[] = $action;
                }
                $obj = [
                    'entity_id'    => $entity->entity_id,
                    'entity_name'  => Entity::find($entity->entity_id)->name,
                    'actions'      => $actions,
                ];
                $permission[] = $obj;
            }

            $token = PortalAccessToken::where('user_id', $userId)->value('user_token');

            $user = User::find($userId);
            $data = [
                'access'      => true,
                'user_name'   => $user->name,
                'level'       => $user->level,
                'application' => $application,
                'role'        => $role,
                'brands'      => $brandList,
                'permission'  => $permission,
                'user_token'  => $token
            ];

            //remove public_key
            PortalAccessToken::where('user_id', $userId)->delete();

            return response()->json($data, 200);
        }
    }

    /**
     * @OA\Get(
     *     path="/auth/getUserInfo",
     *     security={{"bearerAuth":{}}},
     *     tags={"Auth"},
     *     operationId="getUserInfo",
     *     @OA\Response(response="200",
     *     description="Get User Info",
     *     @OA\JsonContent()
     *   )
     * )
     */
    public function getUserInfo()
    {
        if (Auth::check()) {
            $userId = \auth()->user()->id;
            $user = User::find($userId);
            $apps = DB::table('application_user')
                ->select('application_id', 'user_id')
                ->where('user_id', $userId)->distinct()->get();

            $roleId = 0;
            $applications = [];
            foreach ($apps as $app) {
                $appId = $app->application_id;
                $appObject = Application::find($appId);
                $roleBrands = DB::table('application_user')
                    ->where([
                        ['application_id', '=', $appId],
                        ['user_id', '=', $userId]
                    ])->get();

                $brandRoles = [];
                foreach ($roleBrands as $roleBrand) {
                    $brandId = $roleBrand->brand_id;
                    $brandObject = Brand::with('Languages')->find($brandId);
                    $brand = [
                        'id' => $brandId,
                        'name' => $brandObject->name,
                        'logo' => $brandObject->logo,
                        'languages' => $brandObject->Languages,
                    ];

                    $roleId = $roleBrand->role_id;
                    $roleObject = Role::find($roleId);
                    $role = [
                        'id' => $roleId,
                        'name' => $roleObject->name
                    ];

                    $rb = [
                        'brand' => $brand,
                        'role'  => $role
                    ];
                    array_push($brandRoles, $rb);
                }

                $appUrl = env('L5_SWAGGER_CONST_HOST').'/auth/getAppInfo/'.$appId;

                $permission = [];
                $entities = EntityRole::query()->distinct()
                    ->select('entity_id')
                    ->where([
                        ['application_id', '=', $appId],
                        ['role_id', '=', $roleId]
                    ])->get();
                foreach ($entities as $entity) {
                    $entityId = $entity->entity_id;
                    $actionIds = EntityRole::query()
                        ->select('action_id')
                        ->where('role_id', $roleId)
                        ->where('entity_id', $entityId)->get();
                    $actions = [];
                    foreach ($actionIds as $actionId) {
                        $action = Action::find($actionId->action_id)->name;
                        $actions[] = $action;
                    }
                    $obj = [
                        'entity_id'    => $entity->entity_id,
                        'entity_name'  => Entity::find($entity->entity_id)->name,
                        'actions'      => $actions,
                    ];
                    $permission[] = $obj;
                }

                $application = [
                    'app_id'    => $appId,
                    'name'      => $appObject->name,
                    'app_url'   => $appUrl,
                    'image'     => $appObject->image,
                    'brands'    => $brandRoles,
                    'permission'=> $permission
                ];
                array_push($applications, $application);
            }

            $data = [
                'access'      => true,
                'user_id'     => $userId,
                'user_name'   => $user->name,
                'first_name'  => $user->first_name,
                'last_name'   => $user->last_name,
                'level'       => $user->level,
                'applications' => $applications,
            ];

            return response()->json($data, 200);
        } else {
            return response()->json(['message' => 'Valid!'], 200);
        }
    }

    /**
     * @OA\Get(
     *     path="/auth/getAppInfo/{appId}",
     *     security={{"bearerAuth":{}}},
     *     tags={"Auth"},
     *     operationId="getAppInfo",
     *     @OA\Response(response="200",
     *     description="Get App Url",
     *     @OA\JsonContent(ref="#/components/schemas/AppUrlResource")
     * ),
     *   @OA\Parameter(
     *     name="appId",
     *     description="App Id",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *        type="integer"
     *     )
     *   )
     * )
     */
    public function getAppInfo($appId)
    {
        $user_access = ApplicationUser::where('user_id', \auth()->id())
            ->where('application_id', $appId)->first();
        if(!$user_access)
            return response()->json(['message' => 'User does not access!'], 401);

        $app = Application::find($appId);

        //Save Portal Access Token
        $userToken = request()->bearerToken();
        $strUidKey = Str::orderedUuid()->getHex();

        $pat = new PortalAccessToken();
        $pat->user_id = \auth()->id();
        $pat->user_token = $userToken;
        $pat->public_key = $strUidKey;
        $pat->save();

        $publicKey = $app->code . $strUidKey;

        $application = [
            'app_id'     => $appId,
            'name'       => $app->name,
            'app_url'    => $app->app_url,
            'public_key' => $publicKey,
        ];
        return response()->json($application, 200);
    }

    /**
     * @OA\Get(
     *     path="/auth/logout",
     *     security={{"bearerAuth":{}}},
     *     operationId="userLogout",
     *     tags={"Auth"},
     *     @OA\Response(response="200", description="User logout"),
     * )
     */
    public function logout()
    {
        if (Auth::check()) {
            $token = Auth::user()->token();
            $token->revoke();
            return response()->json(['success' =>'Successfully logged out'],200);
        }else{
            return response()->json(['error' =>'Logout error!'], 500);
        }
    }


    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:100',
        ]);
        $userEmail = trim($request->email);
        $user = User::query()->where('email', '=', $userEmail)->first();
        if (is_null($user))
            return response()->json(['message' => 'There is no user with this email!'], 404);
        $token = Utils::randomKey(23);
        try{
            DB::table('password_resets')->insert([
                'email' => $user->email,
                'token' => $token
            ]);
            SendEmailJob::dispatch($user->name, $user->email, $token);
            return response()->json(['message' => 'Reset Password Email sent!'], 200);
        }catch(Exception $exception){
            return response([
                'message' => $exception->getMessage()
            ],400);
        }
    }


    public function resetPassword(ResetRequest $request){
        $token = $request->token;
        $password = Hash::make($request->password);
        $tokencheck = DB::table('password_resets')->where('token', $token)->first();
        if(!$tokencheck){
            return response([
                'message' => 'Permit key invalid'
            ], 401);
        }
        DB::table('users')->where('email', $tokencheck->email)->update(['password'=> $password]);
        DB::table('password_resets')->where('email', $tokencheck->email)->delete();
        return response([
            'message' => 'Password changed successfully'
        ], 200);
    }
}
