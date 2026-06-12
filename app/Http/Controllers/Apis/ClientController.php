<?php

namespace App\Http\Controllers\Apis;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use OpenApi\Attributes as OA;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    #[OA\Get(
        path: '/api/clients',
        summary: 'Lister les clients',
        tags: ['Clients'],
        responses: [
            new OA\Response(response: 200, description: 'Liste des clients recuperee avec succes'),
        ]
    )]
    public function index()
    {
        $clients = Client::latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'Liste des clients recuperee avec succes.',
            'data' => $clients,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    #[OA\Post(
        path: '/api/clients',
        summary: 'Creer un client',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['nom', 'prenom'],
                properties: [
                    new OA\Property(property: 'nom', type: 'string', example: 'Diallo'),
                    new OA\Property(property: 'prenom', type: 'string', example: 'Mamadou'),
                    new OA\Property(property: 'telephone', type: 'string', example: '622000000'),
                    new OA\Property(property: 'adresse', type: 'string', example: 'Conakry'),
                ]
            )
        ),
        tags: ['Clients'],
        responses: [
            new OA\Response(response: 201, description: 'Client cree avec succes'),
            new OA\Response(response: 422, description: 'Erreur de validation'),
        ]
    )]
    public function store(Request $request)
    {
        $validated = $this->validateClient($request);

        $client = Client::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Client cree avec succes.',
            'data' => $client,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    #[OA\Get(
        path: '/api/clients/{id}',
        summary: 'Afficher un client',
        tags: ['Clients'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Identifiant du client',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Client recupere avec succes'),
            new OA\Response(response: 404, description: 'Client introuvable'),
        ]
    )]
    public function show(string $id)
    {
        $client = Client::with('vehicules')->find($id);

        if (! $client) {
            return $this->notFoundResponse();
        }

        return response()->json([
            'success' => true,
            'message' => 'Client recupere avec succes.',
            'data' => $client,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    #[OA\Put(
        path: '/api/clients/{id}',
        summary: 'Modifier un client',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['nom', 'prenom'],
                properties: [
                    new OA\Property(property: 'nom', type: 'string', example: 'Bah'),
                    new OA\Property(property: 'prenom', type: 'string', example: 'Aissatou'),
                    new OA\Property(property: 'telephone', type: 'string', example: '628111111'),
                    new OA\Property(property: 'adresse', type: 'string', example: 'Kipe'),
                ]
            )
        ),
        tags: ['Clients'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Identifiant du client',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Client modifie avec succes'),
            new OA\Response(response: 404, description: 'Client introuvable'),
            new OA\Response(response: 422, description: 'Erreur de validation'),
        ]
    )]
    #[OA\Patch(
        path: '/api/clients/{id}',
        summary: 'Modifier partiellement un client',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'nom', type: 'string', example: 'Bah'),
                    new OA\Property(property: 'prenom', type: 'string', example: 'Aissatou'),
                    new OA\Property(property: 'telephone', type: 'string', example: '628111111'),
                    new OA\Property(property: 'adresse', type: 'string', example: 'Kipe'),
                ]
            )
        ),
        tags: ['Clients'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Identifiant du client',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Client modifie avec succes'),
            new OA\Response(response: 404, description: 'Client introuvable'),
            new OA\Response(response: 422, description: 'Erreur de validation'),
        ]
    )]
    public function update(Request $request, string $id)
    {
        $client = Client::find($id);

        if (! $client) {
            return $this->notFoundResponse();
        }

        $validated = $this->validateClient($request);
        $client->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Client modifie avec succes.',
            'data' => $client,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    #[OA\Delete(
        path: '/api/clients/{id}',
        summary: 'Supprimer un client',
        tags: ['Clients'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Identifiant du client',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Client supprime avec succes'),
            new OA\Response(response: 404, description: 'Client introuvable'),
        ]
    )]
    public function destroy(string $id)
    {
        $client = Client::find($id);

        if (! $client) {
            return $this->notFoundResponse();
        }

        $client->delete();

        return response()->json([
            'success' => true,
            'message' => 'Client supprime avec succes.',
        ], 200);
    }

    private function validateClient(Request $request): array
    {
        try {
            return $request->validate([
                'nom' => ['required', 'string', 'max:100'],
                'prenom' => ['required', 'string', 'max:100'],
                'telephone' => ['nullable', 'string', 'max:30'],
                'adresse' => ['nullable', 'string', 'max:180'],
            ]);
        } catch (ValidationException $exception) {
            throw $exception;
        }
    }

    private function notFoundResponse()
    {
        return response()->json([
            'success' => false,
            'message' => 'Client introuvable.',
        ], 404);
    }
}
