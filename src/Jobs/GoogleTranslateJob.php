<?php

namespace Codept\Core\Jobs;

use Codept\AdminTranslations\Translation;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Stichoza\GoogleTranslate\GoogleTranslate;

class GoogleTranslateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $obj;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Translation $obj)
    {
        $this->obj = $obj;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        sleep(1);


        try{
            $tr = new GoogleTranslate('ar','en',[
                'proxy' => [
                    'http'  => 'http://178.33.9.97',
                ]
            ]);
            $textArr = $this->obj->text;
            $textArr['ar'] = $tr->translate($textArr['en']);
            $this->obj->text = $textArr;


            $metadata = $this->obj->metadata;
            $metadata['translated_by'] = 'google';

            $this->obj->metadata = $metadata;

            $this->obj->save();

        }
        catch (\Exception $exx){
            logger($exx->getMessage());

        }



    }
}
