@extends('layouts.app')

@section('title', $client->exists ? 'Modifier client' : 'Nouveau client')
@section('eyebrow', 'Relation client')
@section('page-title', $client->exists ? 'Modifier un client' : 'Ajouter un client')

@section('content')
    <form class="form-panel" method="POST" action="{{ $client->exists ? route('clients.update', $client) : route('clients.store') }}">
        @csrf
        @if($client->exists) @method('PUT') @endif

        <div class="form-grid">
            <label>Nom <input name="nom" value="{{ old('nom', $client->nom) }}" required></label>
            <label>Prenom <input name="prenom" value="{{ old('prenom', $client->prenom) }}" required></label>
            <label>Telephone <input name="telephone" value="{{ old('telephone', $client->telephone) }}"></label>
            <label>Adresse <input name="adresse" value="{{ old('adresse', $client->adresse) }}"></label>
        </div>

        <div class="form-actions">
            <a class="btn secondary" href="{{ route('clients.index') }}">Annuler</a>
            <button class="btn primary" type="submit">Enregistrer</button>
        </div>
    </form>
@endsection
