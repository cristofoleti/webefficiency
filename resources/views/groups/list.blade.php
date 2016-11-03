<p><a class="btn-flat btn-modal-nav btn-blue" data-page="{{ route('groups.create') }}">Adicionar Grupo</a></p>

<table id="groups" class="table table-hover table-condensed">
    <thead>
        <tr>
            <th>Grupo</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach($groups as $group)
        <tr>
            <td>{{ $group->name }}</td>
            <td class='actions'>
                <a class="btn-flat btn-modal-nav" data-page="{{ route('groups.edit', $group->id) }}"><i class="mdi-editor-mode-edit"></i></a>
                @if(!$group->is_admin)
                <form action="{{ route('groups.destroy', $group->id) }}" method="post" class="form-button-delete">
                    {{ csrf_field() }} {{ method_field("DELETE") }}
                    <button class="btn-flat"><i class="mdi-action-delete"></i></button>
                </form>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<button type="button" class="btn-flat btn-back" data-page="{{ route('companies.index') }}">Voltar</button>