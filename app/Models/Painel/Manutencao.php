<?php

namespace App\Models\Painel;

use Illuminate\Database\Eloquent\Model;

class Manutencao extends Model
{
    protected $table = 'manutencao';

    //Lista branca - colunas que podem ser preenchidas
    protected $fillable = ['cinta', 'carregador', 'tornozeleira', 'compareceu', 'agendamento_id'];
}
