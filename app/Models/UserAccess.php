<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     title="UserAccess",
 *     description="User Access model",
 *     @OA\Xml(
 *         name="UserAccess"
 *     )
 * )
 */
class UserAccess extends Model
{
    use HasFactory;

    /** @var string */
    protected $table = 'application_user';

    /**
     * @OA\Property(
     *      type="array",
     *      @OA\Items(
     *          type="object",
     *          @OA\Property(property="app_id", type="integer"),
     *          @OA\Property(property="brand_id", type="integer"),
     *          @OA\Property(property="role_id", type="integer"),
     *      ),
     * )
     */
    private $applications;
}
