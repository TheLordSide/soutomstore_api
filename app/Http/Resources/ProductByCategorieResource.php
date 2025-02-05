<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductByCategorieResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'category' => [
                'id'          => $this->id,
                'name'        => $this->name,
                'description' => $this->description,
            ],
            'products' => $this->products->map(function ($product) {
                return [
                    'id'          => $product->id,  // Utilisation correcte de $product
                    'name'        => $product->name,
                    'description' => $product->description,
                    'price'       => $product->price,
                    'stock'       => $product->stock,
                    'category_id' => $product->category_id,
                    'image_url'   => $product->image_url,
                    'created_at'  => $product->created_at ? $product->created_at->format('Y-m-d H:i:s') : null,
                    'updated_at'  => $product->updated_at ? $product->updated_at->format('Y-m-d H:i:s') : null,
                ];
            }),

        ];
    }
}
