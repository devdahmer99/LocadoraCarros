<?php

namespace App\Http\Controllers;

use App\Models\Modelo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ModeloController extends Controller
{
    public function __construct(Modelo $modelo) {
        $this->modelo = $modelo;
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $modelos = $this->modelo->all();
        if($modelos == '' || $modelos == '[]') {
            return response()->json(['erro' => 'Nenhum registro encontrado.'], 404);
        }
        return response()->json($modelos, 200);
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
        $request->validate($this->modelo->rules());

        $imagem = $request->file('imagem');
        $image_urn = $imagem->store('imagens/modelos', 'public');

        $modelo = $this->modelo->create([
            'marca_id' => $request->marca_id,
            'nome' => $request->nome,
            'imagem' => $image_urn,
            'portas' => $request->portas,
            'lugares' => $request->lugares,
            'air-bag' => $request->air_bag,
            'abs' => $request->abs
        ]);

       
        return response()->json($modelo, 201);  
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $modelo = $this->modelo->find($id);
        if($modelo === null || $modelo == '' ) {
            return response()->json(['Erro' => 'Registro não encontrado.'], 404);
        }

        return response()->json($modelo, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Modelo $modelo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Modelo $modelo)
    {
        $modelo = $this->modelo->find($id);

        if($modelo === null) {
            return response()->json(['erro' => 'Impossível realizar a atualização. O registro solicitado não foi encontrado.'], 404);
        }
      
        if($request->method() === 'PATCH') {
            $regraDinamica = array();

            foreach($modelo->rules() as $indice => $regra) {
                if(array_key_exists($indice, $request->all())) {
                    $regraDinamica[$indice] = $regra;
                }
            }
            $request->validate($regraDinamica, $modelo->feedback());
        } else {
            $request->validate($modelo->rules(), $modelo->feedback());
        }    

        //Remove o arquivo antigo, caso um novo arquivo tenha sido upado no request
        if($request->file('imagem')) {
            Storage::disk('public')->delete($modelo->imagem);
        }

        $imagem = $request->file('imagem');
        $image_urn = $imagem->store('imagens/modelos', 'public');

        $modelo->update([
            'marca_id' => $request->marca_id,
            'nome' => $request->nome,
            'imagem' => $image_urn,
            'portas' => $request->portas,
            'lugares' => $request->lugares,
            'air-bag' => $request->air_bag,
            'abs' => $request->abs
        ]);

        return response()->json($modelo, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $modelo = $this->modelo->find($id);
        if($modelo === null) {
            return response()->json(['erro' => 'Impossível realizar a exclusão. O registro solicitado não foi encontrado.'], 404);
        }

        //Remove o arquivo antigo, caso um novo arquivo tenha sido upado no request
        Storage::disk('public')->delete($modelo->imagem);

        $modelo->delete();
        return response()->json(['msg' => 'Registro deletado com Suscesso!'], 200);
    }
}
