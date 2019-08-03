<?php

namespace Codept\Core\Http\Controllers;

use Codept\Core\Facades\CoreWorkflowFacade as CoreWorkflow;
use Codept\Core\Models\WorkflowLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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


        $attachments = [];
        $document_uploaded = $request->file('document_file');
        foreach ($document_uploaded as $document){
           $document_name = $document->getClientOriginalName();
           Storage::put('clientRequest'.DIRECTORY_SEPARATOR.$document_name,file_get_contents($document->getRealPath()));
           array_push($attachments, '{"download_link":"'.$document_name.'","original_name":"'.$document_name.'"}');
        }


        if($workflow->can( $obj, $transition)){

            $obj = DB::transaction(function () use($workflow, $obj, $transition,$workflowName, $comment,$attachments){
                $workflow->apply( $obj, $transition);
                $obj->save();

                $logs = $obj->workflowLogs()->create([
                    'user_id' => Auth::user()->id,
                    'workflow' => $workflowName,
                    'transition' => $transition,
                    'comment' => $comment,
                    'attachments' => implode(" , ",$attachments),
                ]);

                activity()
                    ->performedOn($obj)
                    ->causedBy(\Auth::user())
                    ->withProperties($logs)
                    ->log(':subject.title has been :properties.transition by :causer.name');


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
