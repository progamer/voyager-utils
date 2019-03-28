<?php

namespace Codept\Core\Http\Controllers;

use Codept\Core\Facades\CoreWorkflowFacade as CoreWorkflow;
use Codept\Core\Models\WorkflowLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use TCG\Voyager\Http\Controllers\VoyagerBaseController;
use Workflow;

/**
 * Class WorkflowController
 * handle applying workflow transitions
 *
 * @package App\Http\Controllers
 */
class WorkflowController extends VoyagerBaseController
{
    /**
     * Apply work flow to an object and save to database
     * @param $class
     * @param $id
     * @param $workflow
     * @param $transition
     * @return \Illuminate\Http\RedirectResponse
     */
    public function apply(Request $request){

        $class = $request->get('model');
        $id = $request->get('id');
        $workflowName = $request->get('workflow');
        $transition = $request->get('transition');
        $comment = $request->get('comment');

        $obj = app($class)->findOrFail($id);

        $this->authorize($workflowName.'_'.$transition, $obj);

        $workflow = Workflow::get($obj, $workflowName);
        if($workflow->can( $obj, $transition)){

            $obj = DB::transaction(function () use($workflow, $obj, $transition,$workflowName, $comment){
                $workflow->apply( $obj, $transition);
                $obj->save();

                $obj->workflowLogs()->create([
                    'user_id' => Auth::user()->id,
                    'workflow' => $workflowName,
                    'transition' => $transition,
                    'comment' => $comment,
                ]);
                return $obj;
            });

            return CoreWorkflow::getRedirect($workflowName, $transition)->with([
                'message' =>  CoreWorkflow::getMessage($workflowName, $transition, 'success'),
                'alert-type' => 'success',
            ]);
        }
        else{
            return CoreWorkflow::getRedirect($workflowName, $transition)->with([
                'message' => CoreWorkflow::getMessage($workflowName, $transition, 'failed'),
                'alert-type' => 'error',
            ]);
        }
    }

}