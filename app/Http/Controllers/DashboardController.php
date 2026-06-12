<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Mecanicien;
use App\Models\Reparation;
use App\Models\Vehicule;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $reparations = Reparation::with(['vehicule.client', 'mecanicien'])
            ->latest('date_reparation')
            ->take(6)
            ->get();

        $revenue = Reparation::where('statut', 'terminee')->sum('cout');

        return view('dashboard', [
            'stats' => [
                ['label' => 'Clients actifs', 'value' => Client::count(), 'tone' => 'green'],
                ['label' => 'Vehicules suivis', 'value' => Vehicule::count(), 'tone' => 'blue'],
                ['label' => 'Reparations en cours', 'value' => Reparation::where('statut', 'en_cours')->count(), 'tone' => 'yellow'],
                ['label' => 'Chiffre realise', 'value' => number_format($revenue, 0, ',', ' ') . ' GNF', 'tone' => 'red'],
            ],
            'recentReparations' => $reparations,
            'mecaniciensDisponibles' => Mecanicien::withCount('reparations')->orderBy('nom')->take(5)->get(),
        ]);
    }
}
