<div class="row-actions">
    <a class="link-action" href="{{ $edit }}">Modifier</a>
    <form action="{{ $delete }}" method="POST" onsubmit="return confirm('Confirmer la suppression ?')">
        @csrf
        @method('DELETE')
        <button class="danger-action" type="submit">Supprimer</button>
    </form>
</div>
