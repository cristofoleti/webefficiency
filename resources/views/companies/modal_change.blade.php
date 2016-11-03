<div id="App__change_company_modal" class="modal">
    <div class="modal-content">
        <div class="row">
            <div class="col s12">
                <label>Alterar Empresa</label>
                <select class="App__change_company_combo browser-default">
                    @foreach($global_companies as $company)
                        <option {{ ($company->id == session('default_company')) ? 'selected' : '' }}
                                value="{{ request()->url() . '?set_default_company=' . $company->id }}">{{ $company->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <a href="#!" class="modal-action waves-effect waves-green btn-flat App__change_company_agree">Alterar</a>
        <a href="#!" class="modal-action modal-close waves-effect waves-red btn-flat App__change_company_cancel">Cancelar</a>
    </div>
</div>