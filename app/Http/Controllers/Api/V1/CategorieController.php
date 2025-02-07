<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\CategorieResource;
use App\Http\Resources\ProductByCategorieResource;
use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class CategorieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Categorie::all();

        // Vérifier si la collection est videsoutomstorea
        if ($categories->isEmpty()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Aucune catégorie disponible.',
                'data'    => [], // Dans ce cas, on retourne un tableau vide pour 'data'
            ], 404);         // Retourne un message avec un code 404 si aucune catégorie n'est trouvée
        }

        // Si des catégories existent, retourne la collection de catégories dans 'data'
        return response()->json([
            'status'  => 'success',
            'message' => 'Données des catégories récupérées avec succès.',
            'data'    => CategorieResource::collection($categories), // Les catégories sont transformées par la resource
        ], 200);                                                 // Code 200 pour une récupération réussie des données
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validation des données d'entrée
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string|max:50',
        ]);

        // Vérification personnalisée pour éviter les doublons
        $existingCategorie = Categorie::where('name', $validated['name'])
            ->orWhere('description', $validated['description'])
            ->first();

        if ($existingCategorie) {
            return response()->json([
                'status'  => 'conflit.',
                'message' => 'Une catégorie avec ce nom ou cette description existe déjà.',
                'data'    => $existingCategorie,
            ], 409); // Code 409 pour indiquer un conflit
        }

        // Création de la société si aucune duplication n'est trouvée
        $categorie = Categorie::create($validated);

        return response()->json([
            'status'  => 'success',
            'message' => 'La catégorie a été ajoutée avec succès',
            'data'    => new CategorieResource($categorie),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Récupère la catégorie par ID
        $categorie = Categorie::find($id);

        // Vérifie si la catégorie existe
        if (! $categorie) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Catégorie non trouvée.',
                'data'    => [],
            ], 404); // 404 pour catégorie non trouvée
        }

        // Si la catégorie existe, renvoie les données avec un message de succès

        return response()->json([
            'status'  => 'succes',
            'message' => 'Données de la catégorie récupérée avec succès.',
            'data'    => new CategorieResource($categorie),
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
        // Vérifier si la catégorie existe
        $categorie = Categorie::find($id);

        if (! $categorie) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Catégorie non trouvée.',
            ], 404); // Retourne une erreur 404 si la catégorie n'est pas trouvée
        }

        // Supprimer la catégorie
        $categorie->delete();

        // Retourne une réponse de succès après suppression
        return response()->json([
            'status'  => 'succes',
            'message' => 'Catégorie supprimée avec succès.',
        ], 200);
    }

    public function getProductsByCategory($id)
    {
        // Récupère la catégorie par son ID
        $categorie = Categorie::with('products')->find($id);

        // Vérifie si la catégorie existe
        if (! $categorie) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Catégorie non trouvée.',
            ], 404);
        }

        // Vérifie si la catégorie a des produits associés
        if ($categorie->products->isEmpty()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Aucun produit trouvé pour cette catégorie.',
            ], 404);
        }

        // Retourne les produits liés à la catégorie
        return response()->json([
            'status'  => 'succes',
            'message' => 'Produits trouvés pour la catégorie.',
            'data'    => new ProductByCategorieResource($categorie),
        ], 200);
    }

}
