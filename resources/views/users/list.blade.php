<p><strong>{{ isset($company) ? 'Usuários - '.$company->name : '' }}</strong></p>

<p><a class="btn-flat btn-modal-nav btn-blue" data-page="{{ route('users.create') }}">Adicionar Usuário</a></p>

<table id="users" class="table table-hover table-condensed">
    <thead>
        <tr>
            <th>Nome</th>
            <th>Email</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach($users as $user)
        
        <tr>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td class='actions'>
                <a class="btn-flat btn-modal-nav" data-page="{{ route('users.edit', $user->id) }}"><i class="mdi-editor-mode-edit"></i></a>
                <form action="{{ route('users.destroy', $user->id) }}" method="post" class="form-button-delete">
                    {{ csrf_field() }} {{ method_field("DELETE") }}
                    <button class="btn-flat"><i class="mdi-action-delete"></i></button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@if(Auth::user()->isGroupAdmin())
<button type="button" class="btn-flat btn-back" data-page="{{ route('companies.index') }}">Voltar</button>
@endif