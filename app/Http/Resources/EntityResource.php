<?php

namespace App\Http\Resources;

use App\Models\Application;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 *  @OA\Schema(
 *      schema="EntityResource",
 *      title="Entity Resource",
 *      description="Entity Resource model",
 *      @OA\Property(property="app_id", type="integer"),
 * 	    @OA\Property(property="app_name", type="string"),
 *      @OA\Property(
 *              property="entities",
 *              type="array",
 *              @OA\Items(
 *                  @OA\Property(property="id", type="integer"),
 *                  @OA\Property(property="name", type="string")
 *              ),
 *      ),
 *  )
 */
class EntityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $appId = $this->application_id;
        $app = Application::find($appId);
        $appName = $app->name;
        $entities = $app->entities;
        return [
            'app_id' => $appId,
            'app_name' => $appName,
            'entities' => $entities,
        ];
    }
}
