<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrderItemsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $order_item = OrderItem::all();

        // Vérifier si la collection est vide
        if ($order_item->isEmpty()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Aucun acticle dans la commande.',
                'data' => []
            ], 404); // Retourne un message avec un code 404 si aucune commande n'est trouvée
        }

        // Si des commandes existent, retourne la collection de commandes
        return OrderItem::collection($order_item);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
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
