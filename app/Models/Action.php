<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     title="Action",
 *     description="Action model",
 *     @OA\Xml(
 *         name="Action"
 *     )
 * )
 */
class Action extends Model
{
    use HasFactory;

    /** @var string */
    protected $table = 'actions';

    /** @var array */
    protected $fillable = ['name'];

    public $timestamps = false;

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
}
