<?php

namespace App\Http\Controllers\Apis;

use App\Http\Controllers\Controller;
use App\Models\Vehicule;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use OpenApi\Attributes as OA;

class VehiculeController extends Controller
{
    #[OA\Get(
        path: '/api/vehicules',
        summary: 'Lister les vehicules',
        tags: ['Vehicules'],
        responses: [
            new OA\Response(response: 200, description: 'Liste des vehicules recuperee avec succes'),
        ]
    )]
    public function index()
    {
        $vehicules = Vehicule::with('client')->withCount('reparations')->latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'Liste des vehicules recuperee avec succes.',
            'data' => $vehicules,
        ], 200);
    }

    #[OA\Post(
        path: '/api/vehicules',
        summary: 'Creer un vehicule',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['client_id', 'immatriculation', 'marque', 'modele'],
                properties: [
                    new OA\Property(property: 'client_id', type: 'integer', example: 1),
                    new OA\Property(property: 'immatriculation', type: 'string', example: 'RC-1234-AB'),
                    new OA\Property(property: 'marque', type: 'string', example: 'Toyota'),
                    new OA\Property(property: 'modele', type: 'string', example: 'Corolla'),
                    new OA\Property(property: 'annee', type: 'integer', example: 2022),
                ]
            )
        ),
        tags: ['Vehicules'],
        responses: [
            new OA\Response(response: 201, description: 'Vehicule cree avec succes'),
            new OA\Response(response: 422, description: 'Erreur de validation'),
        ]
    )]
    public function store(Request $request)
    {
        $validated = $this->validateVehicule($request);

        $vehicule = Vehicule::create($validated)->load('client');

        return response()->json([
            'success' => true,
            'message' => 'Vehicule cree avec succes.',
            'data' => $vehicule,
        ], 201);
    }

    #[OA\Get(
        path: '/api/vehicules/{id}',
        summary: 'Afficher un vehicule',
        tags: ['Vehicules'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Identifiant du vehicule',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Vehicule recupere avec succes'),
            new OA\Response(response: 404, description: 'Vehicule introuvable'),
        ]
    )]
    public function show(string $id)
    {
        $vehicule = Vehicule::with(['client', 'reparations'])->find($id);

        if (! $vehicule) {
            return $this->notFoundResponse();
        }

        return response()->json([
            'success' => true,
            'message' => 'Vehicule recupere avec succes.',
            'data' => $vehicule,
        ], 200);
    }

    #[OA\Put(
        path: '/api/vehicules/{id}',
        summary: 'Modifier un vehicule',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['client_id', 'immatriculation', 'marque', 'modele'],
                properties: [
                    new OA\Property(property: 'client_id', type: 'integer', example: 1),
                    new OA\Property(property: 'immatriculation', type: 'string', example: 'RC-5678-CD'),
                    new OA\Property(property: 'marque', type: 'string', example: 'Hyundai'),
                    new OA\Property(property: 'modele', type: 'string', example: 'Tucson'),
                    new OA\Property(property: 'annee', type: 'integer', example: 2023),
                ]
            )
        ),
        tags: ['Vehicules'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Identifiant du vehicule',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Vehicule modifie avec succes'),
            new OA\Response(response: 404, description: 'Vehicule introuvable'),
            new OA\Response(response: 422, description: 'Erreur de validation'),
        ]
    )]
    #[OA\Patch(
        path: '/api/vehicules/{id}',
        summary: 'Modifier partiellement un vehicule',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'client_id', type: 'integer', example: 1),
                    new OA\Property(property: 'immatriculation', type: 'string', example: 'RC-5678-CD'),
                    new OA\Property(property: 'marque', type: 'string', example: 'Hyundai'),
                    new OA\Property(property: 'modele', type: 'string', example: 'Tucson'),
                    new OA\Property(property: 'annee', type: 'integer', example: 2023),
                ]
            )
        ),
        tags: ['Vehicules'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Identifiant du vehicule',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Vehicule modifie avec succes'),
            new OA\Response(response: 404, description: 'Vehicule introuvable'),
            new OA\Response(response: 422, description: 'Erreur de validation'),
        ]
    )]
    public function update(Request $request, string $id)
    {
        $vehicule = Vehicule::find($id);

        if (! $vehicule) {
            return $this->notFoundResponse();
        }

        $validated = $this->validateVehicule($request, $vehicule);
        $vehicule->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Vehicule modifie avec succes.',
            'data' => $vehicule->load('client'),
        ], 200);
    }

    #[OA\Delete(
        path: '/api/vehicules/{id}',
        summary: 'Supprimer un vehicule',
        tags: ['Vehicules'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Identifiant du vehicule',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Vehicule supprime avec succes'),
            new OA\Response(response: 404, description: 'Vehicule introuvable'),
        ]
    )]
    public function destroy(string $id)
    {
        $vehicule = Vehicule::find($id);

        if (! $vehicule) {
            return $this->notFoundResponse();
        }

        $vehicule->delete();

        return response()->json([
            'success' => true,
            'message' => 'Vehicule supprime avec succes.',
        ], 200);
    }

    private function validateVehicule(Request $request, ?Vehicule $vehicule = null): array
    {
        try {
            return $request->validate([
                'client_id' => ['required', 'exists:clients,id'],
                'immatriculation' => [
                    'required',
                    'string',
                    'max:30',
                    Rule::unique('vehicules')->ignore($vehicule),
                ],
                'marque' => ['required', 'string', 'max:80'],
                'modele' => ['required', 'string', 'max:80'],
                'annee' => ['nullable', 'integer', 'min:1980', 'max:' . (date('Y') + 1)],
            ]);
        } catch (ValidationException $exception) {
            throw $exception;
        }
    }

    private function notFoundResponse()
    {
        return response()->json([
            'success' => false,
            'message' => 'Vehicule introuvable.',
        ], 404);
    }
}
