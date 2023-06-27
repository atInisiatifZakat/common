<?php

declare(strict_types=1);

namespace Inisiatif\Package\Common\Providers;

use Inisiatif\Package\Common\Common;
use Illuminate\Support\ServiceProvider;

final class CommonServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/common.php', 'common');

        if (Common::isRunningMigrations()) {
            $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');
        }
    }
}
