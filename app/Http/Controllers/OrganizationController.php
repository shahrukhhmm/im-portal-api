<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrganizationResource;
use App\Models\Organization;
use App\Models\Utils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrganizationController extends Controller
{
    /**
     * @OA\Get(path="/organization",
     *   security={{"bearerAuth":{}}},
     *   tags={"Organization"},
     *   operationId="OrganizationList",
     *   @OA\Response(response="200",
     *     description="Organization Collection",
     *     @OA\JsonContent(
     *       @OA\Property(
     *           property="data",
     *           type="array",
     *           @OA\Items(ref="#/components/schemas/OrganizationResource")
     *       ),
     *     )
     *   )
     * )
     */
    public function index()
    {
        $list = DB::table('organizations')
            ->orderBy('id')
            ->get();
        return OrganizationResource::collection($list);
    }

    /**
     * @OA\Post(
     *   path="/organization",
     *   security={{"bearerAuth":{}}},
     *   tags={"Organization"},
     *   operationId="AddOrganization",
     *   @OA\Response(response="201",
     *     description="Organization Create",
     *     @OA\JsonContent(ref="#/components/schemas/Organization")
     *   ),
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(ref="#/components/schemas/Organization")
     *   )
     * )
     */
    public function store(Request $request)
    {
        $org = new Organization();
        $org->name = $request->name;
        $org->logo = $request->logo;
        $org->save();
        return response()->json($org, 200);
    }

    /**
     * @OA\Get(path="/organization/{id}",
     *   security={{"bearerAuth":{}}},
     *   tags={"Organization"},
     *   operationId="GetOrganization",
     *   @OA\Response(response="200",
     *     description="Find a Organization",
     *     @OA\JsonContent(
     *       @OA\Property(
     *           property="data",
     *           type="object",
     *           ref="#/components/schemas/Organization"
     *       )
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="id",
     *     description="Organization ID",
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
        $org = Organization::find($id);
        if (is_null($org)) {
            return response()->json(['message' => 'Organization not found!'], 404);
        } else {
            return new OrganizationResource($org);
        }
    }

    /**
     * @OA\Put(
     *   path="/organization/{id}",
     *   security={{"bearerAuth":{}}},
     *   tags={"Organization"},
     *   operationId="UpdateOrganization",
     *   @OA\Response(response="202",
     *     description="Organization Update",
     *     @OA\JsonContent(ref="#/components/schemas/Organization")
     *   ),
     *   @OA\Parameter(
     *     name="id",
     *     description="Organization ID",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *        type="integer"
     *     )
     *   ),
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(ref="#/components/schemas/Organization")
     *   )
     * )
     */
    public function update(Request $request, $id)
    {
        $org = Organization::find($id);
        if (is_null($org)) {
            return response()->json(['message' => 'Organization not found!'], 404);
        } else {
            $org->name = $request->name;
            $org->logo = $request->logo;
            $org->save();
        }
        return response()->json($org, 200);
    }

    /**
     * @OA\Delete(path="/organizations/{id}",
     *   security={{"bearerAuth":{}}},
     *   tags={"Organization"},
     *   operationId="DeleteOrganization",
     *   @OA\Response(response="204",
     *     description="Organization Delete",
     *     @OA\JsonContent()
     *   ),
     *   @OA\Parameter(
     *     name="id",
     *     description="Organization ID",
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
        $org = Organization::find($id);
        if (is_null($org)) {
            return response()->json(['message' => 'Organization not found!'], 404);
        } else {
            $count = 0;
            //   $count = Brand::where('org_id', '=', $id)->count();
            if ($count == 0) {
                $org->delete();
                return response()->json(['message' => 'Organization deleted successfully'], 200);
            } else {
                return response()->json(['message' => 'This Organization is used!'], 403);
            }
        }
    }
}
