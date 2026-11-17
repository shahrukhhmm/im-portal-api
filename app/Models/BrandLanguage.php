<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/**
 * @OA\Schema(
 *     title="BrandLanguage",
 *     description="Languages index",
 *     @OA\Xml(
 *         name="BrandLanguage"
 *     )
 * )
 */

class BrandLanguage extends Model
{
    use HasFactory;
    protected $fillable = [
        'language_id' ,
        'primary'
    ];

    function Language(){
        return $this->belongsTo(Language::class);
    }
    function Brand(){
        return $this->belongsTo(Brand::class);
    }

    /**
     * @OA\Property()
     * @var integer
     */
    private $id;

    /**
     * @OA\Property()
     * @var integer
     */
    private $primary;
    /**
     * @OA\Property(ref="#/components/schemas/Language")
     * @var integer
     */
    private $language;

}
