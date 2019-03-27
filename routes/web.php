<?php
$namespacePrefix = '\\Codept\\Core\\Http\\Controllers\\';

Route::group(['middleware' => ['web','verified']], function () use($namespacePrefix){

    Route::put('apply-workflow',[
        'uses' => $namespacePrefix. 'WorkflowController@apply',
        'as' => 'apply-workflow'
    ]);
});


