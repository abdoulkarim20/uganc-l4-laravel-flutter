@extends('layouts.app')

@section('title', 'Vehicules')
@section('eyebrow', 'Parc automobile')
@section('page-title', 'Vehicules')

@section('content')
    <section class="panel">
        <div class="panel-header">
            <div><span class="eyebrow">Suivi vehicule</span>
                <h3>Vehicules clients</h3>
            </div>
            <a class="btn primary" href="{{ route('vehicules.create') }}">Ajouter</a>
        </div>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Immatriculation</th>
                        <th>Véhicule</th>
                        <th>Propriétaire</th>
                        <th>Interventions</th>
                        <th style="text-align:center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($vehicules as $vehicule)
                        <tr>
                            <td><strong>{{ $vehicule->immatriculation }}</strong></td>
                            <td>{{ $vehicule->marque }} {{ $vehicule->modele }} <small>{{ $vehicule->annee }}</small></td>
                            <td>{{ $vehicule->client->nom_complet }}</td>
                            <td>{{ $vehicule->reparations_count }}</td>
                            <!-- <td>@include('partials.actions', ['edit' => route('vehicules.edit', $vehicule), 'delete' => route('vehicules.destroy', $vehicule)])
                                                    </td> -->
                            <td style="text-align:center;">
                                <div style="display:inline-flex; align-items:center; gap:8px;">

                                    <a href="{{ route('vehicules.edit', $vehicule) }}" title="Modifier"
                                        style="display:inline-flex; align-items:center; justify-content:center; width:32px; height:32px; border-radius:8px; background:linear-gradient(135deg,#f6a623,#f0810f); color:#fff; text-decoration:none; box-shadow:0 2px 6px rgba(240,129,15,0.4);">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>

                                    <form action="{{ route('vehicules.destroy', $vehicule) }}" method="POST" style="margin:0;"
                                        onsubmit="return confirm('Voulez-vous supprimer ce véhicule ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Supprimer"
                                            style="display:inline-flex; align-items:center; justify-content:center; width:32px; height:32px; border-radius:8px; background:linear-gradient(135deg,#ff5f6d,#c0392b); color:#fff; border:none; cursor:pointer; box-shadow:0 2px 6px rgba(192,57,43,0.4);">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="empty">Aucun vehicule enregistre.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $vehicules->links() }}
    </section>
@endsection