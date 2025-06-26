<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cuenta extends Model
{
    protected $table = 'cuenta';
    protected $primaryKey = 'id_cuenta';
    public $timestamps = false;

    protected $fillable = [
        'id_cliente',
        'contrasena',
        // puedes agregar más campos si los tienes
    ];

    /**
     * Relación inversa con Cliente.
     */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente', 'id_cliente');
    }
}