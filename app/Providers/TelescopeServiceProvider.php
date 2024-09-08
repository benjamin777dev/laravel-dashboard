<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Laravel\Telescope\IncomingEntry;
use Laravel\Telescope\Telescope;
use Laravel\Telescope\TelescopeApplicationServiceProvider;

class TelescopeServiceProvider extends TelescopeApplicationServiceProvider
{

    protected $allowedEmails = ['tech@coloradohomerealty.com', 'phillip@coloradohomerealty.com'];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        Log::info("At register for Telescope");
        Telescope::night();

        $this->hideSensitiveRequestDetails();
        Log::info("-- after hiding sensitive");

        $isLocal = $this->app->environment('local');
        Log::info('-- is Local: ' . ($isLocal ? 'true' : 'false'));

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

    protected function gate(): void
    {
        Log::info("At gate for Telescope");

        // Define who can access Telescope using the gate
        Gate::define('viewTelescope', function ($user) {
            Log::info("Checking email authorization");

            // First, check if the user email is authorized
            if (!in_array($user->email, $this->allowedEmails)) {
                Log::info("User is not authorized by email.");
                return false;
            }

            // Then, check for the passcode in the session
            $validPasscode = config('app.TELESCOPE_ACCESS_PASSCODE');
            $storedPasscode = request()->session()->get('telescope_passcode');

            Log::info("Stored passcode: " . ($storedPasscode ? 'present' : 'none'));

            // If passcode is correct, grant access
            return $storedPasscode === $validPasscode;
        });
    }
}
