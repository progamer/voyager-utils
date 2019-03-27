<?php

namespace Codept\Core\Commands;

use Carbon\Carbon;
use Codept\AdminTranslations\Translation;
use Codept\AdminTranslations\TranslationsScanner;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Input\InputArgument;

class ScanApplicationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'translation:scan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scans all PHP files, extract translations and stores them into the database';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Artisan::call('cache:clear');
        $scanner = app(TranslationsScanner::class);
        collect($this->argument('paths'))->each(function ($path) use ($scanner) {
            $scanner->addScannedPath($path);
        });

        list($trans, $__) = $scanner->getAllViewFilesWithTranslations();

        /** @var Collection $trans */
        /** @var Collection $__ */

        DB::transaction(function () use ($trans, $__) {
            Translation::query()->whereNull('deleted_at')->update([
                    'deleted_at' => Carbon::now(),
                ]);

            $trans->each(function ($trans) {


                $namespace = '*';
                $group = '*';
                if(strpos($trans, "::")){
                    list($namespace, $groupAndKey) = explode('::', $trans, 2);
                    list($group, $key) = explode('.', $groupAndKey, 2);
                }
                else{
                    list($group, $key) = explode(".", $trans, 2);
                }

                $this->createOrUpdate($namespace, $group, $key);
            });

            $__->each(function ($default) {


                $namespace = '*';
                $group = '*';
                $key = $default;

                if(strpos($default, "::")){
                    list($namespace, $groupAndKey) = explode('::', $default, 2);
                    if(strpos($groupAndKey, ".")){
                        list($group, $key) = explode('.', $groupAndKey, 2);
                    }
                    else{
                        $this->info($default);
                    }
                }

                $this->createOrUpdate($namespace, $group, $key);
            });

            $this->info(($trans->count() + $__->count()).' translations saved');
        });
    }

    protected function createOrUpdate($namespace, $group, $key)
    {
        /** @var Translation $translation */
        $translation = Translation::withTrashed()->where('namespace', $namespace)->where('group', $group)->where('key', $key)->first();

        //Lang::get('pagination')
        $defaultLocale = config('app.locale');

        if ($translation) {
            if (! $this->isCurrentTransForTranslationArray($translation, $defaultLocale)) {
                $translation->restore();
            }
        } else {

            $text = [];
            foreach (config('voyager.multilingual.locales') as $locale) {
                \App::setLocale($locale);

                $transKey =  $key;
                if($group != '*'){
                    $transKey = "$group.$transKey";
                }
                if($namespace != '*'){
                    $transKey = "$namespace::$transKey";
                }

                $text[$locale] = trans($transKey, [], $locale);
            }

            $translation = Translation::make([
                'namespace' => $namespace,
                'group' => $group,
                'key' => $key,
                'text' => $text,
            ]);

            if (! $this->isCurrentTransForTranslationArray($translation, $defaultLocale)) {
                $translation->save();
            }
        }
    }

    private function isCurrentTransForTranslationArray(Translation $translation, $locale)
    {
        if ($translation->group == '*') {
            return is_array(__($translation->key, [], $locale));
        } elseif ($translation->namespace == '*') {
            return is_array(trans($translation->group.'.'.$translation->key, [], $locale));
        } else {
            return is_array(trans($translation->namespace.'::'.$translation->group.'.'.$translation->key, [], $locale));
        }
    }

    protected function getArguments()
    {
        return [
            [
                'paths',
                InputArgument::IS_ARRAY,
                'Array of paths to scan.',
                (array) config('admin-translations.scanned_directories'),
            ],
        ];
    }
}
