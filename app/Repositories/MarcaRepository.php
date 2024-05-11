<?php
namespace App\Repositories;
use Illuminate\Database\Eloquent\Model;


class MarcaRepository {

    public function __construct(Model $model) {
        $this->model = $model;
    }

    public function selectRegistrosModelos($atributos) {
        $this->model = $this->model->with($atributos);
    }


    public function selectByFiltro($filtros) {
        $filtros = explode(';', $filtros);
        foreach($filtros as $key => $condicao) {
            $cond = explode(':',$condicao);
            $this->model = $this->model->where($cond[0], $cond[1], $cond[2]);
        }
    }

    public function selectAtributosMarca($atributos) {
        $this->model = $this->model->selectRaw($atributos)->get();
    }

    public function getResultado() {
        return $this->model->get();
    }

}

