<?php

namespace App\Http\Controllers\Api\V1;;

use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Http\Resources\CategorieResource;

class CategorieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Categorie::all();
    
        // Vérifier si la collection est vide
        if ($categories->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Aucune catégorie disponible.',
            ], 404); // Retourne un message avec un code 404 si aucune catégorie n'est trouvée
        }
    
        // Si des catégories existent, retourne la collection de catégories
        return CategorieResource::collection($categories);
    }
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         // Validation des données d'entrée
         $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:50',
        ]);

        // Vérification personnalisée pour éviter les doublons
        $existingCategorie = Categorie::where('name', $validated['name'])
                                  ->orWhere('description', $validated['description'])
                                  ->first();

        if ($existingCategorie) {
            return response()->json([
                'status' => 'echec.',
                'message' => 'Une catégorie avec ce nom ou cette description existe déjà.',
                'data' => $existingCategorie,
            ], 409); // Code 409 pour indiquer un conflit
        }

        // Création de la société si aucune duplication n'est trouvée
        $categorie = Categorie::create($validated);

        // Retourne la société nouvellement créée
        return new CategorieResource($categorie);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Récupère la catégorie par ID
        $categorie = Categorie::find($id);
    
        // Vérifie si la catégorie existe
        if (!$categorie) {
            return response()->json([
                'status' => 'error',
                'message' => 'Catégorie non trouvée.',
            ], 404); // 404 pour catégorie non trouvée
        }
    
        // Si la catégorie existe, renvoie les données avec un message de succès

        return CategorieResource::make($categorie);
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
        
        if (!$categorie) {
            return response()->json([
                'status' => 'error',
                'message' => 'Catégorie non trouvée.',
            ], 404); // Retourne une erreur 404 si la catégorie n'est pas trouvée
        }
    
        // Supprimer la catégorie
        $categorie->delete();
    
        // Retourne une réponse de succès après suppression
        return response()->json([
            'status' => 'success',
            'message' => 'Catégorie supprimée avec succès.',
        ], 200);
    }
    
}
