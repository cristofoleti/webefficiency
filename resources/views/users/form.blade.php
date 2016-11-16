<form id="frmUser" method="post" class="{{ isset($obj) ? 'editar' : 'cadastrar' }}"
    action="{{ isset($obj) ? route('users.update', $obj->id) : route('users.store') }}">

    {{ csrf_field() }}
    @if(isset($obj)) 
        <input type="hidden" name="_method" value="PUT">
    @endif
    <div>
        <label>Nome <em>*</em></label>
        <input type="text" name="name" required="required" value="{{ $obj->name or '' }}" />
    </div>
    <div>
        <label>Email @if(!isset($obj)) <em>*</em> @endif</label>
        <input type="email" name="email" value="{{ $obj->email or '' }}" {{ isset($obj) ? 'disabled readonly' : 'required="required"' }} />
    </div>
    <div>
        @if(isset($obj))
        <label>Alterar Senha</label>
        <input type="password" name="new_password"  />
        @else
        <label>Senha <em>*</em></label>
        <input type="password" name="password" required="required" />
        @endif
    </div>
    <div>
        <label><input type="checkbox" name="is_admin" value="1"
            {{ !isset($obj) || $obj->is_admin == 1 ? 'checked="checked"' : '' }}
             > Administrador</label>
    </div>
    <fieldset>
        <legend><strong>Empresas</strong></legend>
        @foreach($companies as $comp)
            <p><input type="checkbox" name="company_id[]" value="{{ $comp->id }}" 
                {{ isset($obj) && is_array($user_companies) && in_array($comp->id, $user_companies) ? 'checked="checked"' : '' }}
            /> {{ $comp->name }} </p>
        @endforeach
    </fieldset>
    <div class="actions">
        <button type="submit" class="btn-flat btn-blue">Salvar</button>
        <p class="alert"></p>
    </div>
</form>

<button type="button" class="btn-flat btn-back {{ Auth::user()->isGroupAdmin() ? 'btn-admin':'' }}" data-page="{{ Auth::user()->isGroupAdmin() ? route('companies.index') : route('users.index') }}">Voltar</button>