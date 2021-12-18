<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

trait AuthenticateUserTrait
{
    /**
     * @method POST
     * @uri /auth/login
     * @throws ValidationException
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * @param Request $request
     * @return void
     */
    protected function validateLogin(Request $request): void
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string'
        ]);
    }

    /**
     * @param Request $request
     * @return bool
     */
    protected function attemptLogin(Request $request): bool
    {
        return $this->getGuard()->attempt(
          $this->getCredentials($request), $request->filled('remember')
        );
    }

    /**
     * @param Request $request
     * @return array
     */
    protected function getCredentials(Request $request): array
    {
        return $request->only(['username', 'password']);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendLoginResponse(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->session()->regenerate();

        return redirect()->intended('/home');
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws ValidationException
     */
    protected function sendFailedLoginResponse(Request $request): void
    {
        throw ValidationException::withMessages([
            'username' => 'Wrong Credentials' // TODO: use trans([...]) here
        ]);
    }

    /**
     * @method GET
     * @uri /auth/logout
     * @param Request $request
     * @return void
     */
    public function logout(Request $request)
    {
        $this->getGuard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        redirect('/');
    }

    /**
     * @return \Illuminate\Contracts\Auth\Guard|\Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function getGuard()
    {
        return Auth::guard();
    }
}
