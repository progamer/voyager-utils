<?php
/**
 * Created by PhpStorm.
 * User: issa
 * Date: 2019-02-15
 * Time: 17:34
 */

namespace Codept\Core;

use Illuminate\Support\Facades\Auth;
use Workflow;

class CoreWorkflow
{
    public function getMessage($workflow, $transition, $type)
    {
        return config("workflow.{$workflow}.messages.{$type}.{$transition}");
    }

    public function getRedirect($workflow, $transition)
    {
        $redirect = config("workflow.{$workflow}.transitions.{$transition}.redirect");
        return $redirect != null? redirect(route($redirect)) : back();
    }


    public function actions($object, array $workflowsToBeDisplayed = [], $display = 'array', $title =null)
    {
        $workflowsNames = [];
        //find workflows attached to this object
        foreach (config('workflow') as $key => $workflow) {

            //skip workflow if the user is looking for specific workflow
            if(count($workflowsToBeDisplayed)){
                if(!in_array($key, $workflowsToBeDisplayed)){
                    continue;
                }
            }

            if (in_array(get_class($object), config("workflow.$key.supports"))) {
                $workflowsNames[] = $key;
            }
        }

        $actions = [];

        foreach ($workflowsNames as $workflowName) {
            $workflow = Workflow::get($object, $workflowName);
            $transitions = $workflow->getEnabledTransitions($object);

            foreach ($transitions as $transition) {

                if (!Auth::user()->can($workflowName.'_'.$transition->getName('name'), $object)) {
                    continue;
                }


                $workflowAction = [
                    'url' => '#',
                    'class' => 'workflow-action',
                    'model' => get_class($object),
                    'id' => $object->id,
                    'workflow' => $workflowName,
                    'transition' => $transition->getName('name'),
                    'title' => $this->getMessage($workflowName, $transition->getName('name'), 'title'),
                ];

                //In this case, workflow will open popup for the user to give feedback, add attachments
                if(config("workflow.{$workflowName}.transitions.{$transition->getName('name')}.collectInput")){
                    $workflowAction['class'] .= ' collect-data';
                }

                if(config("workflow.{$workflowName}.transitions.{$transition->getName('name')}.confirm") != null){
                    $workflowAction['class'] .= ' confirm-action';
                    $workflowAction['data-confirm'] = __(config("workflow.{$workflowName}.transitions.{$transition->getName('name')}.confirm"));
                }

                $actions[$transition->getName('name')] = $workflowAction;
            }
        }

        if($display == 'array'){
            return $actions;
        }

        if($display == 'json'){
            return json_encode($actions);
        }

        return view($display)->with(compact('object', 'actions','title'));
    }
}
