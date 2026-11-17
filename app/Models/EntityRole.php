<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     title="EntityRole",
 *     description="EntityRole model",
 *     @OA\Xml(
 *         name="EntityRole"
 *     )
 * )
 */
class EntityRole extends Model
{
    use HasFactory;

    /** @var string */
    protected $table = 'entity_role';

    /** @var array */
    protected $fillable = ['role_id', 'entity_id', 'action_id'];

    public $timestamps = false;

    /**
     * @OA\Property(
     * )
     *
     * @var integer
     */
    private $role_id;

    /**
     * @OA\Property(
     * )
     *
     * @var integer
     */
    private $entity_id;

    /**
     * @OA\Property(
     * )
     *
     * @var integer
     */
    private $action_id;
}
