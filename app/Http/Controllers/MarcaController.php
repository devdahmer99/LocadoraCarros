<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use Illuminate\Http\Request;

class MarcaController extends Controller
{
    public function __construct(Marca $marca) {
        $this->marca = $marca;
    }


    public function index()
    {
        $marcas = $this->marca->all();
        if($marcas == '' || $marcas == '[]') {
            return response()->json(['erro' => 'Nenhum registro encontrado.'], 404);
        }
        //$marcas = Marca::all();
        return response()->json($marcas, 200);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $request->validate($this->marca->rules(), $this->marca->feedback());

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
            return response()->json(['Erro' => 'Registro não encontrado.'], 404);
        }

        return response()->json($marca, 200);
    }

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
            return response()->json(['erro' => 'Impossível realizar a atualização. O registro solicitado não foi encontrado.'], 404);
        }
        $request->validate($marca->rules(), $marca->feedback());
        $marca->update($request->all());
        return response()->json($marca, 200);
    }

    public function destroy($id)
    {
        $marca = $this->marca->find($id);
        if($marca === null) {
            return response()->json(['erro' => 'Impossível realizar a exclusão. O registro solicitado não foi encontrado.'], 404);
        }
        $marca->delete();
        return response()->json(['msg' => 'Registro deletado com Suscesso!'], 200);
    }
}
