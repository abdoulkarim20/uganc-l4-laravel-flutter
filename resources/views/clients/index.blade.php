@extends('layouts.app')

@section('title', 'Clients')
@section('eyebrow', 'Carnet client')
@section('page-title', 'Clients')

@section('content')
    <section class="panel">
        <div class="panel-header">
            <div>
                <span class="eyebrow">Relation client</span>
                <h3>Liste des clients</h3>
            </div>
            <a class="btn primary" href="{{ route('clients.create') }}">Ajouter</a>
        </div>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Nom complet</th>
                        <th>Telephone</th>
                        <th>Adresse</th>
                        <th>Vehicules</th>
                        <th style="text-align:center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($clients as $client)
                        <tr>
                            <td><strong>{{ $client->nom_complet }}</strong></td>
                            <td>{{ $client->telephone ?? '-' }}</td>
                            <td>{{ $client->adresse ?? '-' }}</td>
                            <td>{{ $client->vehicules_count }}</td>
                            <td>
                                <div style="display:flex; align-items:center; justify-content:center; gap:8px;">

                                    <a href="{{ route('clients.edit', $client) }}" title="Modifier"
                                        style="display:inline-flex; align-items:center; justify-content:center; width:32px; height:32px; border-radius:8px; background:linear-gradient(135deg,#f6a623,#f0810f); color:#fff; text-decoration:none; box-shadow:0 2px 6px rgba(240,129,15,0.4);">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>

                                    <form action="{{ route('clients.destroy', $client) }}" method="POST" style="margin:0;"
                                        onsubmit="return confirm('Voulez-vous supprimer ce client ?')">
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
                            <td colspan="5" class="empty">Aucun client enregistre.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $clients->links() }}
    </section>
@endsection