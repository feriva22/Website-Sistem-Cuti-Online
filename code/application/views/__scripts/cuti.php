<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<script type="text/javascript">
    var status_approve = <?php echo json_encode($status_approve); ?>;
    
    var pk_key = 'cti_id';
    var form_modal = '#cuti-modal';
    var form_key = '#cuti-form';

    var url = {
        data : base_url+'cuti/get_dataajax',
        approve : base_url+'cuti/set_approve',
        reject : base_url+'cuti/set_reject'
    };

    //Datetimepicker start
    $('#cti_mulai').datetimepicker({
        viewMode: 'days',
        format: 'YYYY-MM-DD'
    });

    $('#cti_selesai').datetimepicker({
        viewMode: 'days',
        format: 'YYYY-MM-DD'
    });
    //Datetimepicker end

    var dataTableObj = $("#cuti-table").DataTable({
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
            {   data:"cti_id",
                render: function(data, type, meta){
                    return '<input type="checkbox" name="cti_id[]" value="'+ data +'"/>';
                }
            },
            { data:"krw_nama" },
            { data:"cti_tglpengajuan" },
            { data:"cti_hari" },
            { data:"cti_mulai" },
            { data:"cti_selesai" },
            { data:"cti_alasan" },
            { 
                data:"cti_appr_atlstat",
                render: function(data, type, meta){
                    return status_approve[data].label;
                }
            },
            { 
                data:"cti_appr_sdmstat",
                render: function(data, type, meta){
                    return status_approve[data].label;
                }
            },
            { 
                data:"cti_appr_attlstat",
                render: function(data, type, meta){
                    return status_approve[data].label;
                }
            }
            <?php if($login_as != ADMIN):?>
            ,
            { 
                data:"cti_id",
                render: function(data, type, meta){

                    return `<button class="btn btn-sm btn-info btn-approve" data-id=${data}><i class="far fa-thumbs-up"></i></button>
                            <button class="btn btn-sm btn-danger btn-reject" data-id=${data}><i class="far fa-thumbs-down"></i></button>`;
                }
            }
            <?php endif;?>
        ]
    });

    $("body").on('click',".btn-approve",function(e){
        e.preventDefault();
        /* ajax request ke server */
        var submitted_data = {};
        submitted_data[pk_key] = $(this).data('id');
        $.ajax({
            url: url.approve,
            type: 'POST',
            dataType: "json",
            data : submitted_data,
            success : function(resp){
                console.log(resp);
                if(typeof(resp) !== 'undefined' && typeof(resp) === 'object'){
                    reloadTable(dataTableObj);
                    
                if(resp.status == 'ok')
                    showMessage('success',resp.msg);
                else
                    showMessage('error',resp.msg);
                }
            }
        });
    });

    $("body").on('click',".btn-reject",function(e){
        e.preventDefault();
        /* ajax request ke server */
        var submitted_data = {};
        submitted_data[pk_key] = $(this).data('id');
        $.ajax({
            url: url.reject,
            type: 'POST',
            dataType: "json",
            data : submitted_data,
            success : function(resp){
                console.log(resp);
                if(typeof(resp) !== 'undefined' && typeof(resp) === 'object'){
                    reloadTable(dataTableObj);
                    
                if(resp.status == 'ok')
                    showMessage('success',resp.msg);
                else
                    showMessage('error',resp.msg);
                }
            }
        });
    });
</script>