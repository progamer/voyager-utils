<?php

namespace Codept\Core;

use Codept\Core\Commands\BackupBreadCommand;
use Codept\Core\Commands\GoogleTranslateCommand;
use Codept\Core\Commands\ImportLanguageFilesCommand;
use Codept\Core\Commands\ScanApplicationCommand;
use Codept\Core\FormFields\JsonFormField;
use Codept\Core\FormFields\TranslationFormField;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use TCG\Voyager\Facades\Voyager;
use Illuminate\Support\Facades\Blade;


class CoreServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->commands([
            BackupBreadCommand::class,
            ImportLanguageFilesCommand::class,
            GoogleTranslateCommand::class,
            ScanApplicationCommand::class
        ]);

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'codept/core');
        $this->loadMigrationsFrom(realpath(__DIR__.'/../database/migrations'));
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        $this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/core'),
        ], 'public');


        $this->publishes([
            __DIR__ . '/../config/core.php' => config_path('core.php'),
        ], 'config');

        Voyager::addFormField(TranslationFormField::class);
        Voyager::addFormField(JsonFormField::class);

        Blade::include('codept/core::includes.input', 'input');
        Blade::include('codept/core::includes.form-field', 'formField');
        Blade::include('codept/core::includes.form-field-display', 'formFieldDisplay');

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/core.php', 'core');

        App::bind('CoreWorkflow',function() {
            return new CoreWorkflow();
        });


    }
}
