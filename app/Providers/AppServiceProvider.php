<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Setting;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $connection = Schema::connection('pgsql');

        if ($connection->hasTable('settings')) {
            $settings = Setting::first();
            if (!$settings) {
                $settings = new Setting;
            }
            view()->share('settings', $settings);
        }

        if ($connection->hasTable('categories')) {
            view()->share('categoryTree', Category::getCategoryTree());
        }

        Blade::directive('priceFormat', function($number) {
            return "<?php echo number_format($number, 0,',',' ') ?>";
        });
    }
}
