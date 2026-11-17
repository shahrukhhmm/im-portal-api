<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/**
 * @OA\Schema(
 *     title="Language",
 *     description="Languages index",
 *     @OA\Xml(
 *         name="Language"
 *     )
 * )
 */

class Language extends Model
{
    use HasFactory;

    /**
     * @OA\Property()
     * @var integer
     */
    private $id;

    /**
     * @OA\Property(
     * )
     *
     * @var string
     */
    private $title;
    /**
     * @OA\Property(
     * )
     *
     * @var string
     */
    private $slug;
    /**
     * @OA\Property(
     * )
     *
     * @var integer
     */
    private $primary;

}
