<script>
    var setTimeOutBuscador = '';

    function open_modal_client()
    {}

    function success_save_client(msg = null, type = null, idtipocomprobante = null, last_id = null) 
    {
        toast_msg(msg, type);
        load_clients();
        setTimeout(() => {
            $('#form_save_sale_note select[name="dni_ruc"]').val(last_id);
            $('#form_save_sale_note select[name="dni_ruc"]').trigger('change');
        }, 500);
    }

    function success_save_product(msg = null, type = null)
    {
        $('#modalAddProduct').modal('hide');
        toast_msg(msg, type);
        load_products();
    }

    function load_clients() {
        $.ajax({
            url: "{{ route('admin.get_clients_update') }}",
            method: 'POST',
            data: {
                '_token': "{{ csrf_token() }}"
            },
            success: function(r) {
                let html_clients = '<option></option>';
                $.each(r, function(index, client) {
                    html_clients +=
                        `<option value="${client.id}">${client.dni_ruc + ' - ' + client.nombres}</option>`;
                });

                $('#form_save_sale_note select[name="dni_ruc"]').html(html_clients).select2({
                    placeholder: "[SELECCIONE]"
                });
            },
            dataType: 'json'
        });
        return;
    }

    $('#form_save_sale_note select[name="dni_ruc"]').select2({
        placeholder: "[SELECCIONE]"
    });

    function load_serie() {
        $.ajax({
            url: "{{ route('admin.load_serie_sale_note') }}",
            method: 'POST',
            data: {
                '_token': "{{ csrf_token() }}"
            },
            success: function(r) {
                if (!r.status) {
                    toast_msg(r.msg, r.title, r.type);
                    return;
                }

                $('#form_save_sale_note input[name="serie"]').val(r.serie.serie);
                $('#form_save_sale_note input[name="correlativo"]').val(r.serie.correlativo);
            },
            dataType: 'json'
        });
    }
    load_serie();

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
            url: "{{ route('admin.load_cart_sale_notes') }}",
            method: 'POST',
            data: {
                '_token': "{{ csrf_token() }}"
            },
            success: function(r) {
                if (!r.status) {
                    toast_msg(r.msg, r.title, r.type);
                    return;
                }

                $('#tbody_sale_notes').html(r.html_cart);
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
            url: "{{ route('admin.add_product_sale_note') }}",
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
            url: "{{ route('admin.delete_product_sale_note') }}",
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
            url: "{{ route('admin.store_product_sale_note') }}",
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
            url: "{{ route('admin.store_product_sale_note') }}",
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
            url: "{{ route('admin.store_product_sale_note') }}",
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

    $('body').on('click', '#form_save_sale_note .btn-save', function() {
        event.preventDefault();
        let form = $('#form_save_sale_note').serialize();
            $.ajax({
                url: "{{ route('admin.save_sale_note') }}",
                method: "POST",
                data: form,
                beforeSend: function() {
                    $('#form_save_sale_note .btn-save').prop('disabled', true);
                    $('#form_save_sale_note .text-save').addClass('d-none');
                    $('#form_save_sale_note .text-saving').removeClass('d-none');
                },
                success: function(r) {
                    if (!r.status) {
                        $('#form_save_sale_note .btn-save').prop('disabled', false);
                        $('#form_save_sale_note .text-save').removeClass('d-none');
                        $('#form_save_sale_note .text-saving').addClass('d-none');
                        $(`input[name="${r.invalid}"]`).addClass('is-invalid');
                        toast_msg(r.msg, r.type);
                        return;
                    }

                    $('#form_save_sale_note .btn-save').prop('disabled', false);
                    $('#form_save_sale_note .text-save').removeClass('d-none');
                    $('#form_save_sale_note .text-saving').addClass('d-none');
                    $('#form_save_sale_note select[name="modo_pago"] option[value="1"]').prop('selected', true);
                    load_alerts();
                    load_serie();
                    load_clients();
                    load_cart();

                    // View ticket
                    let pdf                 =   `{{ asset('files/sale-notes/ticket/${r.pdf}') }}`;
                    var iframe              = document.createElement('iframe');
                    iframe.style.display    = "none";
                    iframe.src              = pdf;
                    document.body.appendChild(iframe);
                    iframe.contentWindow.focus();
                    iframe.contentWindow.print();
                },
                dataType: "json"
            });
    });
</script>