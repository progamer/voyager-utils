<?php
$namespacePrefix = '\\Codept\\Core\\Http\\Controllers\\';

Route::group(['middleware' => ['web']], function () use($namespacePrefix) {

    Route::put('apply-workflow', [
        'uses' => $namespacePrefix.'WorkflowController@apply',
        'as' => 'apply-workflow'
    ]);

    Route::group(['middleware' => ['auth:api']], function () use($namespacePrefix) {
        Route::put('api/apply-workflow', [
            'uses' => $namespacePrefix.'WorkflowController@apply',
            'as' => 'apply-workflow-api'
        ]);

    });
});

