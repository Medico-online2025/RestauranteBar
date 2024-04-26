<script>
    $('body').on('click', '.btn-create', function()
    {
        event.preventDefault();
        $('#modalAddSerie').modal('show');
    });

    $('body').on('click', '.btn-save', function()
    {
        event.preventDefault();
        let form            = $('#form_save').serialize(),
            serie           = $('input[name="serie"]'),
            correlativo     = $('input[name="correlativo"]');

        if(serie.val() == '')
            serie.addClass('is-invalid');
        else
            serie.removeClass('is-invalid');

        if(correlativo.val() == '')
            correlativo.addClass('is-invalid');
        else
            correlativo.removeClass('is-invalid');

        if(serie.val().trim() != '' && correlativo.val().trim() != '')
        {
            $.ajax({
                url         :  "{{ route('admin.save_serie') }}",
                method      : 'POST',
                data        : form,
                beforeSend  : function(){
                    $('.btn-save').prop('disabled', true);
                    $('.text-saving').removeClass('d-none');
                    $('.text-save').addClass('d-none');
                },
                success     : function(r)
                {
                    if(!r.status)
                    {
                        $('.btn-save').prop('disabled', false);
                        $('.text-saving').addClass('d-none');
                        $('.text-save').removeClass('d-none');
						toast_msg(r.msg, r.type);
                        return;
                    }

                    $('#modalAddSerie').modal('hide');
                    $('#form_save').trigger('reset');
                    $('.btn-save').prop('disabled', false);
                    $('.text-save').removeClass('d-none');
                    $('.text-saving').addClass('d-none');
                    toast_msg(r.msg, r.type);
                    reload_table();
                },
                dataType    : 'json'
            });
            return;
        }
    });

    $('body').on('click', '.btn-detail', function()
    {
        event.preventDefault();
        let id  = $(this).data('id');
        $.ajax({
            url         : "{{ route('admin.detail_serie') }}",
            method      : 'POST',
            beforeSend  : function(){
                block_content('#layout-content');
            },
            data        : {'_token' : "{{ csrf_token() }}",id: id},
            success     : function(r){
                if(!r.status)
                {
                    close_block('#layout-content');
                    toast_msg(r.msg, r.type);
                    return;
                }

                close_block('#layout-content');
                $('#form_edit input[name="id"]').val(r.serie.id);
                $('#form_edit input[name="serie"]').val(r.serie.serie);
                $('#form_edit input[name="correlativo"]').val(r.serie.correlativo);
                $(`#form_edit select[name="tipo_documento"] option[value="${r.serie.idtipo_documento}"]`).prop('selected', true);
                $(`#form_edit select[name="idcaja"] option[value="${r.serie.idcaja}"]`).prop('selected', true);
                $('#modalEditSerie').modal('show');
            },
            dataType    : 'json'
        });
        return;
    });

    $('body').on('click', '.btn-store', function()
    {
        event.preventDefault();
        let form            = $('#form_edit').serialize(),
            serie           = $('#form_edit input[name="serie"]'),
            correlativo     = $('#form_edit input[name="correlativo"]');

        if(serie.val() == '')
            serie.addClass('is-invalid');
        else
            serie.removeClass('is-invalid');

        if(correlativo.val() == '')
            correlativo.addClass('is-invalid');
        else
            correlativo.removeClass('is-invalid');

        if(serie.val().trim() != '' && correlativo.val().trim() != '')
        {
            $.ajax({
                url         :  "{{ route('admin.store_serie') }}",
                method      : 'POST',
                data        : form,
                beforeSend  : function(){
                    $('.btn-store').prop('disabled', true);
                    $('.text-store').addClass('d-none');
                    $('.text-storing').removeClass('d-none');
                },
                success     : function(r)
                {
                    if(!r.status)
                    {
                        $('.btn-store').prop('disabled', false);
                        $('.text-store').removeClass('d-none');
                        $('.text-storing').addClass('d-none');
                        toast_msg(r.msg, r.type);
                        return;
                    }

                    $('#modalEditSerie').modal('hide');
                    $('.btn-store').prop('disabled', false);
                    $('.text-store').removeClass('d-none');
                    $('.text-storing').addClass('d-none');
                    toast_msg(r.msg, r.type);
                    reload_table();
                },
                dataType    : 'json'
            });
            return;
        }
    });

    $('body').on('click', '.btn-confirm', function()
    {
        event.preventDefault();
        let id      = $(this).data('id');
        Swal.fire({
            title: 'Eliminar',
            text: "¿Desea eliminar el registro?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Si, eliminar',
            cancelButtonText: 'Cancelar',
            customClass: {
                confirmButton: 'btn btn-primary',
                cancelButton: 'btn btn-outline-danger ml-1'
            },
            buttonsStyling: false
        }).then(function (result) {
            if (result.value) 
            {
                $.ajax({
                    url         : "{{ route('admin.delete_serie') }}",
                    method      : 'POST',
                    data        : {
                        '_token': "{{ csrf_token() }}",
                        id      : id
                    },
                    success     : function(r){
                        if(!r.status)
                        {
                            toast_msg(r.msg, r.type);
                            return;
                        }

                        toast_msg(r.msg, r.type);
                        reload_table();
                    },
                    dataType    : 'json'
                });
            }
        });
    });
</script>