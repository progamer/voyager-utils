<?php

namespace Codept\Core\Models;

use Illuminate\Database\Eloquent\Model;

class WorkflowLog extends Model
{

    protected $fillable = ['user_id','workflowable_id', 'workflowable_type', 'workflow', 'transition', 'comment', 'attachments'];

    public function workflowable()
    {
        return $this->morphTo();
    }
}
