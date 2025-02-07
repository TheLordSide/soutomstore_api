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
                'data'=>[]
            ], 404); // Retourne un message avec un code 404 si aucune commande n'est trouvée
        }

        // Si des commandes existent, retourne la collection de commandes
       return response()->json([
        'status'=> 'succes',
        'message'=> 'Données des commandes récupérée avec succès.',
        'data'=> OrderResource::collection($order)
       ],200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
          // Validation des données d'entrée
          $validated = $request->validate([
            'user_id'      => 'required|exists:users,id',
            'total_amount' => 'required|numeric|min:0',
            'status'       => 'required|string',
        ]);

        
        //ajout de la commande
        $order = Order::create($validated);

        return response()->json([
            'status'  => 'success',
            'message' => 'La catégorie a été ajoutée avec succès',
            'data'    => new OrderResource($order),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Récupère la commande par ID
        $order = Order::find($id);

        // Vérifie si la commande existe
        if (! $order) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Commande non trouvé.',
                'data' => []
            ], 404); // 404 pour commande non trouvée
        }

        // Si la commande existe, renvoie les données avec un message de succès

        return response()->json([
            'status'=> 'succes',
            'message'=> 'Données des commandes récupérée avec succès.',
            'data'=> new OrderResource($order)
           ],201);
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
        $order = Order::find($id);

        if(!$order){

            return response()->json([
                'status'=>'error',
                'message'=>'Commande non trouvée',
                'data'=>[],
            ],404);
        }

        // suppression de l'order
        $order->delete();
        
        // message en cas de suppression reussie
        return response()->json([
            'status'=>'succes',

        ],200);
    }
}
