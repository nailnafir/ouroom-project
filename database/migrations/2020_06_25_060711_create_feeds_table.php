<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_feed', function (Blueprint $table) {
            $table->bigIncrements('id', 20);
            // $table->unsignedBigInteger('class_id');
            $table->string('judul');
            $table->string('kategori');
            $table->text('detail')->nullable();
            $table->string('file')->nullable();
            $table->date('deadline');
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));

            // $table->foreign('class_id')
            //     ->references('id')
            //     ->on('tbl_class')
            //     ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_feed');
    }
}
