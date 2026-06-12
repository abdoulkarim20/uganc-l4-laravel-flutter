@extends('layouts.app')

@section('title', $vehicule->exists ? 'Modifier vehicule' : 'Nouveau vehicule')
@section('eyebrow', 'Parc automobile')
@section('page-title', $vehicule->exists ? 'Modifier un vehicule' : 'Ajouter un vehicule')

@section('content')
    <form class="form-panel" method="POST" action="{{ $vehicule->exists ? route('vehicules.update', $vehicule) : route('vehicules.store') }}">
        @csrf
        @if($vehicule->exists) @method('PUT') @endif

        <div class="form-grid">
            <label>Client
                <select name="client_id" required>
                    <option value="">Choisir un client</option>
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}" @selected(old('client_id', $vehicule->client_id) == $client->id)>{{ $client->nom_complet }}</option>
                    @endforeach
                </select>
            </label>
            <label>Immatriculation <input name="immatriculation" value="{{ old('immatriculation', $vehicule->immatriculation) }}" required></label>
            <label>Marque <input name="marque" value="{{ old('marque', $vehicule->marque) }}" required></label>
            <label>Modele <input name="modele" value="{{ old('modele', $vehicule->modele) }}" required></label>
            <label>Annee <input type="number" name="annee" value="{{ old('annee', $vehicule->annee) }}"></label>
        </div>

        <div class="form-actions">
            <a class="btn secondary" href="{{ route('vehicules.index') }}">Annuler</a>
            <button class="btn primary" type="submit">Enregistrer</button>
        </div>
    </form>
@endsection
