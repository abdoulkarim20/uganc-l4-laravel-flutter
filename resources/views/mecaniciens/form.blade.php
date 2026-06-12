@extends('layouts.app')

@section('title', $mecanicien->exists ? 'Modifier mecanicien' : 'Nouveau mecanicien')
@section('eyebrow', 'Equipe atelier')
@section('page-title', $mecanicien->exists ? 'Modifier un mecanicien' : 'Ajouter un mecanicien')

@section('content')
    <form class="form-panel" method="POST" action="{{ $mecanicien->exists ? route('mecaniciens.update', $mecanicien) : route('mecaniciens.store') }}">
        @csrf
        @if($mecanicien->exists) @method('PUT') @endif

        <div class="form-grid">
            <label>Nom <input name="nom" value="{{ old('nom', $mecanicien->nom) }}" required></label>
            <label>Prenom <input name="prenom" value="{{ old('prenom', $mecanicien->prenom) }}" required></label>
            <label>Telephone <input name="telephone" value="{{ old('telephone', $mecanicien->telephone) }}"></label>
            <label>Specialite <input name="specialite" value="{{ old('specialite', $mecanicien->specialite) }}" required></label>
        </div>

        <div class="form-actions">
            <a class="btn secondary" href="{{ route('mecaniciens.index') }}">Annuler</a>
            <button class="btn primary" type="submit">Enregistrer</button>
        </div>
    </form>
@endsection
