<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    // Spécifie la table associée au modèle (optionnel si le nom de la table correspond à la convention Laravel)
    protected $table = 'order_items';

    // Déclare les colonnes qui peuvent être massivement assignées
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
    ];

    /**
     * Relation avec la commande (Order) - Une ligne de commande appartient à une commande.
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    /**
     * Relation avec le produit (Product) - Une ligne de commande est liée à un produit.
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
