<?php

namespace App\Models\Painel;

use Illuminate\Database\Eloquent\Model;

class Monitorado extends Model
{
    protected $table = 'monitorado';

    //Lista branca - colunas que podem ser preenchidas
    protected $fillable = ['id', 'id_monitorado', 'nome'];

}
