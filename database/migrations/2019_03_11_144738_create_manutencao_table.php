<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateManutencaoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manutencao', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('cinta');
            $table->boolean('carregador');
            $table->boolean('tornozeleira');
            $table->enum('compareceu', ['sim', 'não', 'reagendou']);
            $table->integer('agendamento_id')->unsigned();
            $table->foreign('agendamento_id')->references('id')->on('agendamento');
            $table->timestamps();
            $table->dropForeign(['agendamento_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('manutencao');
    }
}
