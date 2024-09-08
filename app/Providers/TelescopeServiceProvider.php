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

        // Filter what gets logged in Telescope
        Telescope::filter(function (IncomingEntry $entry) {
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

            return true;
        });
    }
}
