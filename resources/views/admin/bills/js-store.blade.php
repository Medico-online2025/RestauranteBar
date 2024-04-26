<script>
    $('body').on('click', '.btn-create', function()
    {
        event.preventDefault();
        $('#form_save select[name="idpurchase_description"]').select2({
            placeholder     : "[SELECCIONE]",
            dropdownParent  : $('#modalAddBill')
        });
        $('#modalAddBill').modal('show');
    });

    $('#form_save input[name="monto"]').on('keyup', function(){
        let value   = $(this).val();
        if(isNaN(value) || value.trim() == "")
        {
            $('#form_save input[name="monto"]').val('');
            return;
        }
    });

    $('body').on('click', '.btn-save', function()
    {
        event.preventDefault();
        let form      = $('#form_save').serialize(),
            monto     = $('input[name="monto"]');

        if(monto.val() == '')
            monto.addClass('is-invalid');
        else
            monto.removeClass('is-invalid');

        if(monto.val().trim() != '')
        {
            $.ajax({
                url         :  "{{ route('admin.save_bill') }}",
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

                    $('#modalAddBill').modal('hide');
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
                    url         : "{{ route('admin.delete_bill') }}",
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