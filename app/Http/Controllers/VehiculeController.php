<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Vehicule;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class VehiculeController extends Controller
{
    public function index()
    {
        return view('vehicules.index', [
            'vehicules' => Vehicule::with('client')->withCount('reparations')->latest()->paginate(10),
        ]);
    }

    public function create()
    {
        return view('vehicules.form', [
            'vehicule' => new Vehicule(),
            'clients' => Client::orderBy('nom')->get(),
        ]);
    }

    public function store(Request $request)
    {
        Vehicule::create($this->validated($request));

        return redirect()->route('vehicules.index')->with('success', 'Vehicule ajoute avec succes.');
    }

    public function edit(Vehicule $vehicule)
    {
        return view('vehicules.form', [
            'vehicule' => $vehicule,
            'clients' => Client::orderBy('nom')->get(),
        ]);
    }

    public function update(Request $request, Vehicule $vehicule)
    {
        $vehicule->update($this->validated($request, $vehicule));

        return redirect()->route('vehicules.index')->with('success', 'Vehicule mis a jour avec succes.');
    }

    public function destroy(Vehicule $vehicule)
    {
        $vehicule->delete();

        return redirect()->route('vehicules.index')->with('success', 'Vehicule supprime avec succes.');
    }

    private function validated(Request $request, ?Vehicule $vehicule = null): array
    {
        return $request->validate([
            'client_id' => ['required', 'exists:clients,id'],
            'immatriculation' => ['required', 'string', 'max:30', Rule::unique('vehicules')->ignore($vehicule)],
            'marque' => ['required', 'string', 'max:80'],
            'modele' => ['required', 'string', 'max:80'],
            'annee' => ['nullable', 'integer', 'min:1980', 'max:' . (date('Y') + 1)],
        ]);
    }
}
