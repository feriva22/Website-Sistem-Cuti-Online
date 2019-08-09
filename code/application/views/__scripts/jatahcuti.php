<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<script type="text/javascript">

    var pk_key = 'jtc_id';
    var form_modal = '#jatahcuti-modal';
    var form_key = '#jatahcuti-form';

    var url = {
        data : base_url+'jatahcuti/get_dataajax',
        detail : base_url+'jatahcuti/detail',
        add: base_url + 'jatahcuti/add',
		edit: base_url + 'jatahcuti/edit',
		delete: base_url + 'jatahcuti/delete',
    };

    function get_status(data){
        if(data == 1)
            return 'Aktif'
        else
            return 'Blokir'
    }

    function get_jenis(data){
        if(data == 1)
            return 'Cuti Tahunan'
        else if (data == 2)
            return 'Cuti Besar'
        else
            return 'Tidak diketahui'
    }

    //Datetimepicker start
    $('#jtc_validstart').datetimepicker({
        viewMode: 'days',
        format: 'YYYY-MM-DD'
    });
    $('#jtc_validend').datetimepicker({
        viewMode: 'days',
        format: 'YYYY-MM-DD'
    });
    //Datetimepicker end

    var dataTableObj = $("#jatahcuti-table").DataTable({
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
            {   data:"jtc_id",
                render: function(data, type, meta){
                    return '<input type="checkbox" name="jtc_id[]" value="'+ data +'"/>';
                }
            },
            { data:"krw_nama" },
            { 
                data:"jtc_jenis",
                render: function(data, type, meta){
                    return get_jenis(data);
                }
            },
            { data:"jtc_jumlah" },
            { data:"jtc_sisa" },
            { data:"jtc_validstart" },
            { data:"jtc_validend" },
            { 
                data:"jtc_status",
                render: function(data, type, meta){
                    return get_status(data);
                }
            },
            <?php if(check_login_as() != KARYAWAN):?>
            { 
                data:"jtc_id",
                render: function(data, type, meta){
                    return `<button class="btn btn-sm btn-success btn-edit" data-id=${data}><i class="far fa-edit"></i></button>
                            <button class="btn btn-sm btn-danger btn-delete" data-id=${data}><i class="fa fa-trash"></i></button>`;
                }
            }
            <?php endif;?>
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
                    showMessage('error',resp.msg);

                closeForm(form_modal);
                reloadTable(dataTableObj);
            }
        });
    });

    

    

</script>