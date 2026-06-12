<?php

namespace App\Http\Controllers;

use App\Models\Mecanicien;
use App\Models\Reparation;
use App\Models\Vehicule;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ReparationController extends Controller
{
    public function index()
    {
        return view('reparations.index', [
            'reparations' => Reparation::with(['vehicule.client', 'mecanicien'])
                ->latest('date_reparation')
                ->paginate(10),
        ]);
    }

    public function create()
    {
        return view('reparations.form', [
            'reparation' => new Reparation(['date_reparation' => now(), 'statut' => 'planifiee']),
            'vehicules' => Vehicule::with('client')->orderBy('immatriculation')->get(),
            'mecaniciens' => Mecanicien::orderBy('nom')->get(),
        ]);
    }

    public function store(Request $request)
    {
        Reparation::create($this->validated($request));

        return redirect()->route('reparations.index')->with('success', 'Reparation ajoutee avec succes.');
    }

    public function edit(Reparation $reparation)
    {
        return view('reparations.form', [
            'reparation' => $reparation,
            'vehicules' => Vehicule::with('client')->orderBy('immatriculation')->get(),
            'mecaniciens' => Mecanicien::orderBy('nom')->get(),
        ]);
    }

    public function update(Request $request, Reparation $reparation)
    {
        $reparation->update($this->validated($request));

        return redirect()->route('reparations.index')->with('success', 'Reparation mise a jour avec succes.');
    }

    public function destroy(Reparation $reparation)
    {
        $reparation->delete();

        return redirect()->route('reparations.index')->with('success', 'Reparation supprimee avec succes.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'vehicule_id' => ['required', 'exists:vehicules,id'],
            'mecanicien_id' => ['required', 'exists:mecaniciens,id'],
            'date_reparation' => ['required', 'date'],
            'description' => ['required', 'string', 'max:1200'],
            'cout' => ['required', 'numeric', 'min:0'],
            'statut' => ['required', Rule::in(['planifiee', 'en_cours', 'terminee', 'annulee'])],
        ]);
    }
}
