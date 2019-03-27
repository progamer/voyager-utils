<?php

namespace Codept\Core\Facades;

use Illuminate\Support\Facades\Facade;

class CoreWorkflowFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'CoreWorkflow';
    }
}