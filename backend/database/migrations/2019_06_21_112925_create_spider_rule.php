<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpiderRule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('spider_rule', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id')->nullable(false)->default(0)->comment('父类别');
            $table->string('name', 100)->nullable(false)->comment('名称');
            $table->string('url', 255)->nullable(true)->comment('采集url');
            $table->tinyInteger('type')->nullable(false)->comment('1:html 2:json');
            $table->string('slice')->nullable(true)->comment('切片选择器');
            $table->text('rule')->nullable(false)->comment('采集规则');
            $table->text('filed_rule')->nullable(true)->comment('字段采集规则');
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
        Schema::dropIfExists('spider_rule');
    }
}
