<script>
    var setTimeOutBuscador = '',
        idtable = $('input[name="idtable"]').val();


    $(document).ready(function() {
        $('input[name="input-search-product"]').focus();
        load_view_products();
    });

    function success_save_product(msg = null, type = null) {
        toast_msg(msg, type);
        load_view_products();
    }

    //Load products
    function load_view_products() {
        $.ajax({
            url: "{{ route('admin.load_view_products') }}",
            method: 'POST',
            data: {
                '_token': "{{ csrf_token() }}"
            },
            success: function(r) {
                if (!r.status) {
                    toast_msg(r.msg, r.title, r.type);
                    return;
                }
                $('#wrapper-products').html(r.html_products);
            },
            dataType: 'json'
        });
        return;
    }

    $('body').on('keyup', '.input-search-product', function() {
        let value = $(this).val();
        if (event.keyCode === 13)
            return;

        if (event.keyCode === 27) {
            $('.input-search-product').val("");
            load_view_products();
            return;
        }

        if (value.trim() == '') {
            load_view_products();
            return;
        }

        clearTimeout(setTimeOutBuscador);
        setTimeOutBuscador = setTimeout(() => {
            $.ajax({
                url: "{{ route('admin.search_view_product') }}",
                method: 'POST',
                data: {
                    '_token': "{{ csrf_token() }}",
                    value: value
                },
                beforeSend: function() {
                    block_content(`#content-pos-product`);
                },
                success: function(r) {
                    if (!r.status) {
                        close_block(`#content-pos-product`);
                        toast_msg(r.msg, r.type);
                        return;
                    }
                    close_block(`#content-pos-product`);
                    $('#wrapper-products').html(r.html_products);
                },
                dataType: "json"
            });
        }, 300);
    });

    $('body').on('click', '.btn-update-product-cart', function() {
        event.preventDefault();
        var id = $(this).data('id'),
            cantidad = $(this).data('cantidad'),
            idtable = $('input[name="idtable"]').val(),
            precio = parseFloat($(this).data('precio'));

        $.ajax({
            url: "{{ route('admin.get_product_order_update') }}",
            method: 'POST',
            data: {
                '_token': "{{ csrf_token() }}",
                id: id,
                cantidad: cantidad,
                precio: precio,
                idtable: idtable
            },
            beforeSend: function() {
                block_content(`.card[id="${id}"]`);
            },
            success: function(r) {
                if (!r.status) {
                    close_block(`.card[id="${id}"]`);
                    toast_msg(r.msg, r.type);
                    return;

                }

                close_block(`.card[id="${id}"]`);
                toast_msg(r.msg, r.type);
                sum_product(r.producto, 1);
            },
            dataType: 'json'
        });
        return;
    });

    $('body').on('change', 'input[name="input-precio"]', function() {
        let precio = $(this).val(),
            cantidad = $(this).data('cantidad'),
            id = $(this).data('id'),
            idtable = $(this).data('idtable');

        if (precio.trim() == '') {
            return;
        }
        if (isNaN(precio)) {
            toast_msg('Solo se permiten números', 'warning');
            $(this).focus();
            return;
        }

        $.ajax({
            url: "{{ route('admin.store_product_order_update') }}",
            method: 'POST',
            data: {
                '_token': "{{ csrf_token() }}",
                id: id,
                cantidad: cantidad,
                precio: precio,
                idtable: idtable
            },
            success: function(r) {
                if (!r.status) {
                    load_cart(r.idtable);
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

    $('body').on('click', '.btn-down', function() {
        event.preventDefault();
        let id = $(this).data('id'),
            cantidad = parseInt($(this).parent().find('input[name="input-cantidad"]').val()),
            cantidad_enviar = cantidad - 1,
            precio = parseFloat($(this).parent().parent().parent().find('td').eq(3).find(
                'input[name="input-precio"]').val()),
            idtable = $(this).data('idtable');

        if (cantidad_enviar < 1) {
            toast_msg('La cantidad no puede ser menor a 1', 'warning');
            return;
        }

        $.ajax({
            url: "{{ route('admin.store_product_order_update') }}",
            method: "POST",
            data: {
                '_token': "{{ csrf_token() }}",
                id: id,
                cantidad: cantidad_enviar,
                precio: precio,
                idtable: idtable
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

    $('body').on('click', '.btn-up', function() {
        event.preventDefault();
        let id = $(this).data('id'),
            cantidad = parseInt($(this).parent().find('input[name="input-cantidad"]').val()),
            cantidad_enviar = cantidad + 1,
            precio = parseFloat($(this).parent().parent().parent().find('td').eq(3).find(
                'input[name="input-precio"]').val()),
            idtable = $(this).data('idtable');

        $.ajax({
            url: "{{ route('admin.store_product_order_update') }}",
            method: "POST",
            data: {
                '_token': "{{ csrf_token() }}",
                id: id,
                cantidad: cantidad_enviar,
                precio: precio,
                idtable: idtable
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

    $('body').on('click', '.btn-delete-product-cart', function() {
        event.preventDefault();
        $(this).closest('tr').remove();
        calculate__totals();
    });

    // Functions news
    function sum_product(producto, cantidad_entra) {
        $('#wrapper-tbody-pos').each(function() {
            let html__new = '',
                id = $(this).find(`#tr__product__` + producto.id).find('input[name="idproducto"]'),
                cantidad = $(this).find(`#tr__product__` + producto.id).find('input[name="input-cantidad"]'),
                precio_unitario = $(this).find(`#tr__product__` + producto.id).find(
                    'input[name="input-precio"]'),
                ultimo_tr = $(this).find('tr:last').find('td').eq(0).text(),
                idtable = $('input[name="idtable"]').val();

            if (id.val() == undefined) {
                html__new += `<tr id="tr__product__${producto.id}">
                                            <td class="d-none"><input type="hidden" name="idproducto" value="${producto.id}"></td>
                                            <td class="text-left">${producto.descripcion}</td>
                                            <td class="text-right">
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-text btn-down" style="cursor: pointer;" data-id="${producto.id}" data-cantidad="1" data-precio="${parseFloat(producto.precio_venta).toFixed(2)}" data-idtable="${idtable}"><i class="ti ti-minus me-sm-1"></i></span>
                                                    <input type="text" data-id="${producto.id}" class="quantity-counter text-center form-control" value="1" data-idtable="${idtable}" name="input-cantidad">
                                                    <span class="input-group-text btn-up" style="cursor: pointer;" data-id="${producto.id}" data-cantidad="1" data-precio="${parseFloat(producto.precio_venta).toFixed(2)}" data-idtable="${idtable}"><i class="ti ti-plus me-sm-1"></i></span>
                                                </div>
                                            </td>
                                            <td class="text-center"><input type="text" class="form-control form-control-sm text-center" value="${parseFloat(producto.precio_venta).toFixed(2)}" data-cantidad="1" data-codigo_igv="${producto.codigo_igv}" data-impuesto="${producto.impuesto}" data-id="${producto.id}" data-idtable="${idtable}" name="input-precio"></td>
                                            <td class="text-center">${parseFloat(producto.precio_venta * 1).toFixed(2)}</td>
                                            <td class="text-center"><span data-id="${producto.id}" data-idtable="${idtable}" class="text-danger btn-delete-product-cart" style="cursor: pointer;"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x align-middle mr-25"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></span></td>
                                        </tr>`;

                $('#wrapper-tbody-pos').append(html__new);
                calculate__totals();
            } else {
                $(this).find(`#tr__product__` + producto.id).find('input[name="input-cantidad"]').val(parseInt(
                    cantidad.val()) + cantidad_entra);
                $(this).find(`#tr__product__` + producto.id).find('td').eq(4).text(parseFloat(parseFloat(
                    precio_unitario.val()) * parseInt(cantidad.val())).toFixed(2));
                calculate__totals();
            }
        });
    }

    function sum_product_price(producto, cantidad_entra, precio) {
        $('#wrapper-tbody-pos').each(function() {
            let html__new = '',
                id = $(this).find(`#tr__product__` + producto.id).find('input[name="idproducto"]'),
                cantidad = $(this).find(`#tr__product__` + producto.id).find('input[name="input-cantidad"]'),
                ultimo_tr = $(this).find('tr:last').find('td').eq(0).text(),
                idtable = $('input[name="idtable"]').val();

            if (id.val() == undefined) {
                html__new += `<tr id="tr__product__${producto.id}">
                                            <td class="d-none"><input type="hidden" name="idproducto" value="${producto.id}"></td>
                                            <td class="text-left">${producto.descripcion}</td>
                                            <td class="text-right">
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-text btn-down" style="cursor: pointer;" data-id="${producto.id}" data-cantidad="1" data-precio="${parseFloat(producto.precio_venta).toFixed(2)}" data-idtable="${idtable}"><i class="ti ti-minus me-sm-1"></i></span>
                                                    <input type="text" data-id="${producto.id}" class="quantity-counter text-center form-control" value="1" data-idtable="${idtable}" name="input-cantidad">
                                                    <span class="input-group-text btn-up" style="cursor: pointer;" data-id="${producto.id}" data-cantidad="1" data-precio="${parseFloat(producto.precio_venta).toFixed(2)}" data-idtable="${idtable}"><i class="ti ti-plus me-sm-1"></i></span>
                                                </div>
                                            </td>
                                            <td class="text-center"><input type="text" class="form-control form-control-sm text-center" value="${parseFloat(producto.precio_venta).toFixed(2)}" data-cantidad="1" data-codigo_igv="${producto.codigo_igv}" data-impuesto="${producto.impuesto}" data-id="${producto.id}" data-idtable="${idtable}" name="input-precio"></td>
                                            <td class="text-center">${parseFloat(producto.precio_venta * 1).toFixed(2)}</td>
                                            <td class="text-center"><span data-id="${producto.id}" data-idtable="${idtable}" class="text-danger btn-delete-product-cart" style="cursor: pointer;"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x align-middle mr-25"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></span></td>
                                        </tr>`;

                $('#wrapper-tbody-pos').append(html__new);
                calculate__totals();
            } else {
                $(this).find(`#tr__product__` + producto.id).find('td').eq(4).text(parseFloat(parseFloat(
                    precio) * parseInt(cantidad.val())).toFixed(2));
                $(this).find(`#tr__product__` + producto.id).find('input[name="input-precio"]').val(parseFloat(
                    precio).toFixed(2));
                calculate__totals();
            }
        });
    }

    function sum_product_quantity(producto, cantidad_entra, precio) {
        $('#wrapper-tbody-pos').each(function() {
            let html__new = '',
                id = $(this).find(`#tr__product__` + producto.id).find('input[name="idproducto"]'),
                cantidad = $(this).find(`#tr__product__` + producto.id).find('input[name="input-cantidad"]'),
                ultimo_tr = $(this).find('tr:last').find('td').eq(0).text(),
                idtable = $('input[name="idtable"]').val();

            if (id.val() == undefined) {
                html__new += `<tr id="tr__product__${producto.id}">
                                            <td class="d-none"><input type="hidden" name="idproducto" value="${producto.id}"></td>
                                            <td class="text-left">${producto.descripcion}</td>
                                            <td class="text-right">
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-text btn-down" style="cursor: pointer;" data-id="${producto.id}" data-cantidad="1" data-precio="${parseFloat(producto.precio_venta).toFixed(2)}" data-idtable="${idtable}"><i class="ti ti-minus me-sm-1"></i></span>
                                                    <input type="text" data-id="${producto.id}" class="quantity-counter text-center form-control" value="1" data-idtable="${idtable}" name="input-cantidad">
                                                    <span class="input-group-text btn-up" style="cursor: pointer;" data-id="${producto.id}" data-cantidad="1" data-precio="${parseFloat(producto.precio_venta).toFixed(2)}" data-idtable="${idtable}"><i class="ti ti-plus me-sm-1"></i></span>
                                                </div>
                                            </td>
                                            <td class="text-center"><input type="text" class="form-control form-control-sm text-center" value="${parseFloat(producto.precio_venta).toFixed(2)}" data-cantidad="1" data-codigo_igv="${producto.codigo_igv}" data-impuesto="${producto.impuesto}" data-id="${producto.id}" data-idtable="${idtable}" name="input-precio"></td>
                                            <td class="text-center">${parseFloat(producto.precio_venta * 1).toFixed(2)}</td>
                                            <td class="text-center"><span data-id="${producto.id}" data-idtable="${idtable}" class="text-danger btn-delete-product-cart" style="cursor: pointer;"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x align-middle mr-25"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></span></td>
                                        </tr>`;

                $('#wrapper-tbody-pos').append(html__new);
                calculate__totals();
            } else {
                $(this).find(`#tr__product__` + producto.id).find('input[name="input-cantidad"]').val(
                    cantidad_entra);
                $(this).find(`#tr__product__` + producto.id).find('td').eq(4).text(parseFloat(parseFloat(
                    precio) * parseInt(cantidad.val())).toFixed(2));
                calculate__totals();
            }
        });
    }

    function subtract_product_quantity(producto, cantidad_entra, precio) {
        $('#wrapper-tbody-pos').each(function() {
            let html__new = '',
                id = $(this).find(`#tr__product__` + producto.id).find('input[name="idproducto"]'),
                cantidad = $(this).find(`#tr__product__` + producto.id).find('input[name="input-cantidad"]'),
                ultimo_tr = $(this).find('tr:last').find('td').eq(0).text(),
                idtable = $('input[name="idtable"]').val();

            if (id.val() == undefined) {
                html__new += `<tr id="tr__product__${producto.id}">
                                            <td class="d-none"><input type="hidden" name="idproducto" value="${producto.id}"></td>
                                            <td class="text-left">${producto.descripcion}</td>
                                            <td class="text-right">
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-text btn-down" style="cursor: pointer;" data-id="${producto.id}" data-cantidad="1" data-precio="${parseFloat(producto.precio_venta).toFixed(2)}" data-idtable="${idtable}"><i class="ti ti-minus me-sm-1"></i></span>
                                                    <input type="text" data-id="${producto.id}" class="quantity-counter text-center form-control" value="1" data-idtable="${idtable}" name="input-cantidad">
                                                    <span class="input-group-text btn-up" style="cursor: pointer;" data-id="${producto.id}" data-cantidad="1" data-precio="${parseFloat(producto.precio_venta).toFixed(2)}" data-idtable="${idtable}"><i class="ti ti-plus me-sm-1"></i></span>
                                                </div>
                                            </td>
                                            <td class="text-center"><input type="text" class="form-control form-control-sm text-center" value="${parseFloat(producto.precio_venta).toFixed(2)}" data-cantidad="1" data-codigo_igv="${producto.codigo_igv}" data-impuesto="${producto.impuesto}" data-id="${producto.id}" data-idtable="${idtable}" name="input-precio"></td>
                                            <td class="text-center">${parseFloat(producto.precio_venta * 1).toFixed(2)}</td>
                                            <td class="text-center"><span data-id="${producto.id}" data-idtable="${idtable}" class="text-danger btn-delete-product-cart" style="cursor: pointer;"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x align-middle mr-25"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></span></td>
                                        </tr>`;

                $('#wrapper-tbody-pos').append(html__new);
                calculate__totals();
            } else {
                $(this).find(`#tr__product__` + producto.id).find('input[name="input-cantidad"]').val(parseInt(
                    cantidad_entra));
                $(this).find(`#tr__product__` + producto.id).find('td').eq(4).text(parseFloat(parseFloat(
                    precio) * parseInt(cantidad.val())).toFixed(2));
                calculate__totals();
            }
        });
    }

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
        $('#wrapper-tbody-pos tr').each(function() {
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

    $('body').on('click', '.btn-change-table', function() {
        event.preventDefault();
        let idtable = $('input[name="idtable"]').val();
        $.ajax({
            url: "{{ route('admin.get_tables_availables') }}",
            method: "POST",
            data: {
                '_token': "{{ csrf_token() }}",
                idtable: idtable
            },
            beforeSend: function() {
                block_content('#layout-content');
            },
            success: function(r) {
                if (!r.status) {
                    close_block('#layout-content');
                    toast_msg(r.msg, r.type);
                    return;
                }

                close_block('#layout-content');
                let html = '<option></option>';
                $.each(r.tables, function(index, table) {
                    html += `<option value="${table.id}">${table.descripcion + ' - ' + table.sala}</option>`;
                });

                $('#modalChangeTable select[name="idtable_up"]').html(html).select2({
                    placeholder: "[SELECCIONE]",
                    dropdownParent: $('#modalChangeTable')
                });
                $('#modalChangeTable').modal('show');
            },
            dataType: "json"
        });
    });

    $('body').on('click', '.btn-save-change-table', function() {
        event.preventDefault();
        let form = $('#form_change_table').serialize();
        $.ajax({
            url: "{{ route('admin.change_table_order') }}",
            method: 'POST',
            data: form,
            beforeSend: function() {
                $('.btn-save-change-table').prop('disabled', true);
                $('.text-change-table').addClass('d-none');
                $('.text-saving-change-table').removeClass('d-none');
            },
            success: function(r) {
                if (!r.status) {
                    $('.btn-save-change-table').prop('disabled', false);
                    $('.text-change-table').removeClass('d-none');
                    $('.text-saving-change-table').addClass('d-none');
                    toast_msg(r.msg, r.type);
                    return;
                }

                $('.btn-save-change-table').prop('disabled', false);
                $('.text-change-table').removeClass('d-none');
                $('.text-saving-change-table').addClass('d-none');
                $('#modalChangeTable').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: r.msg,
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    let idtable = {id: r.idtable};
                    window.location.href = route('admin.register_order', idtable);
                })
            },
            dataType: 'json'
        });
    });

    $('body').on('click', '.btn-process-order', function() {
        event.preventDefault();
        let productos = [],
            totales = null,
            observaciones = $('textarea[name="observaciones"]').val(),
            idtable = $('input[name="idtable"]').val();

        $('#wrapper-tbody-pos tr').each(function() {
            let nuevo_producto = {
                idproducto: $(this).find('input[name="idproducto"]').val(),
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

        Swal.fire({
            title: 'Confirmar Pedido',
            text: "¿Desea confirmar el pedido?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Si, confirmar',
            cancelButtonText: 'Cancelar',
            customClass: {
                confirmButton: 'btn btn-primary',
                cancelButton: 'btn btn-outline-danger ml-1'
            },
            buttonsStyling: false
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    url: "{{ route('admin.gen_order_update') }}",
                    method: 'POST',
                    data: {
                        '_token': "{{ csrf_token() }}",
                        'productos': JSON.stringify(productos),
                        'totales': JSON.stringify(totales),
                        observaciones: observaciones,
                        idtable: idtable
                    },
                    beforeSend: function() {
                        $('.btn-process-order').prop('disabled', true);
                        $('.text-process').addClass('d-none');
                        $('.text-processing').removeClass('d-none');
                    },
                    success: function(r) {
                        if (!r.status) {
                            $('.btn-process-order').prop('disabled', false);
                            $('.text-process').removeClass('d-none');
                            $('.text-processing').addClass('d-none');
                            toast_msg(r.msg, r.type);
                            return;
                        }

                        $('.btn-process-order').prop('disabled', false);
                        $('.text-process').removeClass('d-none');
                        $('.text-processing').addClass('d-none');
                        Swal.fire({
                            icon: 'success',
                            title: r.msg,
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            window.location.href =
                                "{{ route('admin.create_order') }}"
                        })
                    },
                    dataType: 'json'
                });
            }
        });


    });
</script>
