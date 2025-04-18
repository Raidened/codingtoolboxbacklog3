<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsTeacherOrAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        $userRole = $user->school()->pivot->role ?? null;
        
        // Seuls les enseignants et administrateurs peuvent accéder
        if ($userRole === 'teacher' || $userRole === 'admin') {
            return $next($request);
        }
        
        // Rediriger les étudiants vers la page des groupes
        return redirect()->route('groups.index')
            ->with('error', 'Vous n\'avez pas les droits nécessaires pour effectuer cette action.');
    }
}