<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgendamentoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agendamento', function (Blueprint $table) {
            $table->increments('id')->unique();
            $table->dateTime('data_hora');
            $table->integer('posicao');
            $table->enum('motivo', ['Tornozeleira rompida', 'Aparelho não liga', 'Troca de carregador', 'Sem comunicação', 'Sem sinal GPS', 'Não segura carga', 'Reativação', 'Devolução', 'Pendente']);
            $table->integer('monitorado_id')->unsigned();
            $table->foreign('monitorado_id')->references('id')->on('monitorado');
            $table->timestamps();
            $table->dropForeign(['monitorado_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agendamento');
    }
}
