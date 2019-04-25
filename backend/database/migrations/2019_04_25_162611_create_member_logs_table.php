<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMemberLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('fk_member_id')->nullable(false);
            $table->tinyInteger('log_type')->nullable(false)->comment('类别 1:抽取卡片 2:分享 3:广告 4:分解卡片 5:获取碎片 6:交换物品 7:整点登陆');
            /**
             * 扩展字段格式 json
             */
            $table->text('extend')->nullable(true)->comment('扩展字段');
            $table->timestamp('created_at')->nullable(false);
            
            $table->index(['fk_member_id']);
            $table->index(['fk_member_id', 'created_at']);

            $table->engine = 'MyISAM';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('member_logs');
    }
}
