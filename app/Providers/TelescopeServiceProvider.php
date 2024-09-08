<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Laravel\Telescope\IncomingEntry;
use Laravel\Telescope\Telescope;
use Laravel\Telescope\TelescopeApplicationServiceProvider;

class TelescopeServiceProvider extends TelescopeApplicationServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Telescope::night();

        $this->hideSensitiveRequestDetails();

        $isLocal = $this->app->environment('local');

        // Filter what gets logged in Telescope
        Telescope::filter(function (IncomingEntry $entry) use ($isLocal) {
            return $isLocal ||
            $entry->isReportableException() ||
            $entry->isFailedRequest() ||
            $entry->isFailedJob() ||
            $entry->isScheduledTask() ||
            $entry->hasMonitoredTag() ||
            $entry->isGate() ||
            $entry->isQuery() ||
            $entry->isException() ||
            $entry->isLog() ||
            $entry->isDump() ||
            $entry->isSlowQuery();
        });

        // Only add passcode protection (email restriction is handled by the gate)
        Telescope::auth(function ($request) use ($isLocal) {
            // Allow local environment without passcode
            if ($isLocal) {
                return true;
            }
        
            // Check if passcode is correct (stored in .env)
            $inputPasscode = $request->get('passcode');
            $validPasscode = env('TELESCOPE_ACCESS_PASSCODE');
        
            if ($inputPasscode !== $validPasscode) {
                // If passcode is incorrect or missing, show passcode form
                return redirect()->view('passcode');
            }
        
            return true;
        });
    }

    /**
     * Prevent sensitive request details from being logged by Telescope.
     */
    protected function hideSensitiveRequestDetails(): void
    {
        if ($this->app->environment('local')) {
            return;
        }

        Telescope::hideRequestParameters(['_token']);

        Telescope::hideRequestHeaders([
            'cookie',
            'x-csrf-token',
            'x-xsrf-token',
        ]);
    }

    /**
     * Register the Telescope gate.
     *
     * This gate determines who can access Telescope in non-local environments.
     */
    protected function gate(): void
    {
        Gate::define('viewTelescope', function ($user) {
            $allowedEmails = [
                'tech@coloradohomerealty.com',
                'phillip@coloradohomerealty.com',
            ];

            return in_array($user->email, $allowedEmails);
        });
    }
}
