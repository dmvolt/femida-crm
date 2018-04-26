<?php
namespace App\Http\Middleware;

use Illuminate\Auth\AuthenticationException;


class Authenticate extends \Illuminate\Auth\Middleware\Authenticate
{
    protected function authenticate(array $guards)
    {
        if (empty($guards)) {
            if ( $this->auth->authenticate()->isBlocked() )
            {
                abort(403);
            }

            return $this->auth->authenticate();
        }

        foreach ($guards as $guard) {
            if ($this->auth->guard($guard)->check()) {
                return $this->auth->shouldUse($guard);
            }
        }

        throw new AuthenticationException('Unauthenticated.', $guards);
    }
}