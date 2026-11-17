<?php

namespace App\Http\Controllers;

use App\Http\Resources\BrandUserResource;
use App\Models\Brand;
use App\Models\Utils;
use Illuminate\Http\Request;
use App\Http\Requests\BrandRequest;
use App\Http\Resources\BrandResource;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class BrandController extends Controller
{
    /**
     * @OA\Get(path="/brand",
     *   security={{"bearerAuth":{}}},
     *   tags={"Brand"},
     *   operationId="brandList",
     *   @OA\Response(response="200",
     *     description="Brand Collection",
     *     @OA\JsonContent(
     *       @OA\Property(
     *           property="data",
     *           type="array",
     *           @OA\Items(ref="#/components/schemas/BrandResource")
     *       ),
     *     )
     *   )
     * )
     */
    public function index()
    {
        $brands = DB::table('brands')
            ->orderBy('id')
            ->get();
        return BrandResource::collection($brands);
    }

    /**
     * @OA\Post(
     *   path="/brand",
     *   security={{"bearerAuth":{}}},
     *   tags={"Brand"},
     *   operationId="AddBrand",
     *   @OA\Response(
     *      response=201,
     *      description="Successful operation",
     *      @OA\JsonContent(ref="#/components/schemas/Brand")
     *   ),
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(ref="#/components/schemas/Brand")
     *   )
     * )
     */
    public function store(BrandRequest $request)
    {
        $brand = new Brand();
        $brand->name = $request->name;
        $brand->logo = $request->logo;
        $brand->application_id = $request->application_id;
        $brand->save();
        return response()->json($brand, 200);
    }

    /**
     * @OA\Get(path="/brand/{id}",
     *   security={{"bearerAuth":{}}},
     *   tags={"Brand"},
     *   operationId="showBrand",
     *   @OA\Response(response="200",
     *     description="Find a Brand",
     *     @OA\JsonContent(
     *       @OA\Property(
     *           property="data",
     *           type="object",
     *           ref="#/components/schemas/BrandResource"
     *       )
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="id",
     *     description="Brand ID",
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
        $brand = Brand::find($id);
        if (is_null($brand)) {
            return response()->json(['message' => 'Brand not found!'], 404);
        } else {
            return new BrandResource($brand);
        }
    }

    /**
     * @OA\Put(
     *   path="/brand/{id}",
     *   security={{"bearerAuth":{}}},
     *   tags={"Brand"},
     *   operationId="UpdateBrand",
     *   @OA\Response(response="202",
     *     description="Brand Update",
     *     @OA\JsonContent(ref="#/components/schemas/Brand")
     *   ),
     *   @OA\Parameter(
     *     name="id",
     *     description="Brand ID",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *        type="integer"
     *     )
     *   ),
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(ref="#/components/schemas/Brand")
     *   )
     * )
     */
    public function update(Request $request, $id)
    {
        $brand = Brand::find($id);
        if (is_null($brand)) {
            return response()->json(['message' => 'Brand not found!'], 404);
        } else {
            $brand->name = $request->name;
            $brand->logo = $request->logo;;
            $brand->application_id = $request->application_id;;
            $brand->save();
        }
        return response()->json($brand, 200);
    }

    /**
     * @OA\Delete(path="/brand/{id}",
     *   security={{"bearerAuth":{}}},
     *   tags={"Brand"},
     *   operationId="deleteBrand",
     *   @OA\Response(response="204",
     *     description="Brand Delete",
     *     @OA\JsonContent()
     *   ),
     *   @OA\Parameter(
     *     name="id",
     *     description="Brand ID",
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
        $brand = Brand::find($id);
        if (is_null($brand)) {
            return response()->json(['message' => 'Brand not found!'], 404);
        } else {
            $count = DB::table('brand_user')
                ->where('brand_id', '=', $id)->count();
            if ($count == 0) {
                $brand->delete();
                return response()->json(['message' => 'Brand deleted successfully'], 200);
            } else {
                return response()->json(['message' => 'This Brand is used!'], 403);
            }
        }
    }

//    /**
//     * @OA\Put(
//     *   path="/brand/setBrandUser/{brandId}/{userId}/{status}",
//     *   security={{"bearerAuth":{}}},
//     *   tags={"Brand"},
//     *   operationId="SetBrandUser",
//     *   @OA\Response(response="202",
//     *     description="Set Brand User",
//     *     @OA\JsonContent(ref="#/components/schemas/BrandUserResource")
//     *   ),
//     *   @OA\Parameter(
//     *     name="brandId",
//     *     description="Brand ID",
//     *     in="path",
//     *     required=true,
//     *     @OA\Schema(
//     *        type="integer"
//     *     )
//     *   ),
//     *   @OA\Parameter(
//     *     name="userId",
//     *     description="User ID",
//     *     in="path",
//     *     required=true,
//     *     @OA\Schema(
//     *        type="integer"
//     *     )
//     *   ),
//     *   @OA\Parameter(
//     *     name="status",
//     *     description="Status: 0 or 1",
//     *     in="path",
//     *     required=true,
//     *     @OA\Schema(
//     *        type="integer"
//     *     )
//     *   )
//     * )
//     */
//    public function setBrandUser($brandId, $userId, $status)
//    {
//        $brand = Brand::find($brandId);
//        if (is_null($brand)) {
//            return response()->json(['message' => 'Brand not found!'], 404);
//        } else {
//            $user = User::find($userId);
//            if (is_null($user)) {
//                return response()->json(['message' => 'User not found!'], 404);
//            }
//            if ($status == 1)
//                $brand->users()->attach($user);
//            else
//                $brand->users()->detach($user);
//        }
//        return new BrandUserResource($brand);
//    }
//
//    /**
//     * @OA\Get(path="/brand/getBrandUser/{brandId}",
//     *   security={{"bearerAuth":{}}},
//     *   tags={"Brand"},
//     *   operationId="getBrandUser",
//     *   @OA\Response(response="200",
//     *     description="get brand user list",
//     *     @OA\JsonContent(
//     *       @OA\Property(
//     *           property="data",
//     *           type="object",
//     *           ref="#/components/schemas/BrandUserResource"
//     *       )
//     *     )
//     *   ),
//     *   @OA\Parameter(
//     *     name="brandId",
//     *     description="Brand ID",
//     *     in="path",
//     *     required=true,
//     *     @OA\Schema(
//     *        type="integer"
//     *     )
//     *   )
//     * )
//     */
//    public function getBrandUser($brandId)
//    {
//        $brand = Brand::find($brandId);
//        if (is_null($brand)) {
//            return response()->json(['message' => 'Brand not found!'], 404);
//        } else {
//            return new BrandUserResource($brand);
//        }
//    }

//    /**
//     * @OA\Post(
//     *   path="/brand/assignToUser",
//     *   security={{"bearerAuth":{}}},
//     *   tags={"Brand"},
//     *   operationId="brandsAssignToUser",
//     *   @OA\Response(response="202",
//     *     description="Update User Brands",
//     *     @OA\JsonContent(
//     *       @OA\Property(
//     *           property="data",
//     *           type="object",
//     *           ref="#/components/schemas/BrandUserResource"
//     *       )
//     *     )
//     *   ),
//     *   @OA\RequestBody(
//     *     required=true,
//     *     @OA\JsonContent(
//     *       @OA\Property(
//     *           property="data",
//     *           type="object",
//     *           ref="#/components/schemas/BrandUserResource"
//     *       ),
//     *     )
//     *   )
//     * )
//     */
//    public function assignToUser(Request $request)
//    {
//        $output = [];
//        $brands_array = [];
//        $object = $request->get('data');
//        $user = User::find($object['user_id']);
//        if (is_null($user)) {
//            return response()->json(['message' => 'User not found!'], 404);
//        } else {
//            $values = $object['brands'];
//            foreach ($values as $value) {
//                array_push($brands_array, $value['brand_id']);
//            }
//
//            //Sync
//            $brands = Brand::find($brands_array);
//            $user->brands()->sync($brands);
//
//            foreach ($values as $value) {
//                array_push($output, [
//                    'brand_id' => $value['brand_id']
//                ]);
//            }
//
//            $result = [
//                'user_id' => $object['user_id'],
//                'brands' => $output
//            ];
//
//            $data = [
//                'data' => $result
//            ];
//            return response()->json($data, 200);
//        }
//    }
//
//    /**
//     * @OA\Post(
//     *   path="/brand/attachUser",
//     *   security={{"bearerAuth":{}}},
//     *   tags={"Brand"},
//     *   operationId="brandsAttachUser",
//     *   @OA\Response(response="202",
//     *     description="Update User Brands",
//     *     @OA\JsonContent(
//     *       @OA\Property(
//     *           property="data",
//     *           type="object",
//     *           ref="#/components/schemas/BrandUserResource"
//     *       )
//     *     )
//     *   ),
//     *   @OA\RequestBody(
//     *     required=true,
//     *     @OA\JsonContent(
//     *       @OA\Property(
//     *           property="data",
//     *           type="object",
//     *           ref="#/components/schemas/BrandUserResource"
//     *       ),
//     *     )
//     *   )
//     * )
//     */
//    public function attachUser(Request $request)
//    {
//        $output = [];
//        $object = $request->get('data');
//        $user = User::find($object['user_id']);
//        if (is_null($user)) {
//            return response()->json(['message' => 'User not found!'], 404);
//        } else {
//            $values = $object['brands'];
//            foreach ($values as $value) {
//                $count = DB::table('brand_user')
//                    ->where([
//                        ['brand_id', '=', $value['brand_id']],
//                        ['user_id', '=', $object['user_id']]
//                    ])->count();
//                if ($count == 0) {
//                    $brand = Brand::find($value['brand_id']);
//                    $user->brands()->attach($brand);
//                }
//                array_push($output, [
//                    'brand_id' => $value['brand_id']
//                ]);
//            }
//
//            $result = [
//                'user_id' => $object['user_id'],
//                'brands' => $output
//            ];
//
//            $data = [
//                'data' => $result
//            ];
//            return response()->json($data, 200);
//        }
//    }
//
//    /**
//     * @OA\Post(
//     *   path="/brand/detachUser",
//     *   security={{"bearerAuth":{}}},
//     *   tags={"Brand"},
//     *   operationId="brandsDetachUser",
//     *   @OA\Response(response="202",
//     *     description="Update User Brands",
//     *     @OA\JsonContent(
//     *       @OA\Property(
//     *           property="data",
//     *           type="object",
//     *           ref="#/components/schemas/BrandUserResource"
//     *       )
//     *     )
//     *   ),
//     *   @OA\RequestBody(
//     *     required=true,
//     *     @OA\JsonContent(
//     *       @OA\Property(
//     *           property="data",
//     *           type="object",
//     *           ref="#/components/schemas/BrandUserResource"
//     *       ),
//     *     )
//     *   )
//     * )
//     */
//    public function detachUser(Request $request)
//    {
//        $output = [];
//        $object = $request->get('data');
//        $user = User::find($object['user_id']);
//        if (is_null($user)) {
//            return response()->json(['message' => 'User not found!'], 404);
//        } else {
//            $values = $object['brands'];
//            foreach ($values as $value) {
//                $count = DB::table('brand_user')
//                    ->where([
//                        ['brand_id', '=', $value['brand_id']],
//                        ['user_id', '=', $object['user_id']]
//                    ])->count();
//                if ($count != 0) {
//                    $brand = Brand::find($value['brand_id']);
//                    $user->brands()->detach($brand);
//                }
//                array_push($output, [
//                    'brand_id' => $value['brand_id']
//                ]);
//            }
//
//            $result = [
//                'user_id' => $object['user_id'],
//                'brands' => $output
//            ];
//
//            $data = [
//                'data' => $result
//            ];
//            return response()->json($data, 200);
//        }
//    }
//
//    /**
//     * @OA\Get(path="/brand/getByUserId/{userId}",
//     *   security={{"bearerAuth":{}}},
//     *   tags={"Brand"},
//     *   operationId="GetBrandsByUserId",
//     *   @OA\Response(response="200",
//     *     description="Get Brand List By user id",
//     *     @OA\JsonContent(
//     *       @OA\Property(
//     *           property="data",
//     *           type="array",
//     *           @OA\Items(ref="#/components/schemas/BrandResource")
//     *       ),
//     *     )
//     *   ),
//     *   @OA\Parameter(
//     *     name="userId",
//     *     description="User ID",
//     *     in="path",
//     *     required=true,
//     *     @OA\Schema(
//     *        type="integer"
//     *     )
//     *   )
//     * )
//     */
//    public function getByUserId($userId)
//    {
//        $user = User::find($userId);
//        if (is_null($user)) {
//            return response()->json(['message' => 'User not found!'], 404);
//        } else {
//            $brands = $user->brands;
//            return BrandResource::collection($brands);
//        }
//    }

}
