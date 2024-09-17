<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\User;

class CheckUserId
{
    public function handle($request, Closure $next)
    {
        $id = $request->query('id');

        if ($id) {
            $user = User::find($id);

            if ($user) {
                Auth::login($user);
                return redirect('/dashboard');
            } else {
                return redirect()->back()->with('error', 'Anda tidak memiliki akses.');
            }
        }

        return $next($request);
    }
}
