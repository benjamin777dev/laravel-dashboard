<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TelescopePasscodeMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Check if the passcode is stored in the session
        $storedPasscode = $request->session()->get('telescope_passcode');
        $validPasscode = config('app.TELESCOPE_ACCESS_PASSCODE');
        
        Log::info("Checking passcode in middleware: " . ($storedPasscode ? 'present' : 'none'));

        // If passcode is invalid or missing, redirect to passcode form
        if ($storedPasscode !== $validPasscode) {
            Log::info('Passcode invalid, redirecting to form.');
            return redirect()->route('telescope.passcode');
        }

        // If passcode is correct, proceed to the next middleware
        return $next($request);
    }
}

