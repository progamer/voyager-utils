<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkflowLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workflow_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');

            $table->unsignedInteger('workflowable_id');
            $table->string('workflowable_type');

            $table->string('workflow');
            $table->string('transition');

            $table->text('comment')->nullable();
            $table->jsonb('attachments')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('workflow_logs');
    }
}
