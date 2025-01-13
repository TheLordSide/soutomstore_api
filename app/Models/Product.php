<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

    // Spécifie les colonnes qui peuvent être massivement assignées
    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'category_id',
        'image_url',
    ];

    // Définir la relation avec la catégorie (Relation BelongsTo)
    public function category()
    {
        return $this->belongsTo(Categorie::class, 'category_id');
    }

}
