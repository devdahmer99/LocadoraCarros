<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use App\Models\Marca;
use Illuminate\Http\Request;


class MarcaController extends Controller
{
    public function __construct(Marca $marca) {
        $this->marca = $marca;
    }


    public function index(Request $request)
    {
        $marcas = array();

        if($marcas == '' || $marcas == '[]') {
            return response()->json(['erro' => 'Nenhum registro encontrado.'], 404);
        }

        /*
        Eu apenas criei essas validações e implementação para fins de uso futuro
        no momento este endpoint ira retornar todos os modelos e as suas associações
        com as devidas marcas pelo seu ID.
        Mas caso seja necessário o uso, já esta funcional.
        */

        if($request->has('atributos_modelos')) {
            $atributos_modelos = $request->atributos_modelos;
            $marcas = $this->marca->with('modelos:id,'.$atributos_modelos);
        } else {
            $marcas = $this->marca->with('modelos');
        }

        if($request->has('filtro')) {
            $filtros = explode(';', $request->filtro);
            foreach($filtros as $key => $condicao) {
                $cond = explode(':',$condicao);
                $marcas = $marcas->where($cond[0], $cond[1], $cond[2]);
            }
        }

        if($request->has('atributos')) {
            $atributos = $request->atributos;
            $marcas = $marcas->selectRaw($atributos)->get();
        } else {
            $marcas = $marcas->get();
        };

        return response()->json($marcas, 200);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $request->validate($this->marca->rules(), $this->marca->feedback());

        $imagem = $request->file('imagem');
        $image_urn = $imagem->store('imagens', 'public');

        $marca = $this->marca->create([
            'nome' => $request->nome,
            'imagem' => $image_urn
        ]);

       
        return response()->json($marca, 201);  
    }

    /**
     * @param Integer
     * Display the specified resource.
     */
    public function show($id)
    {
        $marca = $this->marca->with('modelos')->find($id);
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
      
        if($request->method() === 'PATCH') {
            $regraDinamica = array();

            foreach($marca->rules() as $indice => $regra) {
                if(array_key_exists($indice, $request->all())) {
                    $regraDinamica[$indice] = $regra;
                }
            }
            $request->validate($regraDinamica, $marca->feedback());
        } else {
            $request->validate($marca->rules(), $marca->feedback());
        }    

        //Remove o arquivo antigo, caso um novo arquivo tenha sido upado no request
        if($request->file('imagem')) {
            Storage::disk('public')->delete($marca->imagem);
        }

        $imagem = $request->file('imagem');
        $image_urn = $imagem->store('imagens', 'public');

        $marca->fill($request->all());
        $marca->imagem = $image_urn;
        $marca->save();

        return response()->json($marca, 200);
    }

    public function destroy($id)
    {
        $marca = $this->marca->find($id);
        if($marca === null) {
            return response()->json(['erro' => 'Impossível realizar a exclusão. O registro solicitado não foi encontrado.'], 404);
        }

        //Remove o arquivo antigo, caso um novo arquivo tenha sido upado no request
        Storage::disk('public')->delete($marca->imagem);

        $marca->delete();
        return response()->json(['msg' => 'Marca deletado com Suscesso!'], 200);
    }
}
