<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use Illuminate\Http\Request;

class MarcaController extends Controller
{
    public function __construct(Marca $marca) {
        $this->marca = $marca;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $marcas = $this->marca->all();
        if($marcas == '' || $marcas == '[]') {
            return ['erro' => 'Nenhuma marca encontrada!'];
        }
        //$marcas = Marca::all();
        return $marcas;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $marca = $this->marca->create($request->all());
        return response()->json($marca, 201);  
    }

    /**
     * @param Integer
     * Display the specified resource.
     */
    public function show($id)
    {
        $marca = $this->marca->find($id);
        if($marca === null || $marca == '' ) {
            return response()->json(['Erro' => 'Recurso Indisponível no momento!'], 404);
        }

        return $marca;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Marca $marca)
    {
        //
    }

    /**
     * @param integer
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $marca = $this->marca->find($id);
        if($marca === null) {
            return response()->json(['erro' => 'Impossível realizar a atualização. O recurso solicitado não foi encontrado.'], 404);
        }
        $marca->update($request->all());
        return $marca;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $marca = $this->marca->find($id);
        if($marca === null) {
            return response()->json(['erro' => 'Impossível realizar a exclusão. O recurso solicitado não foi encontrado.'], 404);
        }
        $marca->delete();
        return ['msg' => 'Marca Deletada com Suscesso!'];
    }
}
