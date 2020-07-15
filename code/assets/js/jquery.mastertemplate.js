const Toast = Swal.mixin({
    toast: true,
    position: 'top',
    showConfirmButton: false,
    timer: 3000
});

showMessage = function(type,msg){
    if(typeof(msg) == 'object'){
        msg = 'Check inputan anda';
    }
    Toast.fire({
        type: type,
        title: msg
    });
}

fillForm = function(selector,data){
    resetForm(selector);
    $.each(data, function(key, value){
            $('[name='+key+']', selector).val(value);
    });
}

resetForm = function(selector){
    if(typeof($(selector) !== 'undefined'))
        $(selector)[0].reset();
        $(selector).find('input[type=hidden]').each(function(){    
            this.value = '';
        });
}

showForm = function(selector_modal,isEdit){
    if(isEdit) { $(selector_modal).data('onedit','true');}
    else { $(selector_modal).data('onedit','false'); }
    $(selector_modal).modal('show');
}

closeForm = function(selector_modal){
    $(selector_modal).modal('hide');
}

reloadTable = function(datatableObj){
    datatableObj.ajax.reload(function(){/*action inside here*/}); 
}