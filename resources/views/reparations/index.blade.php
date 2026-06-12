@extends('layouts.app')

@section('title', 'Reparations')
@section('eyebrow', 'Production atelier')
@section('page-title', 'Reparations')

@section('content')
    <section class="panel">
        <div class="panel-header">
            <div><span class="eyebrow">Ordres de travail</span>
                <h3>Reparations</h3>
            </div>
            <a class="btn primary" href="{{ route('reparations.create') }}">Ajouter</a>
        </div>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Véhicule</th>
                        <th>Client</th>
                        <th>Mécanicien</th>
                        <th>Statut</th>
                        <th>Montant</th>
                        <th style="text-align:center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($reparations as $reparation)
                        <tr>
                            <td>{{ $reparation->date_reparation->format('d/m/Y') }}</td>
                            <td>
                                <strong>{{ $reparation->vehicule->immatriculation }}</strong>
                                <small>{{ $reparation->vehicule->marque }} {{ $reparation->vehicule->modele }}</small>
                            </td>
                            <td>{{ $reparation->vehicule->client->nom_complet }}</td>
                            <td>{{ $reparation->mecanicien->nom_complet }}</td>
                            <td><span
                                    class="badge {{ $reparation->statut }}">{{ str_replace('_', ' ', $reparation->statut) }}</span>
                            </td>
                            <td>{{ number_format($reparation->cout, 0, ',', ' ') }} GNF</td>
                            <!-- <td>@include('partials.actions', ['edit' => route('reparations.edit', $reparation), 'delete' => route('reparations.destroy', $reparation)])
                                                                                            </td> -->
                            <td style="text-align:center;">
                                <div style="display:inline-flex; align-items:center; gap:8px;">

                                    <a href="{{ route('reparations.edit', $reparation) }}" title="Modifier"
                                        style="display:inline-flex; align-items:center; justify-content:center; width:32px; height:32px; border-radius:8px; background:linear-gradient(135deg,#f6a623,#f0810f); color:#fff; text-decoration:none; box-shadow:0 2px 6px rgba(240,129,15,0.4);">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>

                                    <form action="{{ route('reparations.destroy', $reparation) }}" method="POST"
                                        style="margin:0;" onsubmit="return confirm('Voulez-vous supprimer cette réparation ?')">
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
                            <td colspan="7" class="empty">Aucune reparation enregistree.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $reparations->links() }}
    </section>
@endsection