<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     title="Login",
 *     description="Login model",
 *     @OA\Xml(
 *         name="Login"
 *     )
 * )
 */
class Login extends Model
{
    use HasFactory;

    /**
     * @OA\Property(
     * )
     *
     * @var string
     */
    private $email;

    /**
     * @OA\Property(
     * )
     *
     * @var string
     */
    private $password;
}
