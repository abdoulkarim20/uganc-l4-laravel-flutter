@extends('layouts.app')

@section('title', $reparation->exists ? 'Modifier reparation' : 'Nouvelle reparation')
@section('eyebrow', 'Production atelier')
@section('page-title', $reparation->exists ? 'Modifier une reparation' : 'Ajouter une reparation')

@section('content')
    <form class="form-panel" method="POST" action="{{ $reparation->exists ? route('reparations.update', $reparation) : route('reparations.store') }}">
        @csrf
        @if($reparation->exists) @method('PUT') @endif

        <div class="form-grid">
            <label>Vehicule
                <select name="vehicule_id" required>
                    <option value="">Choisir un vehicule</option>
                    @foreach($vehicules as $vehicule)
                        <option value="{{ $vehicule->id }}" @selected(old('vehicule_id', $reparation->vehicule_id) == $vehicule->id)>
                            {{ $vehicule->immatriculation }} - {{ $vehicule->marque }} {{ $vehicule->modele }} / {{ $vehicule->client->nom_complet }}
                        </option>
                    @endforeach
                </select>
            </label>
            <label>Mecanicien
                <select name="mecanicien_id" required>
                    <option value="">Choisir un mecanicien</option>
                    @foreach($mecaniciens as $mecanicien)
                        <option value="{{ $mecanicien->id }}" @selected(old('mecanicien_id', $reparation->mecanicien_id) == $mecanicien->id)>
                            {{ $mecanicien->nom_complet }} - {{ $mecanicien->specialite }}
                        </option>
                    @endforeach
                </select>
            </label>
            <label>Date <input type="date" name="date_reparation" value="{{ old('date_reparation', optional($reparation->date_reparation)->format('Y-m-d') ?? now()->format('Y-m-d')) }}" required></label>
            <label>Cout <input type="number" step="0.01" min="0" name="cout" value="{{ old('cout', $reparation->cout ?? 0) }}" required></label>
            <label>Statut
                <select name="statut" required>
                    @foreach(['planifiee' => 'Planifiee', 'en_cours' => 'En cours', 'terminee' => 'Terminee', 'annulee' => 'Annulee'] as $value => $label)
                        <option value="{{ $value }}" @selected(old('statut', $reparation->statut) === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </label>
            <label class="full">Description
                <textarea name="description" rows="5" required>{{ old('description', $reparation->description) }}</textarea>
            </label>
        </div>

        <div class="form-actions">
            <a class="btn secondary" href="{{ route('reparations.index') }}">Annuler</a>
            <button class="btn primary" type="submit">Enregistrer</button>
        </div>
    </form>
@endsection
