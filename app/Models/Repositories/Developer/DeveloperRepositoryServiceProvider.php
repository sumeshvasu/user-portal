<?php
/**
 * ServiceProvider : DeveloperRepositoryServiceProvider.
 *
 * This file used to register DeveloperRepositoryService
 *
 * @author Sumesh K V <sumeshvasu@gmail.com>
 *
 * @version 1.0
 */

namespace App\Models\Repositories\Developer;

use App\Models\Entities\Developer;
use Illuminate\Support\ServiceProvider;

class DeveloperRepositoryServiceProvider extends ServiceProvider
{
    /**
     * Registers the DeveloperInterface with Laravels IoC Container.
     */
    public function register()
    {
        // Bind the returned class to the namespace 'App\Models\Repositories\Developer\DeveloperInterface
        $this->app->bind(
            'App\Models\Repositories\Developer\DeveloperInterface',
            function ($app) {
                return new DeveloperRepository(new Developer());
            }
        );
    }
}
