<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMemberCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_cards', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('fk_member_id')->nullable(false);
            $table->integer('fk_card_id')->nullable(false);
            $table->tinyInteger('delete')->default(0)->nullable(false)->comment('是否删除 0:否 1:是');
            $table->string('delete_remark')->comment('删除原因');
            $table->timestamps();

            $table->index(['fk_member_id', 'delete']);
            $table->index(['fk_member_id', 'fk_card_id', 'delete']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('member_cards');
    }
}
