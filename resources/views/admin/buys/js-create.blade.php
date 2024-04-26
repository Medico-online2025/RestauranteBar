<script>
    function success_save_provider(msg = null, type = null, idtipocomprobante = null, last_id = null) 
    {
        toast_msg(msg, type);
        load_providers(idtipocomprobante);
        setTimeout(() => {
            $('#form_save_buy select[name="dni_ruc"]').val(last_id);
            $('#form_save_buy select[name="dni_ruc"]').trigger('change');
        }, 500);
    }

    function success_save_product(msg = null, type = null)
    {
        $('#modalAddProduct').modal('hide');
        toast_msg(msg, type);
        load_products();
    }

    var setTimeOutBuscador = '';
    $('#form_save_buy select[name="dni_ruc"]').select2({
        placeholder: "[SELECCIONE]"
    });

    function load_serie() {
        $.ajax({
            url: "{{ route('admin.load_serie_buy') }}",
            method: 'POST',
            data: {
                '_token': "{{ csrf_token() }}"
            },
            success: function(r) {
                if (!r.status) {
                    toast_msg(r.msg, r.title, r.type);
                    return;
                }

                $(`#form_save_buy select[name="idtipo_comprobante"] option[value="${r.serie.idtipo_documento}"]`)
                    .prop('selected', true);
            },
            dataType: 'json'
        });
    }
    load_serie();

    $('#form_save_buy select[name="idtipo_comprobante"]').on('change', function() {
        let value = $(this).val();
        load_providers(value);
    });

    function load_providers(idtipo_documento) {
        $.ajax({
            url: "{{ route('admin.get_serie_buy') }}",
            method: 'POST',
            data: {
                '_token': "{{ csrf_token() }}",
                idtipo_documento: idtipo_documento
            },
            success: function(r) {
                if (!r.status) {
                    $('#form_save_buy input[name="serie"]').val('');
                    $('#form_save_buy input[name="correlativo"]').val('');
                    toast_msg(r.msg, r.type);
                    return;
                }

                let html_providers = '<option></option>';
                $.each(r.providers, function(index, provider) {
                    html_providers +=
                        `<option value="${provider.id}">${provider.dni_ruc + ' - ' + provider.nombres}</option>`;
                });

                $('#form_save_buy select[name="dni_ruc"]').html(html_providers).select2({
                    placeholder: "[SELECCIONE]",
                    containerCssClass: 'select-sm'
                });
            },
            dataType: 'json'
        });
        return;
    }

    function load_cart() {
        $.ajax({
            url: "{{ route('admin.load_cart_buys') }}",
            method: 'POST',
            data: {
                '_token': "{{ csrf_token() }}"
            },
            success: function(r) {
                if (!r.status) {
                    toast_msg(r.msg, r.title, r.type);
                    return;
                }

                $('#tbody_buys').html(r.html_cart);
                $('#wrapper_totals').html(r.html_totales);
            },
            dataType: 'json'
        });
    }
    load_cart();

    function load_products() {
        $.ajax({
            url: "{{ route('admin.get_products_update') }}",
            method: 'POST',
            data: {
                '_token': "{{ csrf_token() }}"
            },
            success: function(data) {
                let html_products = '<option value=""></option>';
                $.each(data, function(index, product) {
                    html_products +=
                        `<option value="${product.id}">${product.descripcion + ' - S/' + product.precio_compra}</option>`;
                });

                $('#form_save_to_product select[name="product"]').html(html_products);
                $('#form_save_to_product select[name="product"]').select2({
                    dropdownParent: $('#modalAddToProduct .modal-body'),
                    placeholder: "[SELECCIONE]"
                });
            },
            dataType: 'json'
        });
    }

    $('body').on('click', '.btn-add-product', function() {
        event.preventDefault();
        $('#modalAddToProduct').modal('show');
        $('#form_save_to_product select[name="product"]').select2({
            dropdownParent: $('#modalAddToProduct .modal-body'),
            placeholder: "[SELECCIONE]",
        });
    });

    $('#modalAddToProduct select[name="product"]').on('change', function() {
        let value = $(this).val(),
            cantidad = $('#form_save_to_product input[name="cantidad"]').val();
        if (value.trim() == "") {
            return;
        }
        $.ajax({
            url: "{{ route('admin.get_price_product_buy') }}",
            method: "POST",
            data: {
                '_token': "{{ csrf_token() }}",
                id: value
            },
            success: function(r) {
                if (!r.status) {
                    toast_msg(r.msg, r.type);
                    return;
                }
                // Add based on purchase price
                $('#form_save_to_product input[name="precio"]').val(r.product.precio_compra);
            },
            dataType: "json"
        });
    });

    touch_down('#form_save_to_product input[name="cantidad"]', 'product');
    touch_up('#form_save_to_product input[name="cantidad"]', 'product');

    $('body').on('click', '#form_save_to_product .btn-save', function() {
        event.preventDefault();
        let select_product = $('#form_save_to_product select[name="product"]').val(),
            cantidad = parseFloat($('#form_save_to_product input[name="cantidad"]').val()),
            precio = parseFloat($('#form_save_to_product input[name="precio"]').val());
        if (select_product.trim() == "") {
            toast_msg('Debe seleccionar un producto', 'warning');
            return;
        }
        if (cantidad <= 0) {
            toast_msg('Ingrese una cantidad válida', 'warning');
            return;
        }
        if (precio <= 0) {
            toast_msg('Ingrese un precio válido', 'warning');
            return;
        }

        $.ajax({
            url: "{{ route('admin.add_product_buy') }}",
            method: "POST",
            data: {
                '_token': "{{ csrf_token() }}",
                id: select_product,
                cantidad: cantidad,
                precio: precio
            },
            beforeSend: function() {
                $('#form_save_to_product .btn-save').prop('disabled', true);
                $('#form_save_to_product .text-save').addClass('d-none');
                $('#form_save_to_product .text-saving').removeClass('d-none');
            },
            success: function(r) {
                if (!r.status) {
                    $('#form_save_to_product .btn-save').prop('disabled', false);
                    $('#form_save_to_product .text-save').removeClass('d-none');
                    $('#form_save_to_product .text-saving').addClass('d-none');
                    toast_msg(r.msg, r.type);
                    return;
                }

                toast_msg(r.msg, r.type);
                $('#form_save_to_product .btn-save').prop('disabled', false);
                $('#form_save_to_product .text-save').removeClass('d-none');
                $('#form_save_to_product .text-saving').addClass('d-none');
                $('#form_save_to_product input[name="cantidad"]').val('1');
                $('#form_save_to_product input[name="precio"]').val('');
                $('#form_save_to_product select[name="product"]').val('').trigger('change');
                $('#form_save_to_product select[name="product"]').select2({
                    dropdownParent: $('#modalAddToProduct .modal-body'),
                    placeholder: "[SELECCIONE]",
                });
                load_cart();
            },
            dataType: "json"
        });
    });

    $('body').on('click', '.btn-delete-product', function() {
        event.preventDefault();
        let id = $(this).data('id');
        $.ajax({
            url: "{{ route('admin.delete_product_buy') }}",
            method: 'POST',
            data: {
                '_token': "{{ csrf_token() }}",
                id: id
            },
            success: function(r) {
                if (!r.status) {
                    toast_msg(r.msg, r.title, r.type);
                    return;
                }
                load_cart();
            },
            dataType: 'json'
        });
        return;
    });

    $('body').on('click', '.btn-down', function() {
        event.preventDefault();
        let id = $(this).data('id'),
            cantidad = parseInt($(this).data('cantidad')),
            cantidad_enviar = cantidad - 1,
            precio = parseFloat($(this).data('precio'));

        if (cantidad_enviar <= 0) {
            toast_msg('La cantidad no puede ser menor a 1', 'warning');
            return;
        }

        $.ajax({
            url: "{{ route('admin.store_product_buy') }}",
            method: "POST",
            data: {
                '_token': "{{ csrf_token() }}",
                id: id,
                cantidad: cantidad_enviar,
                precio: precio
            },
            success: function(r) {
                if (!r.status) {
                    toast_msg(r.msg, r.type);
                    return;
                }

                toast_msg(r.msg, r.type);
                load_cart();
            },
            dataType: "json"
        });
    });

    $('body').on('click', '.btn-up', function() {
        event.preventDefault();
        let id = $(this).data('id'),
            cantidad = parseInt($(this).data('cantidad')),
            cantidad_enviar = cantidad + 1,
            precio = parseFloat($(this).data('precio'));

        $.ajax({
            url: "{{ route('admin.store_product_buy') }}",
            method: "POST",
            data: {
                '_token': "{{ csrf_token() }}",
                id: id,
                cantidad: cantidad_enviar,
                precio: precio
            },
            success: function(r) {
                if (!r.status) {
                    toast_msg(r.msg, r.type);
                    return;
                }

                toast_msg(r.msg, r.type);
                load_cart();
            },
            dataType: "json"
        });
    });

    $('body').on('change', '.input-update', function() {
        let precio = $(this).val(),
            cantidad = $(this).data('cantidad'),
            id = $(this).data('id');

        if (precio.trim() == '') {
            return;
        }
        if (isNaN(precio)) {
            toast_msg('Solo se permiten números', 'warning');
            $(this).focus();
            return;
        }

        $.ajax({
            url: "{{ route('admin.store_product_buy') }}",
            method: 'POST',
            data: {
                '_token': "{{ csrf_token() }}",
                id: id,
                cantidad: cantidad,
                precio: precio
            },
            success: function(r) {
                if (!r.status) {
                    load_cart();
                    toast_msg(r.msg, r.type);
                    return;
                }

                toast_msg(r.msg, r.type);
                load_cart();
            },
            dataType: 'json'
        });
    });

    $('body').on('click', '#form_save_buy .btn-save', function() {
        event.preventDefault();
        let form = $('#form_save_buy').serialize(),
            serie = $('#form_save_buy input[name="serie"]'),
            correlativo = $('#form_save_buy input[name="correlativo"]');


        if(serie.val() == '' || correlativo.val() == '')
        {
            toast_msg('Complete los campos', 'warning');
        }

        if (serie.val() == '')
            serie.addClass('is-invalid');
        else 
            serie.removeClass('is-invalid');

        if (correlativo.val() == '')
            correlativo.addClass('is-invalid');
        else
            correlativo.removeClass('is-invalid');


        if (serie.val() != '' && correlativo.val() != '') {
            $.ajax({
                url: "{{ route('admin.save_buy') }}",
                method: "POST",
                data: form,
                beforeSend: function() {
                    $('#form_save_buy .btn-save').prop('disabled', true);
                    $('#form_save_buy .text-save').addClass('d-none');
                    $('#form_save_buy .text-saving').removeClass('d-none');
                },
                success: function(r) {
                    if (!r.status) {
                        $('#form_save_buy .btn-save').prop('disabled', false);
                        $('#form_save_buy .text-save').removeClass('d-none');
                        $('#form_save_buy .text-saving').addClass('d-none');
                        $(`input[name="${r.invalid}"]`).addClass('is-invalid');
                        toast_msg(r.msg, r.type);
                        return;
                    }

                    $(`input[name="serie"]`).removeClass('is-invalid');
                    $(`input[name="correlativo"]`).removeClass('is-invalid');
                    $('#form_save_buy .btn-save').prop('disabled', false);
                    $('#form_save_buy .text-save').removeClass('d-none');
                    $('#form_save_buy .text-saving').addClass('d-none');
                    $('#form_save_buy select[name="idtipo_comprobante"] option[value="1"]').prop(
                        'selected', true);
                    $('#form_save_buy input[name="serie"]').val("");
                    $('#form_save_buy input[name="correlativo"]').val("");
                    $('#form_save_buy select[name="modo_pago"] option[value="1"]').prop('selected',
                        true);
                    load_alerts();
                    load_providers(r.idtipo_comprobante);
                    load_cart();
                    
                    let pdf = `{{ asset('files/buys/${r.pdf}') }}`;
                    var iframe = document.createElement('iframe');
                    iframe.style.display = "none";
                    iframe.src = pdf;
                    document.body.appendChild(iframe);
                    iframe.contentWindow.focus();
                    iframe.contentWindow.print();
                },
                dataType: "json"
            });
        }
    });
</script>