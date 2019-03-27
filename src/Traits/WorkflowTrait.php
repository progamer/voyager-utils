<?php
namespace Codept\Core\Traits;

/**
 * Created by PhpStorm.
 * User: issa
 * Date: 2019-03-12
 * Time: 12:01
 */

trait WorkflowTrait
{
    public function workflowLogs(){
        return $this->morphMany('Codept\Core\Models\WorkflowLog', 'workflowable');
    }
}