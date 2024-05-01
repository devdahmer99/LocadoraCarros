<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modelo extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['marca_id', 'nome', 'imagem', 'portas', 'lugares', 'air_bag', 'abs'];

    public function rules() {
        return [
            'marca_id' => 'exists:marcas,id',
            'nome' => 'required|unique:modelos,nome,'.$this->id.'|min:3',
            'imagem' => 'required|file|mimes:png,jpeg,jpg',
            'portas' => 'required|integer|digits_between:1,5',
            'lugares' => 'required|integer|digits_between:1,4',
            'air_bag' => 'required|boolean',
            'abs' => 'required|boolean'
        ];
    }

    public function Marca() {
        return $this->belongsTo(Marca::class);
    }
}
