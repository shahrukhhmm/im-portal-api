<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     title="Role",
 *     description="Role model",
 *     @OA\Xml(
 *         name="Role"
 *     )
 * )
 */
class Role extends Model
{
    use HasFactory;

    /**
     * @OA\Property(
     *     title="id",
     * )
     *
     * @var integer
     */
    private $id;

    /**
     * @OA\Property(
     *      title="name",
     * )
     *
     * @var string
     */
    private $name;
}
