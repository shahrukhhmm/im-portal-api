<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entity_role', function (Blueprint $table) {
            $table->bigInteger('application_id')->unsigned()->nullable();
            $table->foreign('application_id')->references('id')->on('applications');
            $table->bigInteger('role_id')->unsigned()->nullable();
            $table->foreign('role_id')->references('id')->on('roles');
            $table->bigInteger('entity_id')->unsigned()->nullable();
            $table->foreign('entity_id')->references('id')->on('entities');
            $table->integer('action_id')->unsigned()->nullable();
            $table->foreign('action_id')->references('id')->on('actions');
            $table->unique(['role_id', 'entity_id', 'action_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('entity_roles');
    }
};
