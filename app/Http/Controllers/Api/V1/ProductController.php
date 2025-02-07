<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $product = Product::all();

        // Vérifier si la collection est vide
        if ($product->isEmpty()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Aucun produit disponible.',
                'data'    => [],
            ], 404); // Retourne un message avec un code 404 si aucun produit n'est trouvé
        }

        // Si des produits existent, retourne la collection de produits
        return response()->json([
            'status'  => 'succes',
            'message' => 'Données des produits récupérées avec succès.',
            'data'    => ProductResource::collection($product), // Utilisez collection ici pour transformer la collection
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validation des données d'entrée

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'image_url'   => 'nullable|url',
        ]);

        // Vérification personnalisée pour éviter les doublons
        $existingProduct = Product::where('name', $validated['name'])
            ->orWhere('description', $validated['description'])
            ->first();

        if ($existingProduct) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Un produit avec ce nom ou cette description existe déjà.',
                'data'    => $existingProduct,
            ], 409); // Code 409 pour indiquer un conflit
        }

        // Création du produit si aucune duplication n'est trouvée
        $product = Product::create($validated);

        // Retourne le produit nouvellement créé
        return response()->json([
            'status'  => 'succes',
            'message' => 'Le produit a été ajoutée avec succès',
            'data'    => new ProductResource($product),
        ], 201); // Code 201 pour indiquer une ressource créée
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Récupère la catégorie par ID
        $product = Product::find($id);

        // Vérifie si la catégorie existe
        if (! $product) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Produit non trouvé.',
                'data'    => [],
            ], 404); // 404 pour catégorie non trouvée
        }

        // Si la catégorie existe, renvoie les données avec un message de succès
        return response()->json([
            'status'  => 'succes',
            'message' => 'Données du produit récupérée avec succès.',
            'data'    => new ProductResource($product),
        ]);
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
    public function destroy($id)
    {
        // Vérifier si le produit existe
        $product = Product::find($id);

        if (! $product) {
            return response()->json([
                'status'  => 'error',
                'message' => 'produit non trouvé.',
            ], 404); // Retourne une erreur 404 si le produit n'est pas trouvé
        }

        // Supprimer le produit
        $product->delete();

        // Retourne une réponse de succès après suppression
        return response()->json([
            'status'  => 'succes',
            'message' => 'produit supprimé avec succès.',
        ], 200);
    }
}
