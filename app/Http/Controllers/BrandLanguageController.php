<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\BrandLanguage;
use App\Models\Language;
use Illuminate\Http\Request;

class BrandLanguageController extends Controller
{
    /**
     * @OA\Get (
     *   path="/brand/languages/options",
     *   security={{"bearerAuth":{}}},
     *   tags={"Language"},
     *   operationId="LanguageOptions",
     *   @OA\Response(response="201",
     *     description="List of available languages",
     *     @OA\JsonContent(ref="#/components/schemas/Language")
     *   )
     * )
     */

    function options(){
        return Language::all();
    }

    /**
     * @OA\Get(
     *   path="/brand/languages/{brandID}",
     *   security={{"bearerAuth":{}}},
     *   tags={"Language"},
     *   operationId="BrandLanguageIndex",
     *   @OA\Response(response="201",
     *     description="Get Brand Languages",
     *     @OA\JsonContent(ref="#/components/schemas/BrandLanguage")
     *   ),
     *   @OA\Parameter(
     *     name="brandID",
     *     description="Brand ID",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *        type="integer"
     *     )
     *   ),
     * )
     */

    function index(Brand $brand){
        return BrandLanguage::where('brand_id' , $brand->id  )->with('Language')->get();
    }

    /**
     * @OA\Post (
     *   path="/brand/languages/{brandID}",
     *   security={{"bearerAuth":{}}},
     *   tags={"Language"},
     *   operationId="BrandLanguageCreate",
     *   @OA\Response(response="201",
     *     description="Get Brand Languages",
     *     @OA\JsonContent(ref="#/components/schemas/BrandLanguage")
     *   ),
     *   @OA\Parameter(
     *     name="brandID",
     *     description="Brand ID",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *        type="integer"
     *     )
     *   ),
     *   @OA\RequestBody(
     *     @OA\JsonContent(
     *       @OA\Property(
     *
     *           property="language_id",
     *           type="integer",
     *       ),
     *       @OA\Property(
     *
     *           property="primary",
     *           type="integer",
     *       ),
     *     )
     *   )
     * )
     */

    function create(Request $request , Brand $brand ){

         $request->validate([
            'language_id' => 'required|exists:languages,id' ,
            'primary' => 'sometimes|in:0,1'
        ]);

         try
         {
             $brand->Languages()->attach($request->language_id , ['primary'=> $request->primary === 1 ? 1 : null ]);
         }
         catch (\Exception $exception)
         {
            if(str($exception->getMessage())->contains('brand_languages_brand_id_primary_unique'))
                return response(['message'=>'this brand already has a primary language'] , 500 );
            if(str($exception->getMessage())->contains('brand_languages_brand_id_language_id_unique'))
                return response(['message'=>'this language has already been added to this brand'] , 500 );
            else
               throw $exception ;

         }
        return $this->index($brand);
    }

    /**
     * @OA\Delete  (
     *   path="/brand/languages/{brandLanguageID}",
     *   security={{"bearerAuth":{}}},
     *   tags={"Language"},
     *   operationId="brandLanguageDelete",
     *      @OA\Response(
     *          response=200,
     *          description="language has been deleted",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  format="string",
     *                  default="language has been deleted",
     *                  description="message",
     *                  property="message"
     *              ),
     *          ),
     *       ),
     *   @OA\Parameter(
     *     name="brandLanguageID",
     *     description="Brand Language ID",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *        type="integer"
     *     )
     *   ),
     *  )
     */

    function delete(BrandLanguage $brandLanguage){

        if($brandLanguage->primary == 1 )
            return response(['message'=>'cant delete primary language'] , 500 );

        $brandLanguage->delete();
        return response(['message'=>'language has been deleted']  );
    }



}
