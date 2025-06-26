<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_producto';
    public $timestamps = false;

    protected $fillable = ['nombre', 'precio', 'descripcion', 'imagen', 'oferta'];

    // Relación: un producto tiene muchos lotes
    public function lotes()
    {
        return $this->hasMany(Lote::class, 'id_producto');
    }
}