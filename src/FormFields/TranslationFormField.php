<?php
/**
 * Created by PhpStorm.
 * User: issa
 * Date: 2019-02-04
 * Time: 18:31
 */

namespace Codept\Core\FormFields;


use TCG\Voyager\FormFields\AbstractHandler;

class TranslationFormField extends AbstractHandler
{
    protected $codename = 'translate';

    public function createContent($row, $dataType, $dataTypeContent, $options)
    {
        return view('codept/core::form_fields.translate', [
            'row' => $row,
            'options' => $options,
            'dataType' => $dataType,
            'dataTypeContent' => $dataTypeContent
        ]);
    }
}