<?php

namespace App\Models\Painel;

use Illuminate\Database\Eloquent\Model;

class Agendamento extends Model
{
    protected $table = 'agendamento';
    
    //Lista branca - colunas que podem ser preenchidas
    protected $fillable = ['id','data_hora', 'posicao', 'motivo', 'monitorado_id'];
}
