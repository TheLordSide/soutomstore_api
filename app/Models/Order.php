<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    // Spécifie la table associée au modèle (optionnel si le nom de la table suit les conventions Laravel)
    protected $table = 'orders';

    // Les colonnes qui peuvent être massivement assignées
    protected $fillable = [
        'user_id',
        'total_amount',
        'status',
    ];

    /**
     * Relation avec l'utilisateur (User) - Une commande appartient à un utilisateur.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relation avec les lignes de commande (OrderItem) - Une commande a plusieurs lignes de commande.
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    /**
     * Accesseur pour formater le montant total avec deux décimales.
     */
    public function getFormattedTotalAmountAttribute()
    {
        return number_format($this->total_amount, 2, '.', ',');
    }

    /**
     * Définir l'attribut `status` pour s'assurer qu'il est toujours en minuscule.
     */
    public function setStatusAttribute($value)
    {
        $this->attributes['status'] = strtolower($value);
    }
}
