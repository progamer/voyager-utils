<?php

namespace Codept\Core\Commands;

use Codept\AdminTranslations\Translation;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Illuminate\Translation\FileLoader;

class ImportLanguageFilesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'translation:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Load translation keys and values from language files to database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }


    private function handleTranslation($namespace, $group, $key, $locale, $trans){

        if(is_array($trans)){
            foreach ($trans as $subKey => $subtrans){
                $this->handleTranslation($namespace, $group, $key.'.'.$subKey, $locale, $subtrans);
            }
            return;
        }

        $translation = Translation::withTrashed()
            ->where('namespace', $namespace)
            ->where('group', $group)
            ->where('key', $key)
            ->first();

        $text = !empty($translation->text)? $translation->text : [];
        $text[$locale] = $trans;

        if($translation){
            $translation->key = $key;
            $translation->text = $text;
            $translation->namespace = $namespace;
            $translation->group = $group;
            $translation->save();
        }
        else{

            foreach (config('voyager.multilingual.locales') as $locale){
                $text[$locale] = $trans;
            }

            Translation::create([
                'key' => $key,
                'text' => $text,
                'namespace' => $namespace,
                'group' => $group,
            ]);
        }
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $counter = 0;

        $app = App::getInstance();

        $namespaces = [
            'voyager' => base_path('packages/voyager').'/publishable/lang',
            '*' => $app['path.lang'],
        ];

        foreach ($namespaces as $namespace => $namespacePath)
        {

            $loader =  new FileLoader($app['files'], $namespacePath);
            foreach (config("voyager.multilingual.locales") as $locale)
            {
                if(!file_exists ( $namespacePath.DIRECTORY_SEPARATOR.$locale )){
                    continue;
                }

                $files = array_diff(scandir($namespacePath.DIRECTORY_SEPARATOR.$locale), array('..', '.'));
                foreach ($files as $fileName)
                {
                    $group = str_replace(".php", "", $fileName);

                    $fileTranslations = $loader->load($locale, $group );

                    foreach ($fileTranslations as $key => $trans)
                    {
                        $this->handleTranslation($namespace, $group, $key, $locale, $trans);
                        $counter++;
                    }
                }
            }
        }

        $this->info("$counter word has been imported");
    }
}
