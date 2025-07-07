<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cliente extends Authenticatable
{
    use Notifiable, HasFactory;

    protected $table = 'cliente';
    protected $primaryKey = 'id_cliente';
    public $timestamps = true;

    protected $fillable = [
        'nombre',
        'apellido',
        'dni',
        'telefono',
        'correo',
        'rol',
        'token',
    ];

    public function cuenta()
    {
        return $this->hasOne(Cuenta::class, 'id_cliente', 'id_cliente');
    }

    public function getAuthIdentifierName()
    {
        return 'correo';
    }

    public function getAuthPassword()
    {
        return $this->cuenta ? $this->cuenta->contrasena : null;
    }

    public function estaAutenticado()
    {
        return !empty($this->token);
    }
}
