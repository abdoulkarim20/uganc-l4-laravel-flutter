@extends('layouts.app')

@section('title', 'Mecaniciens')
@section('eyebrow', 'Equipe atelier')
@section('page-title', 'Mecaniciens')

@section('content')
    <section class="panel">
        <div class="panel-header">
            <div><span class="eyebrow">Competences</span>
                <h3>Equipe technique</h3>
            </div>
            <a class="btn primary" href="{{ route('mecaniciens.create') }}">Ajouter</a>
        </div>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Nom complet</th>
                        <th>Téléphone</th>
                        <th>Spécialité</th>
                        <th>Interventions</th>
                        <th style="text-align:center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($mecaniciens as $mecanicien)
                        <tr>
                            <td><strong>{{ $mecanicien->nom_complet }}</strong></td>
                            <td>{{ $mecanicien->telephone ?? '-' }}</td>
                            <td>{{ $mecanicien->specialite }}</td>
                            <td>{{ $mecanicien->reparations_count }}</td>
                            <!-- <td>@include('partials.actions', ['edit' => route('mecaniciens.edit', $mecanicien), 'delete' => route('mecaniciens.destroy', $mecanicien)])
                                                                    </td> -->
                            <td style="text-align:center;">
                                <div style="display:flex; align-items:center; justify-content:center; gap:8px;">

                                    <a href="{{ route('mecaniciens.edit', $mecanicien) }}" title="Modifier"
                                        style="display:inline-flex; align-items:center; justify-content:center; width:32px; height:32px; border-radius:8px; background:linear-gradient(135deg,#f6a623,#f0810f); color:#fff; text-decoration:none; box-shadow:0 2px 6px rgba(240,129,15,0.4);">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>

                                    <form action="{{ route('mecaniciens.destroy', $mecanicien) }}" method="POST"
                                        style="margin:0;" onsubmit="return confirm('Voulez-vous supprimer ce mécanicien ?')">
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
                            <td colspan="5" class="empty">Aucun mecanicien enregistre.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $mecaniciens->links() }}
    </section>
@endsection