<p>
    <a class="btn-flat btn-modal-nav btn-blue" data-page="{{ route('companies.create') }}">Adicionar Empresa</a> 
    <a class="btn-flat btn-modal-nav btn-blue" data-page="{{ route('groups.index') }}">Gerenciar Grupos</a>
</p>

<table id="companies" class="table table-hover table-condensed">
    <thead>
        <tr>
            <th>Empresa</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach($companies as $company)
        <tr>
            <td>{{ $company->name }}</td>
            <td class='actions'>
                <a class="btn-flat btn-modal-nav loadUsers" data-page="{{ route('company.users', $company->id) }}"><i class="mdi-social-group"></i></a>
                <a class="btn-flat btn-modal-nav" data-page="{{ route('companies.edit', $company->id) }}"><i class="mdi-editor-mode-edit"></i></a>
                @if(!is_object($company->group) || !$company->group->is_admin)
                <form action="{{ route('companies.destroy', $company->id) }}" method="post" class="form-button-delete">
                    {{ csrf_field() }} {{ method_field("DELETE") }}
                    <button class="btn-flat"><i class="mdi-action-delete"></i></button>
                </form>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>