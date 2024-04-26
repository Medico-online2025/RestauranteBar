<script>
    var setTimeOutBuscador = '';

    function open_modal_client()
    {}

    function success_save_client(msg = null, type = null, idtipocomprobante = null, last_id = null) 
    {
        toast_msg(msg, type);
        load_clients(idtipocomprobante);
        setTimeout(() => {
            $('#form_save_quote select[name="dni_ruc"]').val(last_id);
            $('#form_save_quote select[name="dni_ruc"]').trigger('change');
        }, 500);
    }

    function success_save_product(msg = null, type = null)
    {
        $('#modalAddProduct').modal('hide');
        toast_msg(msg, type);
        load_products();
    }

    $('#form_save_quote select[name="dni_ruc"]').select2({
        placeholder: "[SELECCIONE]"
    });

    function load_clients(idtipo_documento) {
        $.ajax({
            url: "{{ route('admin.get_serie_quote') }}",
            method: 'POST',
            data: {
                '_token': "{{ csrf_token() }}",
                idtipo_documento: idtipo_documento
            },
            success: function(r) {
                if (!r.status) {
                    toast_msg(r.msg, r.type);
                    return;
                }

                let html_clients = '<option></option>';
                $.each(r.clients, function(index, client) {
                    html_clients +=
                        `<option value="${client.id}">${client.dni_ruc + ' - ' + client.nombres}</option>`;
                });

                $('#form_save_quote select[name="dni_ruc"]').html(html_clients).select2({
                    placeholder: "[SELECCIONE]"
                });
            },
            dataType: 'json'
        });
        return;
    }

    $('#form_save_quote select[name="idtipo_comprobante"]').on('change', function() {
        let value = $(this).val();
        load_clients(value);
    });

    function load_products() {
        $.ajax({
            url: "{{ route('admin.get_products_update_q') }}",
            method: 'POST',
            data: {
                '_token': "{{ csrf_token() }}"
            },
            success: function(data) {
                let html_products = '<option value=""></option>';
                $.each(data, function(index, product) {
                    html_products +=
                        `<option value="${product.id}">${product.descripcion + ' - S/' + product.precio_venta}</option>`;
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
            url: "{{ route('admin.get_price_product_quote') }}",
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
                $('#form_save_to_product input[name="precio"]').val(r.product.precio_venta);
            },
            dataType: "json"
        });
    });

    touch_down('#form_save_to_product input[name="cantidad"]', 'product');
    touch_up('#form_save_to_product input[name="cantidad"]', 'product');

    function load_cart() {
        $.ajax({
            url: "{{ route('admin.load_cart_quotes') }}",
            method: 'POST',
            data: {
                '_token': "{{ csrf_token() }}"
            },
            success: function(r) {
                if (!r.status) {
                    toast_msg(r.msg, r.title, r.type);
                    return;
                }

                $('#tbody_quotes').html(r.html_cart);
                $('#wrapper_totals').html(r.html_totales);
            },
            dataType: 'json'
        });
    }
    load_cart();

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
            url: "{{ route('admin.add_product_quote') }}",
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
            url: "{{ route('admin.delete_product_quote') }}",
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
            url: "{{ route('admin.store_product_quote') }}",
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
            url: "{{ route('admin.store_product_quote') }}",
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
            url: "{{ route('admin.store_product_quote') }}",
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

    $('body').on('click', '#form_save_quote .btn-save', function() {
        event.preventDefault();
        let form = $('#form_save_quote').serialize();
            $.ajax({
                url: "{{ route('admin.save_quote') }}",
                method: "POST",
                data: form,
                beforeSend: function() {
                    $('#form_save_quote .btn-save').prop('disabled', true);
                    $('#form_save_quote .text-save').addClass('d-none');
                    $('#form_save_quote .text-saving').removeClass('d-none');
                },
                success: function(r) {
                    if (!r.status) {
                        $('#form_save_quote .btn-save').prop('disabled', false);
                        $('#form_save_quote .text-save').removeClass('d-none');
                        $('#form_save_quote .text-saving').addClass('d-none');
                        $(`input[name="${r.invalid}"]`).addClass('is-invalid');
                        toast_msg(r.msg, r.type);
                        return;
                    }

                    $('#form_save_quote .btn-save').prop('disabled', false);
                    $('#form_save_quote .text-save').removeClass('d-none');
                    $('#form_save_quote .text-saving').addClass('d-none');
                    $('#form_save_quote select[name="idtipo_comprobante"] option[value="1"]').prop(
                        'selected', true);
                    $('#form_save_quote select[name="modo_pago"] option[value="1"]').prop('selected',
                        true);
                    load_clients(r.idtipo_comprobante);
                    load_cart();
                    
                    let pdf = `{{ asset('files/quotes/${r.pdf}') }}`;
                    var iframe = document.createElement('iframe');
                    iframe.style.display = "none";
                    iframe.src = pdf;
                    document.body.appendChild(iframe);
                    iframe.contentWindow.focus();
                    iframe.contentWindow.print();
                },
                dataType: "json"
            });
    });

</script>