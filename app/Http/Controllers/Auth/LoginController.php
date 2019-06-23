<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use Socialite;

class LoginController extends Controller
{
    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider($provider, $type)
    {
        session(['user-type' => $type]);

        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback($provider)
    {
        $user = Socialite::driver($provider)->user();
        $type = session('user-type');

        if ($user) {
            $params = 'provider=' . $provider . '&token=' . $user->token;
        } else {
            $params = 'external-login-error';
        }
        return redirect('login/' . $type . '?' . $params);
    }
}