<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     title="Entity",
 *     description="Entity model",
 *     @OA\Xml(
 *         name="Entity"
 *     )
 * )
 */
class Entity extends Model
{
    use HasFactory;

    /** @var string */
    protected $table = 'entities';

    /** @var array */
    protected $fillable = ['name', 'application_id'];

    /**
     * @OA\Property(
     * )
     *
     * @var integer
     */
    private $id;

    /**
     * @OA\Property(
     * )
     *
     * @var string
     */
    private $name;

    /**
     * @OA\Property(
     * )
     *
     * @var integer
     */
    private $application_id;
}
