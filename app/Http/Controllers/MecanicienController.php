<?php

namespace App\Http\Controllers;

use App\Models\Mecanicien;
use Illuminate\Http\Request;

class MecanicienController extends Controller
{
    public function index()
    {
        return view('mecaniciens.index', [
            'mecaniciens' => Mecanicien::withCount('reparations')->latest()->paginate(10),
        ]);
    }

    public function create()
    {
        return view('mecaniciens.form', ['mecanicien' => new Mecanicien()]);
    }

    public function store(Request $request)
    {
        Mecanicien::create($this->validated($request));

        return redirect()->route('mecaniciens.index')->with('success', 'Mecanicien ajoute avec succes.');
    }

    public function edit(Mecanicien $mecanicien)
    {
        return view('mecaniciens.form', compact('mecanicien'));
    }

    public function update(Request $request, Mecanicien $mecanicien)
    {
        $mecanicien->update($this->validated($request));

        return redirect()->route('mecaniciens.index')->with('success', 'Mecanicien mis a jour avec succes.');
    }

    public function destroy(Mecanicien $mecanicien)
    {
        $mecanicien->delete();

        return redirect()->route('mecaniciens.index')->with('success', 'Mecanicien supprime avec succes.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'nom' => ['required', 'string', 'max:100'],
            'prenom' => ['required', 'string', 'max:100'],
            'telephone' => ['nullable', 'string', 'max:30'],
            'specialite' => ['required', 'string', 'max:120'],
        ]);
    }
}
