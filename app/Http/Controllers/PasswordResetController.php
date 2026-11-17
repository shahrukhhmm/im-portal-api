<?php

namespace App\Http\Controllers;

use App\Jobs\SendEmailJob;
use App\Models\User;
use App\Notifications\PasswordRestTest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    /**
     * @OA\Post(
     * path="/auth/password/forgot",
     * operationId="ForgotPassword",
     * tags={"Auth"},
     * summary="Forgot Password",
     * description="User Can retrive Password Here",
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  format="string",
     *                  default="mail@gmail.com",
     *                  property="email"
     *              ),
     *     ),
     *    ),
     *      @OA\Response(
     *          response=200,
     *          description="Reset Password Email Sent Successfully",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  format="string",
     *                  default="EmailSent",
     *                  description="message",
     *                  property="message"
     *              ),
     *          ),
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad request",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  format="string",
     *                  property="message",
     *                  default="email not found"
     *              ),
     *          ),
     *       ),
     *      @OA\Response(response=404, description="Resource Not Found"),
     * )
     */

    function forgot(Request $request){
        return ;
        $request->validate(['email' => 'required|email']);

        $usr = User::where('email' , $request->email ) -> firstOrFail();
        $usr->notify(new PasswordRestTest());
        Log::info("reset for : " . $request->email );
        $status = Password::sendResetLink(
            $request->only('email')
        );

        SendEmailJob::dispatch('mjzavar', 'maxxxir@gmail.com', 'xxxx');
        event(new PasswordRestTest());


        return $status === Password::RESET_LINK_SENT
            ? response(['message' => __('messages.EmailSent')])
            : response(['message' => __($status)], 400);
    }

    /**
     * @OA\Post(
     * path="/auth/password/reset",
     * operationId="ResetPassword",
     * tags={"Auth"},
     * summary="Reset Password",
     * description="User can reset their password with token emailed",
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  format="string",
     *                  default="mail@gmail.com",
     *                  property="email"
     *              ),
     *              @OA\Property(
     *                  format="string",
     *                  default="*****",
     *                  property="password"
     *              ),
     *              @OA\Property(
     *                  format="string",
     *                  default="xxxxx",
     *                  property="token"
     *              ),
     *          ),
     *    ),
     *      @OA\Response(
     *          response=200,
     *          description="Reset Password Email Sent Successfully",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  format="string",
     *                  default="PasswordReset",
     *                  description="message",
     *                  property="message"
     *              ),
     *          ),
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad request",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  format="string",
     *                  property="message",
     *                  default="email not found"
     *              ),
     *          ),
     *       ),
     *      @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    function reset(Request $request){
        return ;
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => bcrypt($password)
                ])->setRememberToken(Str::random(60));
                $user->save();
              //  event(new PasswordReset($user));

            }
        );

        return $status === Password::PASSWORD_RESET
            ? response(['message' => __('messages.PasswordReset')])
            : response(['message' => __($status)], 400);

    }

}
