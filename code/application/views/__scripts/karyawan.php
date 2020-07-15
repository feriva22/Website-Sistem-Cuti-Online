<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<script type="text/javascript">
    var status_level = <?php echo json_encode($status_level); ?>;
    var pk_key = 'krw_id';
    var form_modal = '#karyawan-modal';
    var form_key = '#karyawan-form';

    var url = {
        data : base_url+'karyawan/get_dataajax',
        detail : base_url+'karyawan/detail',
        add: base_url + 'karyawan/add',
		edit: base_url + 'karyawan/edit',
		delete: base_url + 'karyawan/delete',
    };

     //Datetimepicker start
     $('#krw_tgllahir').datetimepicker({
        viewMode: 'days',
        format: 'YYYY-MM-DD'
    });
    $('#krw_tglmasuk').datetimepicker({
        viewMode: 'days',
        format: 'YYYY-MM-DD'
    });
     //Datetimepicker end

    var dataTableObj = $("#karyawan-table").DataTable({
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
            {   data:"krw_id",
                render: function(data, type, meta){
                    return '<input type="checkbox" name="krw_id[]" value="'+ data +'"/>';
                }
            },
            { data:"krw_username" },
            { data:"krw_email" },
            { data:"krw_nama" },
            { data:"krw_nik" },
            {   data:"krw_tgllahir",
                visible: false 
            },
            {   data:"krw_jeniskelamin",
                visible: false
            },
            {   data:"krw_alamat",
                visible: false 
            },
            {   data:"krw_agama",
                visible: false 
            },
            {   data:"krw_foto",
                visible: false 
            },
            {   data:"krw_tglmasuk",
                visible: false 
            },  //tgl masuk
            {   data:"dvs_nama",
                visible: true,
                render: function(data,type,meta){
                    return data || 'Tidak Ada';
                } 
            },
            {   data:"jbt_nama",
                visible: true 
            },
            { 
                data:"krw_level",
                render: function(data, type, meta){
                    return status_level[data].text;
                }
            },
            { 
                data:"krw_id",
                render: function(data, type, meta){
                    return `<button class="btn btn-sm btn-success btn-edit" data-id=${data}><i class="far fa-edit"></i></button>
                            <button class="btn btn-sm btn-danger btn-delete" data-id=${data}><i class="fa fa-trash"></i></button>`;
                }
            }
        ]
    });

    $("body").on('click',".btn-add",function(){
        resetForm(form_key);
        showForm(form_modal,false);
    });

    $("body").on('click',".btn-edit",function(){
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

    $("body").on('click',".btn-delete",function(){
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

    $("body").on('click',"#submit-btn",function(){
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