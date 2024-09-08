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
    public function up():void
    {
        Schema::create('links', function (Blueprint $table) {
            $table->engine = 'InnoDB';//添加引擎定义命令
            $table->id();
            $table->string('title')->comment('资源的描述')->index();
            $table->string('link')->comment('资源的链接')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down():void
    {
        Schema::dropIfExists('links');
    }
};
