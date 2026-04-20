<?php

namespace App\Http\Responses\Auth;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Fortify\Contracts\TwoFactorLoginResponse as TwoFactorLoginResponseContract;
use Laravel\Fortify\Fortify;
use Symfony\Component\HttpFoundation\Response;

class ForcePasswordChangeTwoFactorLoginResponse implements TwoFactorLoginResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param  Request  $request
     * @return Response
     */
    public function toResponse($request)
    {
        $redirectPath = $request->user()?->force_password_change
            ? route('force-password.edit')
            : Fortify::redirects('login');

        return $request->wantsJson()
            ? new JsonResponse(['redirect' => $redirectPath], 200)
            : redirect()->intended($redirectPath);
    }
}
