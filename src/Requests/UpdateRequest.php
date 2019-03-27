<?php

namespace Codept\Core\Requests;

use Illuminate\Foundation\Http\FormRequest;
use TCG\Voyager\Facades\Voyager;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        list($rules, $messages, $customAttributes) = $this->getBreadValidation();
        return $rules;
    }

    /**
     *  Return Voyager datatype name ex: users
     *
     * @return string
     */
    protected function getDataTypeName()
    {
        return '';
    }

    public function getUpdatedFields()
    {
        return [];
    }

    public function getBreadValidation()
    {

        $dataType = Voyager::model('DataType')->where('slug', '=', $this->getDataTypeName())->first();

        $rowsQuery = $dataType->editRows();
        if (! empty($this->getUpdatedFields())) {
            $rowsQuery->whereIn('field', $this->getUpdatedFields());
        }

        $id = $this->getObject()->id;
        $name = $dataType->name;
        $rows = $rowsQuery->get();

        $rules = [];
        $messages = [];
        $customAttributes = [];
        $is_update = $name && $id;

        $fieldsWithValidationRules = $this->getFieldsWithValidationRules($rows);

        foreach ($fieldsWithValidationRules as $field) {
            $fieldRules = $field->details->validation->rule;
            $fieldName = $field->field;

            // Show the field's display name on the error message
            if (! empty($field->display_name)) {
                $customAttributes[$fieldName] = $field->display_name;
            }

            // Get the rules for the current field whatever the format it is in
            $rules[$fieldName] = is_array($fieldRules) ? $fieldRules : explode('|', $fieldRules);

            // Fix Unique validation rule on Edit Mode
            if ($is_update) {
                foreach ($rules[$fieldName] as &$fieldRule) {
                    if (strpos(strtoupper($fieldRule), 'UNIQUE') !== false) {
                        $fieldRule = \Illuminate\Validation\Rule::unique($name)->ignore($id);
                    }
                }
            }

            // Set custom validation messages if any
            if (! empty($field->details->validation->messages)) {
                foreach ($field->details->validation->messages as $key => $msg) {
                    $messages["{$fieldName}.{$key}"] = $msg;
                }
            }
        }

        return [$rules, $messages, $customAttributes];
    }

    protected function getFieldsWithValidationRules($fieldsConfig)
    {
        return $fieldsConfig->filter(function ($value) {
            if (empty($value->details)) {
                return false;
            }

            return ! empty($value->details->validation->rule);
        });
    }

    /**
     * Return instance of object being updated, ex: $user
     *
     * @return mixed
     */
    protected function getObject()
    {
        return null;
    }

    public function messages()
    {
        list($rules, $messages, $customAttributes) = $this->getBreadValidation();

        return $messages;
    }
}
