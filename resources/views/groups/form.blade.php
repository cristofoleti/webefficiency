<form id="frmGroup" method="post" class="{{ isset($obj) ? 'editar' : 'cadastrar' }}"
    action="{{ isset($obj) ? route('groups.update', $obj->id) : route('groups.store') }}">

    {{ csrf_field() }}
    @if(isset($obj)) 
        <input type="hidden" name="_method" value="PUT">
    @endif
    <div>
        <label>Nome <em>*</em></label>
        <input type="text" name="name" required="required" value="{{ $obj->name or '' }}" />
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

<button type="button" class="btn-flat btn-back" data-page="{{ route('groups.index') }}">Voltar</button>