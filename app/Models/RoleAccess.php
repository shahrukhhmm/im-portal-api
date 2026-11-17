<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     title="RoleAccess",
 *     description="Role Access model",
 *     @OA\Xml(
 *         name="RoleAccess"
 *     )
 * )
 */
class RoleAccess extends Model
{
    use HasFactory;

    /** @var string */
    protected $table = 'entity_role';

    /**
     * @OA\Property(
     * )
     *
     * @var integer
     */
    private $app_id;

    /**
     * @OA\Property(
     * )
     *
     * @var integer
     */
    private $role_id;

    /**
     * @OA\Property(
     *      type="array",
     *      @OA\Items(
     *          type="object",
     *          @OA\Property(property="entity_id", type="integer"),
     *          @OA\Property(property="action_ids", type="array",
     *              @OA\Items(
     *                  type="integer",
     *              ),
     *          )
     *      )
     * )
     */
    private $entities;
}
