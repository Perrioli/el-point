<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemInventario extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_stock';

    protected $fillable = [
        'producto_stock',
        'cantidad',
        'costo',
    ];
}