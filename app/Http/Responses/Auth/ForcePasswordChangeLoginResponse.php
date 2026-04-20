<?php

namespace App\Http\Responses\Auth;

use Illuminate\Http\Request;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Laravel\Fortify\Fortify;
use Symfony\Component\HttpFoundation\Response;

class ForcePasswordChangeLoginResponse implements LoginResponseContract
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
            ? response()->json([
                'two_factor' => false,
                'redirect' => $redirectPath,
            ])
            : redirect()->intended($redirectPath);
    }
}
