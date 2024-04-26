<script>
    var setTimeOutBuscador = '',
        idtable            = $('input[name="idtable"]').val();

    $(document).ready(function() {
        $('input[name="input-search-product"]').focus();
        load_view_products();
        load_cart(idtable);
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

    $('body').on('click', '.btn-clear-input', function() {
        event.preventDefault();
        let input = $('input[name="input-search-product"]').val();
        if (input.trim() == '')
            return;

        $('input[name="input-search-product"]').val('');
        load_view_products();
    });

    // Cart
    function load_cart(id) {
        $.ajax({
            url         : "{{ route('admin.load_cart_order') }}",
            method      : 'POST',
            data        : {
                '_token': "{{ csrf_token() }}",
                id      : id
            },
            success: function(r) {
                if (!r.status) {
                    toast_msg(r.msg, r.title, r.type);
                    return;
                }

                $('#wrapper-tbody-pos').html(r.html_cart);
                $('#wrapper-totals').html(r.html_totals);
            },
            dataType: 'json'
        });
        return;
    }

    $('body').on('click', '.btn-add-product-cart', function() {
        event.preventDefault();
        let id      = $(this).data('id');
        cantidad    = $(this).data('cantidad'),
        idtable     = $('input[name="idtable"]').val(),
        precio      = parseFloat($(this).data('precio'));

        $.ajax({
            url: "{{ route('admin.add_product_order') }}",
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
                load_cart(r.idtable);
            },
            dataType: 'json'
        });
        return;
    });

    $('body').on('click', '.btn-delete-product-cart', function() {
        event.preventDefault();
        let id          = $(this).data('id'),
            idtable     = $(this).data('idtable');
        $.ajax({
            url: "{{ route('admin.delete_product_order') }}",
            method: 'POST',
            data: {
                '_token': "{{ csrf_token() }}",
                id: id,
                idtable: idtable
            },
            success: function(r) {
                if (!r.status) {
                    toast_msg(r.msg, r.type);
                    return;
                }

                load_cart(r.idtable);
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
            precio = parseFloat($(this).data('precio')),
            idtable = $(this).data('idtable');

        if (cantidad_enviar <= 0) {
            toast_msg('La cantidad no puede ser menor a 1', 'warning');
            return;
        }

        $.ajax({
            url: "{{ route('admin.store_product_order') }}",
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
                load_cart(r.idtable);
            },
            dataType: "json"
        });
    });

    $('body').on('click', '.btn-up', function() {
        event.preventDefault();
        let id = $(this).data('id'),
            cantidad = parseInt($(this).data('cantidad')),
            cantidad_enviar = cantidad + 1,
            precio = parseFloat($(this).data('precio')),
            idtable = $(this).data('idtable');

        $.ajax({
            url: "{{ route('admin.store_product_order') }}",
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
                load_cart(r.idtable);
            },
            dataType: "json"
        });
    });

    $('body').on('change', '.input-update', function() {
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
            url: "{{ route('admin.store_product_order') }}",
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

                toast_msg(r.msg, r.type);
                load_cart(r.idtable);
            },
            dataType: 'json'
        });
    });

    $('body').on('click', '.btn-cancel-order', function() {
        event.preventDefault();
        Swal.fire({
            title: 'Cancelar Venta',
            text: "¿Desea cancelar la venta actual?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Si, cancelar',
            cancelButtonText: 'Cancelar',
            customClass: {
                confirmButton: 'btn btn-primary',
                cancelButton: 'btn btn-outline-danger ml-1'
            },
            buttonsStyling: false
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    url: "{{ route('admin.cancel_cart_order') }}",
                    method: 'POST',
                    data: {
                        '_token': "{{ csrf_token() }}",
                        idtable : idtable
                    },
                    success: function(r) {
                        if (!r.status) {
                            toast_msg(r.msg, r.type);
                            return;
                        }

                        toast_msg(r.msg, r.type);
                        load_cart();
                    },
                    dataType: 'json'
                });
            }
        });
    });

    $('body').on('click', '.btn-process-order', function()
    {
        event.preventDefault();
        let form        = $('#form_gen_order').serialize();
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
                    url: "{{ route('admin.gen_order') }}",
                    method: 'POST',
                    data: form,
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
                            icon                : 'success',
                            title               : r.msg,
                            showConfirmButton   : false,
                            timer               : 1500
                        }).then(() => {
                            window.location.href = "{{ route('admin.create_order') }}"
                        }) 
                    },
                    dataType: 'json'
                });
            }
        });
    });
</script>