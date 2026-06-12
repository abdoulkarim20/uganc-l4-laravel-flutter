<?php

namespace App\Http\Controllers\Apis;

use App\Http\Controllers\Controller;
use App\Models\Mecanicien;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use OpenApi\Attributes as OA;

class MecanicienController extends Controller
{
    #[OA\Get(
        path: '/api/mecaniciens',
        summary: 'Lister les mecaniciens',
        tags: ['Mecaniciens'],
        responses: [
            new OA\Response(response: 200, description: 'Liste des mecaniciens recuperee avec succes'),
        ]
    )]
    public function index()
    {
        $mecaniciens = Mecanicien::withCount('reparations')->latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'Liste des mecaniciens recuperee avec succes.',
            'data' => $mecaniciens,
        ], 200);
    }

    #[OA\Post(
        path: '/api/mecaniciens',
        summary: 'Creer un mecanicien',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['nom', 'prenom', 'specialite'],
                properties: [
                    new OA\Property(property: 'nom', type: 'string', example: 'Camara'),
                    new OA\Property(property: 'prenom', type: 'string', example: 'Ibrahima'),
                    new OA\Property(property: 'telephone', type: 'string', example: '624000000'),
                    new OA\Property(property: 'specialite', type: 'string', example: 'Moteur diesel'),
                ]
            )
        ),
        tags: ['Mecaniciens'],
        responses: [
            new OA\Response(response: 201, description: 'Mecanicien cree avec succes'),
            new OA\Response(response: 422, description: 'Erreur de validation'),
        ]
    )]
    public function store(Request $request)
    {
        $validated = $this->validateMecanicien($request);

        $mecanicien = Mecanicien::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Mecanicien cree avec succes.',
            'data' => $mecanicien,
        ], 201);
    }

    #[OA\Get(
        path: '/api/mecaniciens/{id}',
        summary: 'Afficher un mecanicien',
        tags: ['Mecaniciens'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Identifiant du mecanicien',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Mecanicien recupere avec succes'),
            new OA\Response(response: 404, description: 'Mecanicien introuvable'),
        ]
    )]
    public function show(string $id)
    {
        $mecanicien = Mecanicien::with('reparations')->find($id);

        if (! $mecanicien) {
            return $this->notFoundResponse();
        }

        return response()->json([
            'success' => true,
            'message' => 'Mecanicien recupere avec succes.',
            'data' => $mecanicien,
        ], 200);
    }

    #[OA\Put(
        path: '/api/mecaniciens/{id}',
        summary: 'Modifier un mecanicien',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['nom', 'prenom', 'specialite'],
                properties: [
                    new OA\Property(property: 'nom', type: 'string', example: 'Bah'),
                    new OA\Property(property: 'prenom', type: 'string', example: 'Moussa'),
                    new OA\Property(property: 'telephone', type: 'string', example: '628111111'),
                    new OA\Property(property: 'specialite', type: 'string', example: 'Electricite auto'),
                ]
            )
        ),
        tags: ['Mecaniciens'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Identifiant du mecanicien',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Mecanicien modifie avec succes'),
            new OA\Response(response: 404, description: 'Mecanicien introuvable'),
            new OA\Response(response: 422, description: 'Erreur de validation'),
        ]
    )]
    #[OA\Patch(
        path: '/api/mecaniciens/{id}',
        summary: 'Modifier partiellement un mecanicien',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'nom', type: 'string', example: 'Bah'),
                    new OA\Property(property: 'prenom', type: 'string', example: 'Moussa'),
                    new OA\Property(property: 'telephone', type: 'string', example: '628111111'),
                    new OA\Property(property: 'specialite', type: 'string', example: 'Electricite auto'),
                ]
            )
        ),
        tags: ['Mecaniciens'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Identifiant du mecanicien',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Mecanicien modifie avec succes'),
            new OA\Response(response: 404, description: 'Mecanicien introuvable'),
            new OA\Response(response: 422, description: 'Erreur de validation'),
        ]
    )]
    public function update(Request $request, string $id)
    {
        $mecanicien = Mecanicien::find($id);

        if (! $mecanicien) {
            return $this->notFoundResponse();
        }

        $validated = $this->validateMecanicien($request);
        $mecanicien->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Mecanicien modifie avec succes.',
            'data' => $mecanicien,
        ], 200);
    }

    #[OA\Delete(
        path: '/api/mecaniciens/{id}',
        summary: 'Supprimer un mecanicien',
        tags: ['Mecaniciens'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Identifiant du mecanicien',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Mecanicien supprime avec succes'),
            new OA\Response(response: 404, description: 'Mecanicien introuvable'),
        ]
    )]
    public function destroy(string $id)
    {
        $mecanicien = Mecanicien::find($id);

        if (! $mecanicien) {
            return $this->notFoundResponse();
        }

        $mecanicien->delete();

        return response()->json([
            'success' => true,
            'message' => 'Mecanicien supprime avec succes.',
        ], 200);
    }

    private function validateMecanicien(Request $request): array
    {
        try {
            return $request->validate([
                'nom' => ['required', 'string', 'max:100'],
                'prenom' => ['required', 'string', 'max:100'],
                'telephone' => ['nullable', 'string', 'max:30'],
                'specialite' => ['required', 'string', 'max:120'],
            ]);
        } catch (ValidationException $exception) {
            throw $exception;
        }
    }

    private function notFoundResponse()
    {
        return response()->json([
            'success' => false,
            'message' => 'Mecanicien introuvable.',
        ], 404);
    }
}
