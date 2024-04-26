<script>
    function open_modal_client() {}

    function success_save_client(msg = null, type = null, idtipocomprobante = null, last_id = null) {
        toast_msg(msg, type);
        load_clients(idtipocomprobante);
        setTimeout(() => {
            $('#form_save_quote select[name="dni_ruc"]').val(last_id);
            $('#form_save_quote select[name="dni_ruc"]').trigger('change');
        }, 500);
    }

    function success_save_product(msg = null, type = null) {
        $('#modalAddProduct').modal('hide');
        toast_msg(msg, type);
        load_products();
    }

    $('#form_save_quote select[name="dni_ruc"]').val("{{ $quote->idcliente }}").select2({
        placeholder: "[SELECCIONE]"
    });

    $('#form_save_quote select[name="idtipo_comprobante"] option[value="{{ $quote->idtipo_comprobante }}"]').prop(
        'selected', true);
    $('#form_save_quote select[name="modo_pago"] option[value="{{ $quote->modo_pago }}"]').prop('selected', true);

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

        $('#form_save_to_product select[name="idalmacen"]').select2({
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
            url: "{{ route('admin.get_product_quote_update') }}",
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

                sum_product(r.producto, r.cantidad);
            },
            dataType: "json"
        });
    });

    // Functions news
    function sum_product(producto, cantidad_entra) {
        $('#wrapper-tbody').each(function() {
            let html__new = '',
                id = $(this).find(`#tr__product__` + producto.id).find('input[name="idproducto"]'),
                cantidad = $(this).find(`#tr__product__` + producto.id).find('input[name="input-cantidad"]'),
                precio_unitario = $(this).find(`#tr__product__` + producto.id).find(
                    'input[name="input-precio"]'),
                ultimo_tr = $(this).find('tr:last').find('td').eq(0).text();

            if (id.val() == undefined) {
                html__new += `<tr id="tr__product__${producto.id}">
                                                <td class="d-none"><input type="hidden" name="idproducto" value="${producto.id}"></td>
                                                <td>${producto.producto}</td>
                                                <td class="text-center">${producto.unidad}</td>
                                                <td class="text-right">
                                                    <div class="input-group input-group-sm">
                                                        <span class="input-group-text btn-down" style="cursor: pointer;" data-id="${producto.id}" data-cantidad="1" data-precio="${parseFloat(producto.precio_venta).toFixed(2)}"><i class="ti ti-minus me-sm-1"></i></span>
                                                        <input type="text" data-id="${producto.id}" class="quantity-counter text-center form-control" value="1" name="input-cantidad">
                                                        <span class="input-group-text btn-up" style="cursor: pointer;" data-id="${producto.id}" data-cantidad="1" data-precio="${parseFloat(producto.precio_venta).toFixed(2)}"><i class="ti ti-plus me-sm-1"></i></span>
                                                    </div>
                                                </td>
                                                <td class="text-center"><input type="text" class="form-control form-control-sm text-center" value="${parseFloat(producto.precio_venta).toFixed(2)}" data-cantidad="'1" data-id="${producto.id}" data-codigo_igv="${producto.codigo_igv}" data-impuesto="${producto.impuesto}"  name="input-precio"></td>

                                                <td class="text-center">${parseFloat(producto.precio_venta * 1).toFixed(2)}</td>
                                                <td class="text-center"><span data-id="${producto.id}" class="text-danger btn-delete-product" style="cursor: pointer;"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x align-middle mr-25"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></span></td>
                                </tr>`;

                $('#wrapper-tbody').append(html__new);
                calculate__totals();
            } else {
                $(this).find(`#tr__product__` + producto.id).find('input[name="input-cantidad"]').val(parseInt(
                    cantidad.val()) + cantidad_entra);
                $(this).find(`#tr__product__` + producto.id).find('td').eq(5).text(parseFloat(parseFloat(
                    precio_unitario.val()) * parseInt(cantidad.val())).toFixed(2));
                calculate__totals();
            }
        });
    }

    $('body').on('change', 'input[name="input-precio"]', function() {
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
            url: "{{ route('admin.store_product_quote_update') }}",
            method: 'POST',
            data: {
                '_token': "{{ csrf_token() }}",
                id: id,
                cantidad: cantidad,
                precio: precio
            },
            success: function(r) {
                if (!r.status) {
                    toast_msg(r.msg, r.type);
                    return;
                }

                let producto = r.producto,
                    cantidad = r.cantidad,
                    precio = r.precio;
                sum_product_price(producto, cantidad, precio);
            },
            dataType: 'json'
        });
    });

    function sum_product_price(producto, cantidad_entra, precio) {
        $('#wrapper-tbody').each(function() {
            let html__new = '',
                id = $(this).find(`#tr__product__` + producto.id).find('input[name="idproducto"]'),
                cantidad = $(this).find(`#tr__product__` + producto.id).find('input[name="input-cantidad"]'),
                ultimo_tr = $(this).find('tr:last').find('td').eq(0).text(),
                idtable = $('input[name="idtable"]').val();

            if (id.val() == undefined) {
                html__new += `<tr id="tr__product__${producto.id}">
                                                <td class="d-none"><input type="hidden" name="idproducto" value="${producto.id}"></td>
                                                <td>${producto.producto}</td>
                                                <td class="text-center">${producto.unidad}</td>
                                                <td class="text-right">
                                                    <div class="input-group input-group-sm">
                                                        <span class="input-group-text btn-down" style="cursor: pointer;" data-id="${producto.id}" data-cantidad="1" data-precio="${parseFloat(producto.precio_venta).toFixed(2)}"><i class="ti ti-minus me-sm-1"></i></span>
                                                        <input type="text" data-id="${producto.id}" class="quantity-counter text-center form-control" value="1" name="input-cantidad">
                                                        <span class="input-group-text btn-up" style="cursor: pointer;" data-id="${producto.id}" data-cantidad="1" data-precio="${parseFloat(producto.precio_venta).toFixed(2)}"><i class="ti ti-plus me-sm-1"></i></span>
                                                    </div>
                                                </td>
                                                <td class="text-center"><input type="text" class="form-control form-control-sm text-center" value="${parseFloat(producto.precio_venta).toFixed(2)}" data-cantidad="'1" data-id="${producto.id}" data-codigo_igv="${producto.codigo_igv}" data-impuesto="${producto.impuesto}" name="input-precio"></td>

                                                <td class="text-center">${parseFloat(producto.precio_venta * 1).toFixed(2)}</td>
                                                <td class="text-center"><span data-id="${producto.id}" class="text-danger btn-delete-product" style="cursor: pointer;"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x align-middle mr-25"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></span></td>
                                </tr>`;

                $('#wrapper-tbody').append(html__new);
                calculate__totals();
            } else {
                $(this).find(`#tr__product__` + producto.id).find('td').eq(5).text(parseFloat(parseFloat(
                    precio) * parseInt(cantidad.val())).toFixed(2));
                $(this).find(`#tr__product__` + producto.id).find('input[name="input-precio"]').val(parseFloat(
                    precio).toFixed(2));
                calculate__totals();
            }
        });
    }

    $('body').on('click', '.btn-down', function() {
        event.preventDefault();
        let id = $(this).data('id'),
            cantidad = parseInt($(this).parent().find('input[name="input-cantidad"]').val()),
            cantidad_enviar = cantidad - 1,
            precio = parseFloat($(this).parent().parent().parent().find('td').eq(4).find(
                'input[name="input-precio"]').val());

        if (cantidad_enviar < 1) {
            toast_msg('La cantidad no puede ser menor a 1', 'warning');
            return;
        }

        $.ajax({
            url: "{{ route('admin.store_product_quote_update') }}",
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
                subtract_product_quantity(r.producto, r.cantidad, r.precio);
            },
            dataType: "json"
        });
    });

    function subtract_product_quantity(producto, cantidad_entra, precio) {
        $('#wrapper-tbody').each(function() {
            let html__new = '',
                id = $(this).find(`#tr__product__` + producto.id).find('input[name="idproducto"]'),
                cantidad = $(this).find(`#tr__product__` + producto.id).find('input[name="input-cantidad"]'),
                ultimo_tr = $(this).find('tr:last').find('td').eq(0).text();

            if (id.val() == undefined) {
                html__new += `<tr id="tr__product__${producto.id}">
                                                <td class="d-none"><input type="hidden" name="idproducto" value="${producto.id}"></td>
                                                <td>${producto.producto}</td>
                                                <td class="text-center">${producto.unidad}</td>
                                                <td class="text-right">
                                                    <div class="input-group input-group-sm">
                                                        <span class="input-group-text btn-down" style="cursor: pointer;" data-id="${producto.id}" data-cantidad="1" data-precio="${parseFloat(producto.precio_venta).toFixed(2)}"><i class="ti ti-minus me-sm-1"></i></span>
                                                        <input type="text" data-id="${producto.id}" class="quantity-counter text-center form-control" value="1" name="input-cantidad">
                                                        <span class="input-group-text btn-up" style="cursor: pointer;" data-id="${producto.id}" data-cantidad="1" data-precio="${parseFloat(producto.precio_venta).toFixed(2)}"><i class="ti ti-plus me-sm-1"></i></span>
                                                    </div>
                                                </td>
                                                <td class="text-center"><input type="text" class="form-control form-control-sm text-center" value="${parseFloat(producto.precio_venta).toFixed(2)}" data-cantidad="'1" data-id="${producto.id}" data-codigo_igv="${producto.codigo_igv}" data-impuesto="${producto.impuesto}" name="input-precio"></td>

                                                <td class="text-center">${parseFloat(producto.precio_venta * 1).toFixed(2)}</td>
                                                <td class="text-center"><span data-id="${producto.id}" class="text-danger btn-delete-product" style="cursor: pointer;"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x align-middle mr-25"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></span></td>
                                </tr>`;

                $('#wrapper-tbody').append(html__new);
                calculate__totals();
            } else {
                $(this).find(`#tr__product__` + producto.id).find('input[name="input-cantidad"]').val(parseInt(
                    cantidad_entra));
                $(this).find(`#tr__product__` + producto.id).find('td').eq(5).text(parseFloat(parseFloat(
                    precio) * parseInt(cantidad.val())).toFixed(2));
                calculate__totals();
            }
        });
    }

    $('body').on('click', '.btn-up', function() {
        event.preventDefault();
        let id = $(this).data('id'),
            cantidad = parseInt($(this).parent().find('input[name="input-cantidad"]').val()),
            cantidad_enviar = cantidad + 1,
            precio = parseFloat($(this).parent().parent().parent().find('td').eq(4).find(
                'input[name="input-precio"]').val());

        $.ajax({
            url: "{{ route('admin.store_product_quote_update') }}",
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
                sum_product_quantity(r.producto, r.cantidad, r.precio);
            },
            dataType: "json"
        });
    });

    function sum_product_quantity(producto, cantidad_entra, precio) {
        $('#wrapper-tbody').each(function() {
            let html__new = '',
                id = $(this).find(`#tr__product__` + producto.id).find('input[name="idproducto"]'),
                cantidad = $(this).find(`#tr__product__` + producto.id).find('input[name="input-cantidad"]'),
                ultimo_tr = $(this).find('tr:last').find('td').eq(0).text();

            if (id.val() == undefined) {
                html__new += `<tr id="tr__product__${producto.id}">
                                                <td class="d-none"><input type="hidden" name="idproducto" value="${producto.id}"></td>
                                                <td>${producto.producto}</td>
                                                <td class="text-center">${producto.unidad}</td>
                                                <td class="text-right">
                                                    <div class="input-group input-group-sm">
                                                        <span class="input-group-text btn-down" style="cursor: pointer;" data-id="${producto.id}" data-cantidad="1" data-precio="${parseFloat(producto.precio_venta).toFixed(2)}" ><i class="ti ti-minus me-sm-1"></i></span>
                                                        <input type="text" data-id="${producto.id}" class="quantity-counter text-center form-control" value="1" name="input-cantidad">
                                                        <span class="input-group-text btn-up" style="cursor: pointer;" data-id="${producto.id}" data-cantidad="1" data-precio="${parseFloat(producto.precio_venta).toFixed(2)}" ><i class="ti ti-plus me-sm-1"></i></span>
                                                    </div>
                                                </td>
                                                <td class="text-center"><input type="text" class="form-control form-control-sm text-center" value="${parseFloat(producto.precio_venta).toFixed(2)}" data-cantidad="'1" data-id="${producto.id}" data-codigo_igv="${producto.codigo_igv}" data-impuesto="${producto.impuesto}"  name="input-precio"></td>

                                                <td class="text-center">${parseFloat(producto.precio_venta * 1).toFixed(2)}</td>
                                                <td class="text-center"><span data-id="${producto.id}" class="text-danger btn-delete-product" style="cursor: pointer;"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x align-middle mr-25"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></span></td>
                                </tr>`;

                $('#wrapper-tbody').append(html__new);
                calculate__totals();
            } else {
                $(this).find(`#tr__product__` + producto.id).find('input[name="input-cantidad"]').val(
                    cantidad_entra);
                $(this).find(`#tr__product__` + producto.id).find('td').eq(5).text(parseFloat(parseFloat(
                    precio) * parseInt(cantidad.val())).toFixed(2));
                calculate__totals();
            }
        });
    }

    $('body').on('click', '.btn-delete-product', function() {
        event.preventDefault();
        $(this).closest('tr').remove();
        calculate__totals();
    });

    // Totals
    function calculate__totals() {
        var exonerada = 0,
            gravada = 0,
            inafecta = 0,
            codigo_igv = 0;
        subtotal = 0,
            total = 0,
            impuesto = 0,
            cantidad = 0,
            igv = 0;
        $('#wrapper-tbody tr').each(function() {
            let idproducto = $(this).find('input[name="input-precio"]').data('id');
            impuesto = $(this).find('input[name="input-precio"]').data('impuesto');
            codigo_igv = $(this).find('input[name="input-precio"]').data('codigo_igv');
            cantidad = $(this).find('input[name="input-cantidad"]').val();


            if (impuesto == 1) {
                igv += ((parseFloat($(this).find('input[name="input-precio"]').val())) - (parseFloat($(this)
                    .find('input[name="input-precio"]').val()) / 1.18) * parseInt(cantidad));
                igv = redondeado(igv);
            }

            if (codigo_igv == 10) {
                gravada += (parseFloat($(this).find('input[name="input-precio"]').val()) / 1.18) * parseInt(
                    cantidad);
                gravada = redondeado(gravada);
            }

            if (codigo_igv == 20) {
                exonerada += (parseFloat($(this).find('input[name="input-precio"]').val())) * parseInt(
                    cantidad);
                exonerada = redondeado(exonerada);
            }

            if (codigo_igv == 30) {
                inafecta += (parseFloat($(this).find('input[name="input-precio"]').val())) * parseInt(cantidad);
                inafecta = redondeado(inafecta);
            }

            subtotal = exonerada + gravada + inafecta;
        });

        total = subtotal + igv;
        $('.span__exonerada').text(parseFloat(exonerada).toFixed(2));
        $('.span__gravada').text(parseFloat(gravada).toFixed(2));
        $('.span__inafecta').text(parseFloat(inafecta).toFixed(2));
        $('.span__subtotal').text(parseFloat(subtotal).toFixed(2));
        $('.span__igv').text(parseFloat(igv).toFixed(2));
        $('.span__total').text(parseFloat(total).toFixed(2));
    }
    calculate__totals();

    function redondeado(numero, decimales = 2) {
        let factor = Math.pow(10, decimales);
        return (Math.round(numero * factor) / factor);
    }

    $('body').on('click', '.btn-save-quote', function() {
        event.preventDefault();
        let productos = [],
            totales = null,
            idtipo_comprobante  = $('#form_save_quote select[name="idtipo_comprobante"]').val(),
            idcliente           = $('#form_save_quote select[name="dni_ruc"]').val(),
            modo_pago           = $('#form_save_quote select[name="modo_pago"]').val(),
            idquote             = $('#form_save_quote input[name="idquote"]').val();

        $('#wrapper-tbody tr').each(function() {
            let nuevo_producto = {
                idproducto: $(this).find('input[name="idproducto"]').val(),
                idalmacen: $(this).find('input[name="idproducto"]').data('idalmacen'),
                cantidad: $(this).find('input[name="input-cantidad"]').val(),
                precio: $(this).find('input[name="input-precio"]').val()
            }
            productos.push(nuevo_producto);
        });

        let suma_totales = {
            exonerada: $('.span__exonerada').text(),
            gravada: $('.span__gravada').text(),
            inafecta: $('.span__inafecta').text(),
            subtotal: $('.span__subtotal').text(),
            igv: $('.span__igv').text(),
            total: $('.span__total').text()
        }
        totales = suma_totales;

        $.ajax({
            url: "{{ route('admin.gen_quote_update') }}",
            method: 'POST',
            data: {
                '_token': "{{ csrf_token() }}",
                'productos': JSON.stringify(productos),
                'totales': JSON.stringify(totales),
                'idtipo_comprobante': idtipo_comprobante,
                'idcliente' : idcliente,
                'modo_pago' : modo_pago,
                'idquote'   : idquote
            },
            beforeSend: function() {
                $('.btn-save-quote').prop('disabled', true);
                $('.text-save-quote').addClass('d-none');
                $('.text-saving-quote').removeClass('d-none');
            },
            success: function(r) {
                if (!r.status) {
                    $('.btn-save-quote').prop('disabled', false);
                    $('.text-save-quote').removeClass('d-none');
                    $('.text-saving-quote').addClass('d-none');
                    toast_msg(r.msg, r.type);
                    return;
                }

                $('.btn-save-quote').prop('disabled', false);
                $('.text-save-quote').removeClass('d-none');
                $('.text-saving-quote').addClass('d-none');
                let pdf = `{{ asset('files/quotes/${r.pdf}') }}`;
                var iframe = document.createElement('iframe');
                iframe.style.display = "none";
                iframe.src = pdf;
                document.body.appendChild(iframe);
                iframe.contentWindow.focus();
                iframe.contentWindow.print();
            },
            dataType: 'json'
        });
    });


</script>
