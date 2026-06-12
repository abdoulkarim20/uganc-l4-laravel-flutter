@extends('layouts.app')

@section('title', 'Dashboard')
@section('eyebrow', 'Vue generale')
@section('page-title', 'Tableau de bord')

@section('content')
    <section class="hero-panel">
        <div>
            <span class="eyebrow">Tableau de bord</span>
            <h2>Gestion du garage</h2>
            <p>Consultez les principales informations relatives aux clients, véhicules, mécaniciens et réparations.</p>
        </div>
        <!-- <div class="hero-metrics">
                                <span>Aujourd'hui</span>
                                <strong>Bonne journée de travail.</strong>
                            </div> -->
    </section>

    <section class="stats-grid">
        @foreach ($stats as $stat)
            <article class="stat-card {{ $stat['tone'] }}">
                <span>{{ $stat['label'] }}</span>
                <strong>{{ $stat['value'] }}</strong>
            </article>
        @endforeach
    </section>

    <section class="content-grid">
        <div class="panel wide">
            <div class="panel-header">
                <div>
                    <span class="eyebrow">Atelier</span>
                    <h3>Interventions récentes</h3>
                </div>
                <a href="{{ route('reparations.index') }}">Tout voir</a>
            </div>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Véhicule</th>
                            <th>Client</th>
                            <th>Technicien</th>
                            <th>Statut</th>
                            <th>Montant</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentReparations as $reparation)
                            <tr>
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
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="empty">Aucune reparation enregistree pour le moment.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <aside class="panel">
            <div class="panel-header">
                <div>
                    <span class="eyebrow">Equipe</span>
                    <h3>Mecaniciens</h3>
                </div>
            </div>

            <div class="stack-list">
                @forelse ($mecaniciensDisponibles as $mecanicien)
                    <div class="stack-item">
                        <span class="avatar">{{ substr($mecanicien->prenom, 0, 1) }}{{ substr($mecanicien->nom, 0, 1) }}</span>
                        <div>
                            <strong>{{ $mecanicien->nom_complet }}</strong>
                            <small>{{ $mecanicien->specialite }} - {{ $mecanicien->reparations_count }} dossier(s)</small>
                        </div>
                    </div>
                @empty
                    <p class="empty">Ajoutez vos mecaniciens pour organiser l'atelier.</p>
                @endforelse
            </div>
        </aside>
    </section>
@endsection