<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order_Item;

class OrderItemsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $order_item = Order_Item::all();
    
        // Vérifier si la collection est vide
        if ($order_item->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Aucune catégorie disponible.',
            ], 404); // Retourne un message avec un code 404 si aucune catégorie n'est trouvée
        }
    
        // Si des catégories existent, retourne la collection de catégories
        return Order_Item::collection($order_item);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
