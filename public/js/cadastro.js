$(function () {

    //-- CADASTRO

    var modal = $('.modal_cadastro');
    var save_history_url = '';
    var loaded = false;

    //open page actions
    modal.on('click','.btn-modal-nav, .btn-back', function(e){

        var url = $(this).attr('data-page');

        if($(this).hasClass('loadUsers')){
            save_history_url = url;
            loadUsers(url);
        }
        else if(url != ""){
            e.preventDefault();

            $.ajax({
                method: 'get',
                url: url,
                success: function(data){
                    var content = modal.find('#load_content');
                    content.html(data);

                    if(content.find('#frmUser').length > 0 && save_history_url != ""){
                        content.find('.btn-back.btn-admin').attr('data-page', save_history_url);
                    }
                    
                },
                error: function(xhr){
                    debug(xhr);
                }
            });

        }
    });


    //submit form
    modal.on('submit', 'form:not(.form-button-delete)', function(e){
        e.preventDefault();

        var form = $(this).closest('form');
        var alert = form.find('.alert');

        //validate
        if( form.find('[required="required"]').filter(function(){ return $(this).val() == ""; }).length > 0 ){
            alert.text('Preencha os campos obrigatórios');
        }else{

            //save
            $.ajax({
                cache: false,
                method: 'post',
                url: form.attr('action'),
                data: form.serialize(),
                success: function(data, textStatus, xhr){
                    if(typeof data == 'object' || data == true){
                        alert.html('Salvo com sucesso!');
                        //clear form
                        if(form.hasClass('cadastrar')) clearForm(form);
                    }
                },
                error: function(xhr){
                    if(xhr.status == '422' && typeof xhr.responseJSON == 'object'){
                        var arr = [];
                        $.each(xhr.responseJSON, function( index, value ) {
                            arr.push(value);
                        });
                        alert.html(arr.join('<br/>'));
                    }
                    else if(xhr.status == '500'){
                        debug(xhr);
                        alert.html('Desculpe, ocorreu um erro.');
                    }
                }
            });
        }

    });


    //delete item
    modal.on('submit', '.form-button-delete', function(e){
        e.preventDefault();

        var form = $(this);

        $.ajax({
            cache: false,
            method: 'post',
            url: form.attr('action'),
            data: form.serialize(),
            success: function(data){
                form.closest('tr').hide(0);
            },
            error: function(xhr){
                debug(xhr);
            }
        });

    });



});

function clearForm(form){
    form.find('input:not(input[type="checkbox"], input[type="radio"]), textarea').val('');
    form.find('input[type="checkbox"], input[type="radio"]').removeAttr('checked');
    form.find('select option').removeAttr('selected');
}

function loadCompanies(){
    $.ajax({
        cache: false,
        url: '/companies',
        success: function(data){
            //console.log(data);
            $('#load_content').html(data);
            loaded = 'companies';
        },
        error: function(xhr){
            debug(xhr);
        }
    });

}

function loadUsers(url_by_company){
    if(url_by_company == '' || url_by_company == undefined) url_by_company = '/users';
    $.ajax({
        cache: false,
        url: url_by_company,
        success: function(data){
            //console.log(data);
            $('#load_content').html(data);
            loaded = 'users';
        },
        error: function(xhr){
            debug(xhr);
        }
    });
}

function loadGroups(){
    $.ajax({
        cache: false,
        url: '/groups',
        success: function(data){
            //console.log(data);
            $('#load_content').html(data);
            loaded = 'groups';
        },
        error: function(xhr){
            debug(xhr);
        }
    });
}

function debug(xhr){
    console.log(xhr);
    var strip_tags = xhr.responseText.replace(/(<([^>]+)>)/ig,"");
    console.log(strip_tags);
}
//# sourceMappingURL=cadastro.js.map
