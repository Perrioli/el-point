<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_pedido';

    protected $fillable = [
        'caja_id',
        'numero_pedido',
        'numero_caja',
        'persona',
        'status',
        'precio_total',
        'comentarios',
        'hora_entrega',
        'metodo_pago',
    ];

    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'pedido_producto', 'pedido_id', 'producto_id')
            ->withPivot('cantidad', 'precio_unitario');
    }

    public function caja()
    {
        return $this->belongsTo(Caja::class);
    }
}
