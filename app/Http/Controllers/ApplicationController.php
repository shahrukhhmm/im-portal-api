<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApplicationRequest;
use App\Http\Resources\AppBrandResource;
use App\Http\Resources\ApplicationResource;
use App\Http\Resources\AppSettingResource;
use App\Http\Resources\AppUserResource;
use App\Models\Application;
use App\Models\Brand;
use App\Models\User;
use App\Models\Utils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ApplicationController extends Controller
{
    /**
     * @OA\Get(path="/application",
     *   security={{"bearerAuth":{}}},
     *   tags={"Application"},
     *   operationId="ApplicationList",
     *   @OA\Response(response="200",
     *     description="Application Collection",
     *     @OA\JsonContent(
     *       @OA\Property(
     *           property="data",
     *           type="array",
     *           @OA\Items(ref="#/components/schemas/ApplicationResource")
     *       ),
     *     )
     *   )
     * )
     */
    public function index()
    {
        $list = DB::table('applications')
            ->orderBy('id')
            ->get();
        return ApplicationResource::collection($list);
    }

    /**
     * @OA\Post(
     *   path="/application",
     *   security={{"bearerAuth":{}}},
     *   tags={"Application"},
     *   operationId="AddApplication",
     *   @OA\Response(response="201",
     *     description="Application Create",
     *     @OA\JsonContent(ref="#/components/schemas/Application")
     *   ),
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(ref="#/components/schemas/Application")
     *   )
     * )
     */
    public function store(ApplicationRequest $request)
    {
        $app = new Application();
        $app->name = $request->name;
        $app->image = $request->image;
        $app->code = Str::lower(Utils::randomKey(9));
        $app->save();
        return response()->json($app, 200);
    }

    /**
     * @OA\Get(path="/application/{id}",
     *   security={{"bearerAuth":{}}},
     *   tags={"Application"},
     *   operationId="GetApplication",
     *   @OA\Response(response="200",
     *     description="Find a Application",
     *     @OA\JsonContent(
     *       @OA\Property(
     *           property="data",
     *           type="object",
     *           ref="#/components/schemas/AppSettingResource"
     *       )
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="id",
     *     description="Application ID",
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
        $app = Application::find($id);
        if (is_null($app)) {
            return response()->json(['message' => 'Application not found!'], 404);
        } else {
            return new AppSettingResource($app);
        }
    }

    /**
     * @OA\Put(
     *   path="/application/{id}",
     *   security={{"bearerAuth":{}}},
     *   tags={"Application"},
     *   operationId="UpdateApplication",
     *   @OA\Response(response="202",
     *     description="Application Update",
     *     @OA\JsonContent(ref="#/components/schemas/Application")
     *   ),
     *   @OA\Parameter(
     *     name="id",
     *     description="Application ID",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *        type="integer"
     *     )
     *   ),
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(ref="#/components/schemas/Application")
     *   )
     * )
     */
    public function update(Request $request, $id)
    {
        $app = Application::find($id);
        if (is_null($app)) {
            return response()->json(['message' => 'Application not found!'], 404);
        } else {
            $app->name = $request->name;
            $app->image = $request->image;
            $app->save();
        }
        return response()->json($app, 200);
    }

    /**
     * @OA\Put(
     *   path="/application/setting/{id}",
     *   security={{"bearerAuth":{}}},
     *   tags={"Application"},
     *   operationId="ApplicationSetting",
     *   @OA\Response(response="202",
     *     description="Application Setting",
     *     @OA\JsonContent(ref="#/components/schemas/ApplicationSetting")
     *   ),
     *   @OA\Parameter(
     *     name="id",
     *     description="Application ID",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *        type="integer"
     *     )
     *   ),
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(ref="#/components/schemas/ApplicationSetting")
     *   )
     * )
     */
    public function appSetting(Request $request, $id)
    {
        $app = Application::find($id);
        if (is_null($app)) {
            return response()->json(['message' => 'Application not found!'], 404);
        } else {
            $app->app_url = $request->app_url;
            $app->api_url = $request->api_url;
            $app->ip = $request->ip;
            $app->save();
        }
        return response()->json($app, 200);
    }

    /**
     * @OA\Get(
     *   path="/application/getSetting/{id}",
     *   security={{"bearerAuth":{}}},
     *   tags={"Application"},
     *   operationId="GetApplicationSetting",
     *   @OA\Response(response="200",
     *     description="Get Application Setting",
     *     @OA\JsonContent(
     *       @OA\Property(
     *           property="data",
     *           type="object",
     *           ref="#/components/schemas/AppSettingResource"
     *       )
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="id",
     *     description="Application ID",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *        type="integer"
     *     )
     *   )
     * )
     */
    public function getSetting($id)
    {
        $app = Application::find($id);
        if (is_null($app)) {
            return response()->json(['message' => 'Application not found!'], 404);
        } else {
            return new AppSettingResource($app);
        }
    }

    /**
     * @OA\Delete(path="/application/{id}",
     *   security={{"bearerAuth":{}}},
     *   tags={"Application"},
     *   operationId="DeleteApplication",
     *   @OA\Response(response="204",
     *     description="Application Delete",
     *     @OA\JsonContent()
     *   ),
     *   @OA\Parameter(
     *     name="id",
     *     description="Application ID",
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
        $app = Application::find($id);
        if (is_null($app)) {
            return response()->json(['message' => 'Application not found!'], 404);
        } else {
           // $app->delete();
            return response()->json(['message' => 'Application deleted successfully'], 200);
        }
    }

    /**
     * @OA\Get(path="/application/getApplicationBrands",
     *   security={{"bearerAuth":{}}},
     *   tags={"Application"},
     *   operationId="getApplicationBrands",
     *   @OA\Response(response="200",
     *     description="get Application Brand list",
     *     @OA\JsonContent(
     *       @OA\Property(
     *           property="data",
     *           type="object",
     *           ref="#/components/schemas/AppBrandResource"
     *       )
     *     )
     *   )
     * )
     */
    public function getApplicationBrands()
    {
        $apps = Application::all();
        $applications = [];
        foreach ($apps as $app) {
            $application = [
                'id'    => $app->id,
                'name'  => $app->name,
                'image' => $app->image,
                'brands'=> $app->brands
            ];
            array_push($applications, $application);
        }
        $data = [
            'applications' => $applications
        ];
        return response()->json($data, 200);
    }


//    /**
//     * @OA\Get(path="/application/getApplicationUser/{appId}",
//     *   security={{"bearerAuth":{}}},
//     *   tags={"Application"},
//     *   operationId="getApplicationUser",
//     *   @OA\Response(response="200",
//     *     description="get Application User list",
//     *     @OA\JsonContent(
//     *       @OA\Property(
//     *           property="data",
//     *           type="object",
//     *           ref="#/components/schemas/AppUserResource"
//     *       )
//     *     )
//     *   ),
//     *   @OA\Parameter(
//     *     name="appId",
//     *     description="App ID",
//     *     in="path",
//     *     required=true,
//     *     @OA\Schema(
//     *        type="integer"
//     *     )
//     *   )
//     * )
//     */
//    public function getApplicationUser($appId)
//    {
//        $app = Application::find($appId);
//        if (is_null($app)) {
//            return response()->json(['message' => 'Application not found!'], 404);
//        } else {
//            return new AppUserResource($app);
//        }
//    }
}
