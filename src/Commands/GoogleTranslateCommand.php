<?php

namespace Codept\Core\Commands;

use Codept\AdminTranslations\Translation;
use Codept\Core\Jobs\GoogleTranslateJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class GoogleTranslateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'translation:google';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $lines = Translation::query()
            ->whereRaw('LOWER(text->"$.en") = LOWER(text->"$.ar")')
            ->get();

        $lines = $lines->filter(function ($line){
            $englishLocale = $line->text['en'];
            return !preg_match("/[\w]+\_[\w]+/",$englishLocale)
                and !preg_match("/[\w]*\.[\w]+/",$englishLocale)
                and !preg_match("/[\w]*\:[\w]*/",$englishLocale)

                and !preg_match("/[\w]*\-[\w]*/",$englishLocale);
        });

        
        foreach ($lines as $line){
            dispatch(new GoogleTranslateJob($line));
        }
    }
}
