<script>
    function success_save_product(msg = null, type = null)
    {
        toast_msg(msg, type);
        reload_table();
    }

    $('body').on('click', '.btn-detail', function()
    {
        event.preventDefault();
        let id      = $(this).data('id');
        $.ajax({
            url         : "{{ route('admin.detail_product') }}",
            method      : 'POST',
            data        : {'_token' : "{{ csrf_token() }}",id: id},
            beforeSend  : function(){
                block_content('#layout-content');
            },
            success     : function(r){
                if(!r.status)
                {
                    close_block('#layout-content');
                    toast_msg(r.msg, r.title, r.type);
                    return;
                }
                close_block('#layout-content');
                let html_type_inafectos = '',
                    html_unidades       = '<option></option>';

                $('#form_edit_product input[name="id"]').val(r.product.id);
                $('#form_edit_product input[name="descripcion"]').val(r.product.descripcion);

                $.each(r.unidades, function(index, unidad){
                    if(unidad.id == r.product.idunidad)
                    html_unidades += `<option value="${unidad.id}" selected>${unidad.descripcion}</option>`;
                    else
                    html_unidades += `<option value="${unidad.id}">${unidad.descripcion}</option>`;
                });

                $.each(r.type_inafectos, function(index, type_inafecto){
                    if(type_inafecto.id == r.product.idcodigo_igv)
                    html_type_inafectos += `<option value="${type_inafecto.id}" selected>${type_inafecto.descripcion}</option>`;
                    else
                    html_type_inafectos += `<option value="${type_inafecto.id}">${type_inafecto.descripcion}</option>`;
                });

                $('#form_edit_product select[name="idunidad"]').html(html_unidades).select2({
                    placeholder     : "[SELECCIONE]",
                    dropdownParent  : $('#modalEditProduct')
                });
                
                $('#form_edit_product select[name="operacion"]').html(html_type_inafectos);
                $('#form_edit_product input[name="precio_venta"]').val(r.product.precio_venta);
                $('#form_edit_product input[name="precio_compra"]').val(r.product.precio_compra);
                $('#form_edit_product input[name="codigo_barras"]').val(r.product.codigo_barras);
                $('#form_edit_product input[name="codigo_interno"]').val(r.product.codigo_interno);
                if(r.product.stock == null)
                {
                    $(`#form_edit_product input[name="check_stock"]`).prop('checked', false);
                    $(`#form_edit_product input[name="stock"]`).prop('disabled', true);
                    $('#form_edit_product input[name="stock"]').val('');
                }
                else
                {
                    $(`#form_edit_product input[name="check_stock"]`).prop('checked', true);
                    $(`#form_edit_product input[name="stock"]`).prop('disabled', false);
                    $('#form_edit_product input[name="stock"]').val(r.product.stock);
                }

                $('#form_edit_product input[name="marca"]').val(r.product.marca);
                $('#form_edit_product input[name="presentacion"]').val(r.product.presentacion);
                $('#form_edit_product input[name="fecha_vencimiento"]').val(r.product.fecha_vencimiento);
                $(`#form_edit_product input[name="opcion"][value="${r.product.opcion}"]`).prop("checked", true);
                $('#modalEditProduct').modal('show');
            },
            dataType    : 'json'
        });
        return;
    });

    $('#form_edit_product input[name="stock"]').on('keyup', function()
    {
        let value = $(this).val();
        if(isNaN(value))
        {
            $('#form_edit_product input[name="stock"]').val('');
            return;
        }
    });

    $('#form_edit_product input[name="check_stock"]').on('click', function()
    {
        if( $(this).is(':checked') )
        {
            $('#form_edit_product input[name="stock"]').prop('disabled', false);
            $('#form_edit_product input[name="stock"]').attr('placeholder', 'Ingrese la cantidad');
            $('#form_edit_product input[name="stock"]').focus();
        }
        else
        {
            $('#form_edit_product input[name="stock"]').prop('disabled', true);
            $('#form_edit_product input[name="stock"]').attr('placeholder', '');
            $('#form_edit_product input[name="stock"]').val('');
        }
    });

    $('body').on('click', '.btn-store-product', function()
    {
        event.preventDefault();
        let form            = $('#form_edit_product').serialize(),
            descripcion     = $('#form_edit_product input[name="descripcion"]'),
            precio_compra   = $('#form_edit_product input[name="precio_compra"]'),
            idunidad        = $('#form_edit_product select[name="idunidad"]');

            if(descripcion.val().trim() == '')
                descripcion.addClass('is-invalid');
            else
                descripcion.removeClass('is-invalid');

            if(precio_compra.val().trim() == '')
                precio_compra.addClass('is-invalid');
            else
                precio_compra.removeClass('is-invalid');

            if(idunidad.val().trim() == '')
                idunidad.addClass('is-invalid');
            else
                idunidad.removeClass('is-invalid');


            if(descripcion.val().trim() != '' && precio_compra.val().trim() != '' && idunidad.val().trim() != '')
            {
                $.ajax({
                    url         : "{{ route('admin.store_product') }}",
                    method      : 'POST',
                    data        : form,
                    beforeSend  : function()
                    {
                        $('.btn-store-product').prop('disabled', true);
                        $('.text-store-product').addClass('d-none');
                        $('.text-storing-product').removeClass('d-none');
                    },
                    success     : function(r)
                    {
                        if(!r.status)
                        {
                            $('.btn-store-product').prop('disabled', false);
                            $('.text-store-product').removeClass('d-none');
                            $('.text-storing-product').addClass('d-none');
                            toast_msg(r.msg, r.type);
                            return;
                        }

                        $('.btn-store-product').prop('disabled', false);
                        $('.text-store-product').removeClass('d-none');
                        $('.text-storing-product').addClass('d-none');
                        $('#form_edit_product input[name="stock"]').attr('placeholder', '');
                        $('#modalEditProduct').modal('hide');
                        toast_msg(r.msg, r.type);
                        load_alerts();
                        reload_table();
                    },
                    dataType    : 'json'
                });
            }
    });

    $('body').on('click', '.btn-view', function()
    {
        event.preventDefault();
        let id          = $(this).data('id');
        $.ajax({
            url         : "{{ route('admin.view_stocks') }}",
            method      : "POST",
            data        : {
                '_token': "{{ csrf_token() }}",
                id      : id
            },
            beforeSend  : function(){
                block_content('#layout-content');
            },
            success     : function(r){
                if(!r.status)
                {
                    close_block('#layout-content');
                    toast_msg(r.msg, r.type);
                    return;
                }
                close_block('#layout-content');
                $('.detail-code').html(r.data.codigo);
                $('.detail-description').html(r.data.descripcion);
                $('.detail-brand').html(r.data.marca);
                $('.detail-presentation').html(r.data.presentacion);
                $('.detail-buy').html(r.data.precio_compra);
                $('.detail-sale').html(r.data.precio_venta);
                $('.detail-stock').html(r.data.stock);
                $('.detail-expiration').html(r.data.fecha_vencimiento);
                $('#modalDetailProduct').offcanvas('show');
            },
            dataType    : "json"
        });
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
                    url         : "{{ route('admin.delete_product') }}",
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

    $('body').on('click', '.btn-upload', function()
    {
        event.preventDefault();
        $('#modalUpload').modal('show');
    });

    $('body').on('click', '.btn-upload-product', function(){
        event.preventDefault();
        let form    = new FormData($('#form_excel')[0]);
        $.ajax({
            url         : "{{ route('admin.upload_excel') }}",
            method      : "POST",
            data        : form,
            cache       : false,
            contentType : false,
            processData : false,
            beforeSend  : function(){
                $('.btn-upload-product').prop('disabled', true);
                $('.text-upload-product').addClass('d-none');
                $('.text-uploads-product').removeClass('d-none');
            },
            success     : function(r){
                if(!r.status)
                {
                    $('.btn-upload-product').prop('disabled', false);
                    $('.text-upload-product').removeClass('d-none');
                    $('.text-uploads-product').addClass('d-none');
                    toast_msg(r.msg, r.type);
                    return;
                }
                
                $('.btn-upload-product').prop('disabled', false);
                $('.text-upload-product').removeClass('d-none');
                $('.text-uploads-product').addClass('d-none');
                $('#form_excel').trigger('reset');
                $('#modalUpload').modal('hide');
                load_alerts();
                toast_msg(r.msg, r.type);
                reload_table();
            },
            dataType    : "json"
        });
    });
</script>