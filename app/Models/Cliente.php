<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Cliente extends Authenticatable
{
    use Notifiable;

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

    /**
     * Relación uno a uno con la tabla cuenta.
     */
    public function cuenta()
    {
        return $this->hasOne(Cuenta::class, 'id_cliente', 'id_cliente');
    }

    /**
     * Laravel usa este campo para identificar al usuario.
     */
    public function getAuthIdentifierName()
    {
        return 'correo';
    }

    /**
     * Devuelve la contraseña del usuario desde la tabla relacionada 'cuenta'.
     */
    public function getAuthPassword()
    {
        return $this->cuenta ? $this->cuenta->contrasena : null;
    }

    /**
     * Verifica si el cliente está autenticado (tiene token activo).
     */
    public function estaAutenticado()
    {
        return !empty($this->token);
    }
}