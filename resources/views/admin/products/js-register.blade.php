<script>
    $('body').on('click', '.btn-create-product', function()
    {
        event.preventDefault();
        $('#form_save_product select[name="idunidad"]').val('61').trigger('change');
        $('#form_save_product select[name="operacion"] option[value="10"]').prop('selected', true);
        $('#form_save_product select[name="idunidad"]').select2({
            placeholder     : "[SELECCIONE]",
            dropdownParent  : $('#modalAddProduct')
        });
        $('#modalAddProduct').modal('show');
    });

    $('#form_save_product input[name="check_stock"]').on('click', function()
    {
        if( $(this).is(':checked') )
        {
            $('#form_save_product input[name="stock"]').prop('disabled', false);
            $('#form_save_product input[name="stock"]').attr('placeholder', 'Ingrese la cantidad');
            $('#form_save_product input[name="stock"]').focus();
        }
        else
        {
            $('#form_save_product input[name="stock"]').prop('disabled', true);
            $('#form_save_product input[name="stock"]').attr('placeholder', '');
            $('#form_save_product input[name="stock"]').val('');
        }
    });

    $('#form_save_product input[name="stock"]').on('keyup', function()
    {
        let value = $(this).val();
        if(isNaN(value))
        {
            $('#form_save_product input[name="stock"]').val('');
            return;
        }
    });

    $('body').on('click', '.btn-save-product', function()
    {
        event.preventDefault();
        let form            = $('#form_save_product').serialize(),
            descripcion     = $('#form_save_product input[name="descripcion"]'),
            precio_compra   = $('#form_save_product input[name="precio_compra"]'),
            precio_venta    = $('#form_save_product input[name="precio_venta"]'),
            idunidad        = $('#form_save_product select[name="idunidad"]');

        if(descripcion.val() == '')
            descripcion.addClass('is-invalid');
        else
            descripcion.removeClass('is-invalid');

        if(precio_compra.val() == '')
            precio_compra.addClass('is-invalid');
        else
            precio_compra.removeClass('is-invalid');

        if(precio_venta.val() == '')
            precio_venta.addClass('is-invalid');
        else
            precio_venta.removeClass('is-invalid');

        if(idunidad.val() == '')
            idunidad.addClass('is-invalid');
        else
            idunidad.removeClass('is-invalid');

        if(descripcion.val().trim() != '' && precio_compra.val().trim() != '' && precio_venta.val().trim() != '' && idunidad.val().trim() != '')
        {
            $.ajax({
                url         :  "{{ route('admin.save_product') }}",
                method      : 'POST',
                data        : form,
                beforeSend  : function(){
                    $('#form_save_product .btn-save-product').prop('disabled', true);
                    $('#form_save_product .text-save-product').addClass('d-none');
                    $('#form_save_product .text-saving-product').removeClass('d-none');
                },
                success     : function(r)
                {
                    if(!r.status)
                    {
                        $('#form_save_product .btn-save-product').prop('disabled', false);
                        $('#form_save_product .text-save-product').removeClass('d-none');
                        $('#form_save_product .text-saving-product').addClass('d-none');
						toast_msg(r.msg, r.type);
                        return;
                    }
                    
                    load_alerts();
                    $('#form_save_product').trigger('reset');
                    $('#form_save_product .btn-save-product').prop('disabled', false);
                    $('#form_save_product .text-save-product').removeClass('d-none');
                    $('#form_save_product .text-saving-product').addClass('d-none');
                    $('#form_save_product input[name="stock"]').prop('disabled', true);
                    $('#form_save_product input[name="stock"]').attr('placeholder', '');
                    $('#form_save_product input[name="stock"]').val('');
                    $('#modalAddProduct').modal('hide');
                    success_save_product(r.msg, r.type);
                },
                dataType    : 'json'
            });
            return;
        }
    });
</script>