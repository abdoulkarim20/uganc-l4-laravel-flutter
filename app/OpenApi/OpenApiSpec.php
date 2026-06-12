<?php

namespace App\OpenApi;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    title: 'Gestion Garage API',
    description: "Documentation de l'API Gestion Garage"
)]
#[OA\Server(
    url: L5_SWAGGER_CONST_HOST,
    description: 'Serveur principal'
)]
class OpenApiSpec
{
}
