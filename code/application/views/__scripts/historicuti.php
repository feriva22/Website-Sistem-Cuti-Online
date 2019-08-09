<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<script type="text/javascript">
    var status_approve = <?php echo json_encode($status_approve); ?>;
    var jenis_cuti = <?php echo json_encode($jenis_cuti);?>;
    var pk_key = 'cti_id';
    var form_modal = '#historicuti-modal';
    var form_key = '#historicuti-form';

    var url = {
        data : base_url+'historicuti/get_dataajax'
    };

    var dataTableObj = $("#historicuti-table").DataTable({
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
            { 
                data:"cti_jenis",
                render: function(data, type, meta){
                    return jenis_cuti[data].text;
                }
            },
            { 
                data:"cti_upload",
                visible: false,
                render: function(data,type, meta){
                    return data || 'Tidak ada';
                }
            },
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
                data:"cti_appr_attlstat",
                render: function(data, type, meta){
                    return status_approve[data].label;
                }
            },
            { 
                data:"cti_appr_sdmstat",
                render: function(data, type, meta){
                    return status_approve[data].label;
                }
            }
        ]
    });

</script>