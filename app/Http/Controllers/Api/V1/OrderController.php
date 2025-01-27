<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $order = Order::all();

        // Vérifier si la collection est vide
        if ($order->isEmpty()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Aucune commande effectuee.',
            ], 404); // Retourne un message avec un code 404 si aucune commande n'est trouvée
        }

        // Si des commandes existent, retourne la collection de commandes
        return Order::collection($order);
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
    public function show($id)
    {
        // Récupère la commande par ID
        $product = Order::find($id);

        // Vérifie si la commande existe
        if (! $product) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Commande non trouvé.',
                'data' => []
            ], 404); // 404 pour commande non trouvée
        }

        // Si la commande existe, renvoie les données avec un message de succès

        return OrderResource::make($product);
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
