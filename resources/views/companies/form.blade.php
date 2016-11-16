<form id="frmCompany" method="post" class="{{ isset($obj) ? 'editar' : 'cadastrar' }}"
    action="{{ isset($obj) ? route('companies.update', $obj->id) : route('companies.store') }}">

    {{ csrf_field() }}
    @if(isset($obj)) 
        <input type="hidden" name="_method" value="PUT">
    @endif
    <div>
        <label>Nome <em>*</em></label>
        <input type="text" name="name" required="required" value="{{ $obj->name or '' }}" />
    </div>
    <div>
        <label>Grupo <em>*</em></label>
        <select name="group_id" required="required" >
            <option value=""></option>
            @foreach($groups as $group)
                <option value="{{ $group->id }}"
                    {{ isset($obj) && $group->id == $obj->group_id ? 'selected="selected"' : '' }}
                    >{{ $group->name }}
                </option>
            @endforeach
        </select>
    </div>
    <div>
        <label>Fieldlogger <em>*</em></label>
        <input type="number" name="fieldlogger_id" required="required" value="{{ $obj->fieldlogger_id or '' }}" />
    </div>
    <!--<div>
        <label><input type="checkbox" name="active" value="1"
            {{ !isset($obj) || $obj->active == 1 ? 'checked="checked"' : '' }}
             > Ativo</label>
    </div>-->
    <div class="actions">
        <button type="submit" class="btn-flat btn-blue">Salvar</button>
        <p class="alert"></p>
    </div>
</form>

<button type="button" class="btn-flat btn-back" data-page="{{ route('companies.index') }}">Voltar</button>