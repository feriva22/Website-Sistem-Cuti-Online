<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<script type="text/javascript">
    var status_level = <?php echo json_encode($status_level); ?>;
    var pk_key = 'jbt_id';
    var form_modal = '#jabatan-modal';
    var form_key = '#jabatan-form';

    var url = {
        data : base_url+'jabatan/get_dataajax',
        detail : base_url+'jabatan/detail',
        add: base_url + 'jabatan/add',
		edit: base_url + 'jabatan/edit',
		delete: base_url + 'jabatan/delete',
    };

    var dataTableObj = $("#jabatan-table").DataTable({
        processing:true,
        serverSide:false,
        bProcessing: true,
        stateSave: false,
        pagingType: 'full_numbers',
        ajax:{
            url: url.data,
            type: 'POST',
            dataType: "json"  
        },
        'columns':[ 
            {   data:"jbt_id",
                render: function(data, type, meta){
                    return '<input type="checkbox" name="jbt_id[]" value="'+ data +'"/>';
                }
            },
            { data:"jbt_nama" },
            { 
                data:"jbt_level",
                render: function(data, type,meta){
                    return status_level[data].text;
                } 
            },
            { 
                data:"jbt_id",
                render: function(data, type, meta){
                    return `<button class="btn btn-sm btn-success btn-edit" data-id=${data}><i class="far fa-edit"></i></button>
                            <button class="btn btn-sm btn-danger btn-delete" data-id=${data}><i class="fa fa-trash"></i></button>`;
                }
            }
        ]
    });

    $("body").on('click',".btn-add",function(e){
        e.preventDefault();
        resetForm(form_key);
        showForm(form_modal,false);
    });

    $("body").on('click',".btn-edit",function(e){
        e.preventDefault();
        /* ajax request ke server */
        var submitted_data = {};
        submitted_data[pk_key] = $(this).data('id');
        $.ajax({
            url: url.detail,
            type: 'POST',
            dataType: "json",
            data : submitted_data,
            success : function(resp){
                if(typeof(resp) !== 'undefined' && typeof(resp) === 'object'){
                    fillForm(form_key,resp.data);
                    showForm(form_modal,true);
                }
            }
        });
    });

    $("body").on('click',".btn-delete",function(e){
        e.preventDefault();
        if(confirm("ingin hapus data ?")){
            /* ajax request ke server */
            var submitted_data = {};
            submitted_data[pk_key+'[]'] = $(this).data('id');
            $.ajax({
                url: url.delete,
                type: 'POST',
                dataType: "json",
                data : submitted_data,
                success : function(resp){
                    if(resp.status == 'ok'){
                        showMessage('success',resp.msg);
                    }
                    reloadTable(dataTableObj);
                }
            });
        }
    })

    $("body").on('click',"#submit-btn",function(e){
        e.preventDefault();
        var action_url = $(form_modal).data('onedit') == 'true' ? url.edit : url.add;
        var formData = new FormData($(form_key)[0]);
        
        $.ajax({
            url: action_url,
            type: "post",
            data: formData,
            mimeType: 'multipart/form-data',
            contentType: false,
            cache: false,
            processData: false,
            success : function(resp){
                if(typeof(resp) !== 'undefined' && typeof(resp) !== 'object')
                    resp = JSON.parse(resp);
                
                if(resp.status == 'ok')
                    showMessage('success',resp.msg);
                else
                    showMessage('error','Ada yang belum diisi');

                closeForm(form_modal);
                reloadTable(dataTableObj);
            }
        });
    });

    

    

</script>