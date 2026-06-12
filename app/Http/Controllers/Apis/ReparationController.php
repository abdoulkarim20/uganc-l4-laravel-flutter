<?php

namespace App\Http\Controllers\Apis;

use App\Http\Controllers\Controller;
use App\Models\Reparation;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use OpenApi\Attributes as OA;

class ReparationController extends Controller
{
    #[OA\Get(
        path: '/api/reparations',
        summary: 'Lister les reparations',
        tags: ['Reparations'],
        responses: [
            new OA\Response(response: 200, description: 'Liste des reparations recuperee avec succes'),
        ]
    )]
    public function index()
    {
        $reparations = Reparation::with(['vehicule.client', 'mecanicien'])
            ->latest('date_reparation')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Liste des reparations recuperee avec succes.',
            'data' => $reparations,
        ], 200);
    }

    #[OA\Post(
        path: '/api/reparations',
        summary: 'Creer une reparation',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['vehicule_id', 'mecanicien_id', 'date_reparation', 'description', 'cout', 'statut'],
                properties: [
                    new OA\Property(property: 'vehicule_id', type: 'integer', example: 1),
                    new OA\Property(property: 'mecanicien_id', type: 'integer', example: 1),
                    new OA\Property(property: 'date_reparation', type: 'string', format: 'date', example: '2026-06-12'),
                    new OA\Property(property: 'description', type: 'string', example: 'Vidange moteur et controle des freins'),
                    new OA\Property(property: 'cout', type: 'number', format: 'float', example: 250000),
                    new OA\Property(
                        property: 'statut',
                        type: 'string',
                        enum: ['planifiee', 'en_cours', 'terminee', 'annulee'],
                        example: 'planifiee'
                    ),
                ]
            )
        ),
        tags: ['Reparations'],
        responses: [
            new OA\Response(response: 201, description: 'Reparation creee avec succes'),
            new OA\Response(response: 422, description: 'Erreur de validation'),
        ]
    )]
    public function store(Request $request)
    {
        $validated = $this->validateReparation($request);

        $reparation = Reparation::create($validated)->load(['vehicule.client', 'mecanicien']);

        return response()->json([
            'success' => true,
            'message' => 'Reparation creee avec succes.',
            'data' => $reparation,
        ], 201);
    }

    #[OA\Get(
        path: '/api/reparations/{id}',
        summary: 'Afficher une reparation',
        tags: ['Reparations'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Identifiant de la reparation',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Reparation recuperee avec succes'),
            new OA\Response(response: 404, description: 'Reparation introuvable'),
        ]
    )]
    public function show(string $id)
    {
        $reparation = Reparation::with(['vehicule.client', 'mecanicien'])->find($id);

        if (! $reparation) {
            return $this->notFoundResponse();
        }

        return response()->json([
            'success' => true,
            'message' => 'Reparation recuperee avec succes.',
            'data' => $reparation,
        ], 200);
    }

    #[OA\Put(
        path: '/api/reparations/{id}',
        summary: 'Modifier une reparation',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['vehicule_id', 'mecanicien_id', 'date_reparation', 'description', 'cout', 'statut'],
                properties: [
                    new OA\Property(property: 'vehicule_id', type: 'integer', example: 1),
                    new OA\Property(property: 'mecanicien_id', type: 'integer', example: 1),
                    new OA\Property(property: 'date_reparation', type: 'string', format: 'date', example: '2026-06-13'),
                    new OA\Property(property: 'description', type: 'string', example: 'Remplacement des plaquettes de frein'),
                    new OA\Property(property: 'cout', type: 'number', format: 'float', example: 300000),
                    new OA\Property(
                        property: 'statut',
                        type: 'string',
                        enum: ['planifiee', 'en_cours', 'terminee', 'annulee'],
                        example: 'en_cours'
                    ),
                ]
            )
        ),
        tags: ['Reparations'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Identifiant de la reparation',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Reparation modifiee avec succes'),
            new OA\Response(response: 404, description: 'Reparation introuvable'),
            new OA\Response(response: 422, description: 'Erreur de validation'),
        ]
    )]
    #[OA\Patch(
        path: '/api/reparations/{id}',
        summary: 'Modifier partiellement une reparation',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'vehicule_id', type: 'integer', example: 1),
                    new OA\Property(property: 'mecanicien_id', type: 'integer', example: 1),
                    new OA\Property(property: 'date_reparation', type: 'string', format: 'date', example: '2026-06-13'),
                    new OA\Property(property: 'description', type: 'string', example: 'Remplacement des plaquettes de frein'),
                    new OA\Property(property: 'cout', type: 'number', format: 'float', example: 300000),
                    new OA\Property(
                        property: 'statut',
                        type: 'string',
                        enum: ['planifiee', 'en_cours', 'terminee', 'annulee'],
                        example: 'terminee'
                    ),
                ]
            )
        ),
        tags: ['Reparations'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Identifiant de la reparation',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Reparation modifiee avec succes'),
            new OA\Response(response: 404, description: 'Reparation introuvable'),
            new OA\Response(response: 422, description: 'Erreur de validation'),
        ]
    )]
    public function update(Request $request, string $id)
    {
        $reparation = Reparation::find($id);

        if (! $reparation) {
            return $this->notFoundResponse();
        }

        $validated = $this->validateReparation($request);
        $reparation->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Reparation modifiee avec succes.',
            'data' => $reparation->load(['vehicule.client', 'mecanicien']),
        ], 200);
    }

    #[OA\Delete(
        path: '/api/reparations/{id}',
        summary: 'Supprimer une reparation',
        tags: ['Reparations'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Identifiant de la reparation',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Reparation supprimee avec succes'),
            new OA\Response(response: 404, description: 'Reparation introuvable'),
        ]
    )]
    public function destroy(string $id)
    {
        $reparation = Reparation::find($id);

        if (! $reparation) {
            return $this->notFoundResponse();
        }

        $reparation->delete();

        return response()->json([
            'success' => true,
            'message' => 'Reparation supprimee avec succes.',
        ], 200);
    }

    private function validateReparation(Request $request): array
    {
        try {
            return $request->validate([
                'vehicule_id' => ['required', 'exists:vehicules,id'],
                'mecanicien_id' => ['required', 'exists:mecaniciens,id'],
                'date_reparation' => ['required', 'date'],
                'description' => ['required', 'string', 'max:1200'],
                'cout' => ['required', 'numeric', 'min:0'],
                'statut' => ['required', Rule::in(['planifiee', 'en_cours', 'terminee', 'annulee'])],
            ]);
        } catch (ValidationException $exception) {
            throw $exception;
        }
    }

    private function notFoundResponse()
    {
        return response()->json([
            'success' => false,
            'message' => 'Reparation introuvable.',
        ], 404);
    }
}
