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
        Schema::create('logs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('app_id')->default(0)->unsigned()->nullable();
            $table->bigInteger('user_id')->default(0)->unsigned()->nullable();
            $table->bigInteger('role_id')->default(0)->nullable();
            $table->bigInteger('brand_id')->default(0)->unsigned()->nullable();
            $table->ipAddress('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->string('route')->nullable();
            $table->string('method')->nullable();
            $table->string('action')->nullable();
            $table->json('objects')->nullable();
            $table->timestamp('login_at')->nullable();
            $table->timestamp('logout_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('logs');
    }
};
