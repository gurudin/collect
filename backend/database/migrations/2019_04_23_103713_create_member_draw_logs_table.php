<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMemberDrawLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_draw_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('fk_member_id')->nullable(false);
            $table->integer('fk_card_id')->default(0)->nullable(false)->comment('无卡片为0');
            $table->string('source', 50)->nullable(false)->comment('来源 base:基本抽奖 ad:广告 share:分享 login:整点登陆');
            $table->timestamp('created_at')->nullable(false);

            $table->index(['fk_member_id']);
            $table->index(['fk_card_id']);
            $table->index(['fk_card_id', 'created_at']);
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
        Schema::dropIfExists('member_draw_logs');
    }
}
