<script>
    $('document').ready(function()
    {
        Echo.channel('new-order').listen('NewOrderEvent', (e) => {
            cargarSonido("mp3/notification.mp3");
            setTimeout(() => {
                cargarSonido("mp3/notification.mp3");
            }, 2000);
            reload_table();
            toast_msg(e.message, 'success');
        });

        Echo.channel('reload-table').listen('UpdateOrderEvent', (e) => {
            reload_table();
        });
    });

    $('body').on('click', '.btn-print-command', function()
    {
        event.preventDefault();
        let id      = $(this).data('id');
        $.ajax({
            url: "{{ route('admin.print_command') }}",
            method: "POST",
            data: {
                '_token': "{{ csrf_token() }}",
                id: id
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
                let pdf                 =   `{{ asset('files/orders/commands/${r.pdf}') }}`;
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

    $('body').on('click', '.btn-print-account', function()
    {
        event.preventDefault();
        let id      = $(this).data('id');
        $.ajax({
            url: "{{ route('admin.print_pre_account') }}",
            method: "POST",
            data: {
                '_token': "{{ csrf_token() }}",
                id: id
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
                let pdf                 =   `{{ asset('files/orders/pre-accounts/${r.pdf}') }}`;
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

    $('body').on('click', '.btn-confirm', function()
    {
        event.preventDefault();
        let id      = $(this).data('id');
        Swal.fire({
            title: 'Anular',
            text: "¿Desea anular el pedido?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Si, anular',
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
                    url         : "{{ route('admin.anulled_order') }}",
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

    const cargarSonido = function (fuente) {
        const sonido = document.createElement("audio");
        sonido.src = fuente;
        sonido.setAttribute("preload", "auto");
        sonido.setAttribute("controls", "none");
        sonido.style.display = "none"; // <-- oculto
        document.body.appendChild(sonido);
        return sonido.play();
    };

    // Gen voucher
    function open_modal_client() {
        $('#modalConfirmSale').css('z-index', '999');
    }

    function success_save_client(msg = null, type = null, idtipocomprobante = null, last_id = null) {
        toast_msg(msg, type);
        load_clients(idtipocomprobante);
        setTimeout(() => {
            $('#modalConfirmSale select[name="dni_ruc"]').val(last_id);
            $('#modalConfirmSale select[name="dni_ruc"]').trigger('change');
        }, 500);
    }

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

                $('#modalConfirmSale select[name="dni_ruc"]').html(html_clients).select2({
                    placeholder: "[SELECCIONE]",
                    dropdownParent: $('#modalConfirmSale')
                });
            },
            dataType: 'json'
        });
        return;
    }
    
    function load_serie() {
        $.ajax({
            url: "{{ route('admin.load_serie_pos') }}",
            method: 'POST',
            data: {
                '_token': "{{ csrf_token() }}"
            },
            success: function(r) {
                if (!r.status) {
                    toast_msg(r.msg, r.title, r.type);
                    return;
                }
                $('#modalConfirmSale input[name="iddocumento_tipo"]').val(2);
                $('#modalConfirmSale input[name="quantity_paying_2"]').val("0");
                $('#modalConfirmSale input[name="quantity_paying_3"]').val("0");
                $(`#modalConfirmSale input[name="type_document"][value="2"]`).prop('checked', true);
                $(`#modalConfirmSale input[name="type_document"][value="1"]`).prop('checked', false);
                $(`#modalConfirmSale input[name="type_document"][value="7"]`).prop('checked', false);
                $(`#modalConfirmSale input[name="type_document"][value="2"]`).parent().parent().addClass(
                    'checked');
                $(`#modalConfirmSale input[name="type_document"][value="1"]`).parent().parent().removeClass(
                    'checked');
                $(`#modalConfirmSale input[name="type_document"][value="7"]`).parent().parent().removeClass(
                    'checked');
                $('#modalConfirmSale #serie-sale').html(r.serie.serie + '-' + r.serie.correlativo);
            },
            dataType: 'json'
        });
    }

    $('body').on('click', '.btn-type-document', function() {
        event.preventDefault();
        let value = $(this).find('input[name="type_document"]').val();
        $.ajax({
            url: "{{ route('admin.get_serie_pos') }}",
            method: 'POST',
            data: {
                '_token': "{{ csrf_token() }}",
                idtipo_documento: value
            },
            success: function(r) {
                if (!r.status) {
                    $('#modalConfirmSale input[name="serie_sale"]').val('');
                    toast_msg(r.msg, r.type);
                    return;
                }

                let serie = r.serie;
                switch (parseInt(r.serie.idtipo_documento)) {
                    case 1:
                        $(`#modalConfirmSale input[name="type_document"][value="1"]`).prop(
                            'checked', true);
                        $(`#modalConfirmSale input[name="type_document"][value="2"]`).prop(
                            'checked', false);
                        $(`#modalConfirmSale input[name="type_document"][value="7"]`).prop(
                            'checked', false);
                        $(`#modalConfirmSale input[name="type_document"][value="1"]`).parent()
                            .parent().addClass('checked');
                        $(`#modalConfirmSale input[name="type_document"][value="2"]`).parent()
                            .parent().removeClass('checked');
                        $(`#modalConfirmSale input[name="type_document"][value="7"]`).parent()
                            .parent().removeClass('checked');
                        break;

                    case 2:
                        $(`#modalConfirmSale input[name="type_document"][value="2"]`).prop(
                            'checked', true);
                        $(`#modalConfirmSale input[name="type_document"][value="1"]`).prop(
                            'checked', false);
                        $(`#modalConfirmSale input[name="type_document"][value="7"]`).prop(
                            'checked', false);
                        $(`#modalConfirmSale input[name="type_document"][value="2"]`).parent()
                            .parent().addClass('checked');
                        $(`#modalConfirmSale input[name="type_document"][value="1"]`).parent()
                            .parent().removeClass('checked');
                        $(`#modalConfirmSale input[name="type_document"][value="7"]`).parent()
                            .parent().removeClass('checked');
                        break;

                    case 7:
                        $(`#modalConfirmSale input[name="type_document"][value="7"]`).prop(
                            'checked', true);
                        $(`#modalConfirmSale input[name="type_document"][value="1"]`).prop(
                            'checked', false);
                        $(`#modalConfirmSale input[name="type_document"][value="2"]`).prop(
                            'checked', false);
                        $(`#modalConfirmSale input[name="type_document"][value="7"]`).parent()
                            .parent().addClass('checked');
                        $(`#modalConfirmSale input[name="type_document"][value="1"]`).parent()
                            .parent().removeClass('checked');
                        $(`#modalConfirmSale input[name="type_document"][value="2"]`).parent()
                            .parent().removeClass('checked');
                        break;
                }

                $('#modalConfirmSale input[name="iddocumento_tipo"]').val(r.serie.idtipo_documento);
                $('#modalConfirmSale input[name="serie_sale"]').val(r.serie.serie + '-' + r.serie
                    .correlativo);
                $('#modalConfirmSale #serie-sale').text(r.serie.serie + '-' + r.serie.correlativo);
                let html_clientes = '';
                $.each(r.clientes, function(index, cliente) {
                    html_clientes +=
                        `<option value="${cliente.id}">${cliente.dni_ruc + ' - ' + cliente.nombres}</option>`;
                });

                $('#modalConfirmSale select[name="dni_ruc"]').html(html_clientes).select2({
                    placeholder: "[SELECCIONE]",
                    dropdownParent: $('#modalConfirmSale')
                });
            },
            dataType: 'json'
        });
        return;
    });

    $('body').on('click', '.btn-gen', function()
    {
        event.preventDefault();
        let id              = $(this).data('id');
        $.ajax({
            url             : "{{ route('admin.process_pay_order') }}",
            method          : "POST",
            data            : {
                '_token'    : "{{ csrf_token() }}",
                id          : id
            },
            beforeSend      : function(){
                block_content('#layout-content');
            },
            success         : function(r){
                if (!r.status) {
                    close_block('#layout-content');
                    toast_msg(r.msg, r.type);
                    return;
                }

                close_block('#layout-content');
                
                $('#modalConfirmSale input[name="idorder"]').val(r.order.id);
                $('#modalConfirmSale input[name="quantity_paying"]').val(parseFloat(r.order.total)
                    .toFixed(2));
                $('#modalConfirmSale #total_pay').text(parseFloat(r.order.total).toFixed(2));
                $('#modalConfirmSale #total_paying').text(parseFloat(r.order.total).toFixed(2));
                $('#modalConfirmSale #difference').text((parseFloat($('#total_pay').text() -
                    parseFloat($('#total_paying').text()))).toFixed(2));
                $('#modalConfirmSale select[name="modo_pago"] option[value="1"]').prop('selected',
                    true);
                $('#modalConfirmSale select[name="modo_pago_2"] option[value="2"]').prop('selected',
                    true);
                $('#modalConfirmSale select[name="modo_pago_3"] option[value="5"]').prop('selected',
                    true);
                $('#modalConfirmSale select[name="dni_ruc"]').select2({
                    placeholder: "[SELECCIONE]",
                    dropdownParent: $('#modalConfirmSale')
                });
                $('#modalConfirmSale select[name="dni_ruc"]').val(1).trigger('change');
                load_serie();
                $('#modalConfirmSale').modal('show');
            },
            dataType        : "json"
        });
    });

    $('input[name="quantity_paying"]').on('change', function() {
        let value = parseFloat($(this).val()),
            quantity_paying_2 = parseFloat($('input[name="quantity_paying_2"]').val()),
            quantity_paying_3 = parseFloat($('input[name="quantity_paying_3"]').val()),
            total = parseFloat($('#total_pay').text());

        if ($('input[name="quantity_paying"]').val() == "") {
            $('input[name="quantity_paying"]').val("0");
            return;
        }

        sum = (value + quantity_paying_2 + quantity_paying_3).toFixed(2);
        if (sum < total) {
            $('#total_paying').text(parseFloat(sum).toFixed(2));
            $('#difference').text((parseFloat(sum) - total).toFixed(2));
            $('.wrapper_difference').removeClass('text-success');
            $('.wrapper_difference').addClass('text-danger');
            $('.btn-confirm-pay').prop('disabled', true);
        } else {
            $('#total_paying').text(parseFloat(sum).toFixed(2));
            $('#difference').text((parseFloat(sum) - total).toFixed(2));
            $('.wrapper_difference').addClass('text-success');
            $('.wrapper_difference').removeClass('text-danger');
            $('.btn-confirm-pay').prop('disabled', false);
        }
    });

    $('input[name="quantity_paying_2"]').on('change', function() {
        let value = parseFloat($(this).val()),
            quantity_paying_2 = parseFloat($('input[name="quantity_paying"]').val()),
            quantity_paying_3 = parseFloat($('input[name="quantity_paying_3"]').val()),
            total = parseFloat($('#total_pay').text());

        if ($('input[name="quantity_paying_2"]').val() == "") {
            $('input[name="quantity_paying_2"]').val("0");
            return;
        }

        sum = (value + quantity_paying_2 + quantity_paying_3).toFixed(2);
        if (sum < total) {
            $('#total_paying').text(parseFloat(sum).toFixed(2));
            $('#difference').text((parseFloat(sum) - total).toFixed(2));
            $('.wrapper_difference').removeClass('text-success');
            $('.wrapper_difference').addClass('text-danger');
            $('.btn-confirm-pay').prop('disabled', true);
        } else {
            $('#total_paying').text(parseFloat(sum).toFixed(2));
            $('#difference').text((parseFloat(sum) - total).toFixed(2));
            $('.wrapper_difference').addClass('text-success');
            $('.wrapper_difference').removeClass('text-danger');
            $('.btn-confirm-pay').prop('disabled', false);
        }
    });

    $('input[name="quantity_paying_3"]').on('change', function() {
        let value = parseFloat($(this).val()),
            quantity_paying_2 = parseFloat($('input[name="quantity_paying"]').val()),
            quantity_paying_3 = parseFloat($('input[name="quantity_paying_2"]').val()),
            total = parseFloat($('#total_pay').text());

        if ($('input[name="quantity_paying_3"]').val() == "") {
            $('input[name="quantity_paying_3"]').val("0");
            return;
        }

        sum = (value + quantity_paying_2 + quantity_paying_3).toFixed(2);
        if (sum < total) {
            $('#total_paying').text(parseFloat(sum).toFixed(2));
            $('#difference').text((parseFloat(sum) - total).toFixed(2));
            $('.wrapper_difference').removeClass('text-success');
            $('.wrapper_difference').addClass('text-danger');
            $('.btn-confirm-pay').prop('disabled', true);
        } else {
            $('#total_paying').text(parseFloat(sum).toFixed(2));
            $('#difference').text((parseFloat(sum) - total).toFixed(2));
            $('.wrapper_difference').addClass('text-success');
            $('.wrapper_difference').removeClass('text-danger');
            $('.btn-confirm-pay').prop('disabled', false);
        }
    });

    $('body').on('click', '.btn-confirm-pay', function() {
        event.preventDefault();
        let form = $('#form-save-sale').serializeArray();
        form[form.length] = {
            "name": "idorder",
            "value": $('input[name="idorder"]').val()
        };
        form[form.length] = {
            "name": "iddocumento_tipo",
            "value": $('input[name="iddocumento_tipo"]').val()
        };
        form[form.length] = {
            "name": "quantity_paying",
            "value": $('input[name="quantity_paying"]').val()
        };
        form[form.length] = {
            "name": "quantity_paying_2",
            "value": $('input[name="quantity_paying_2"]').val()
        };
        form[form.length] = {
            "name": "quantity_paying_3",
            "value": $('input[name="quantity_paying_3"]').val()
        };
        form[form.length] = {
            "name": "dni_ruc",
            "value": $('select[name="dni_ruc"]').val()
        };
        form[form.length] = {
            "name": "modo_pago",
            "value": $('select[name="modo_pago"]').val()
        };
        form[form.length] = {
            "name": "modo_pago_2",
            "value": $('select[name="modo_pago_2"]').val()
        };
        form[form.length] = {
            "name": "modo_pago_3",
            "value": $('select[name="modo_pago_3"]').val()
        };
        form[form.length] = {
            "name": "_token",
            "value": "{{ csrf_token() }}"
        };
        form[form.length] = {
            "name": "serie_sale",
            "value": $('#serie-sale').text()
        };
        form[form.length] = {
            "name": "difference",
            "value": $('#difference').text()
        };

        $.ajax({
            url: "{{ route('admin.save_billing_order') }}",
            method: 'POST',
            data: form,
            beforeSend: function() {
                $('.btn-confirm-pay').prop('disabled', true);
                $('.text-confirm-pay').addClass('d-none');
                $('.text-confirm-payment').removeClass('d-none');
            },
            success: function(r) {
                if (!r.status) {
                    $('.btn-confirm-pay').prop('disabled', false);
                    $('.text-confirm-pay').removeClass('d-none');
                    $('.text-confirm-payment').addClass('d-none');
                    toast_msg(r.msg, r.type);
                    return;
                }

                if (r.type_document == '7') {
                    // Open ticket sale note
                    $('.btn-confirm-pay').prop('disabled', false);
                    $('.text-confirm-pay').removeClass('d-none');
                    $('.text-confirm-payment').addClass('d-none');
                    open_ticket_sn(r.pdf);
                } else {
                    // Send billing
                    send_data_sunat(r.id, r.pdf);
                }
            },
            dataType: 'json'
        });
    });

    function open_ticket_sn(ticket) {
        $('#modalConfirmSale').modal('hide');
        let pdf = `{{ asset('files/sale-notes/ticket/${ticket}') }}`;
        var iframe = document.createElement('iframe');
        iframe.style.display = "none";
        iframe.src = pdf;
        document.body.appendChild(iframe);
        iframe.contentWindow.focus();
        iframe.contentWindow.print();
        load_alerts();
        load_serie();
        load_clients(2);
    }

    function send_data_sunat(id, ticket)
    {
        $.ajax({
            url             : "{{ route('admin.send_bf') }}",
            method          : "POST",
            data            : {
                '_token'    : "{{ csrf_token() }}",
                id          : id
            },
            beforeSend      : function(){
                $('.btn-confirm-pay').prop('disabled', true);
                $('.text-confirm-pay').addClass('d-none');
                $('.text-confirm-payment').removeClass('d-none');
            },
            success         : function(r){
                if(!r.status){}
                $('.btn-confirm-pay').prop('disabled', false);
                $('.text-confirm-pay').removeClass('d-none');
                $('.text-confirm-payment').addClass('d-none');

                let ip          = r.empresa.url_api,
                    api         = "Api/index.php",
                    datosJSON   = JSON.stringify(r.data);
                    datosJSON   = unescape(encodeURIComponent(datosJSON)),
                    idfactura   = parseInt(r.idfactura);

                    $.ajax({    
                        url         : ip + api,
                        method      : 'POST',
                        data        : {datosJSON},
                        beforeSend  : function(){
                            $('.btn-confirm-pay').prop('disabled', true);
                            $('.text-confirm-pay').addClass('d-none');
                            $('.text-confirm-payment').removeClass('d-none');
                    },
                    
                    }).done(function(res){
                        $('.btn-confirm-pay').prop('disabled', false);
                        $('.text-confirm-pay').removeClass('d-none');
                        $('.text-confirm-payment').addClass('d-none');
                        if (res.trim() == "No se registró") 
                        {
                            toast_msg('El número de comprobante electrónico esta duplicado, revise la base de datos', 'error');
                            return;
                        }

                        let respuesta_sunat = JSON.parse(res),
                            estado_conexion = JSON.parse(respuesta_sunat).status;
                         
                        $('#modalConfirmSale').modal('hide');
                        let pdf = `{{ asset('files/billings/ticket/${ticket}') }}`;
                        var iframe = document.createElement('iframe');
                        iframe.style.display = "none";
                        iframe.src = pdf;
                        document.body.appendChild(iframe);
                        iframe.contentWindow.focus();
                        iframe.contentWindow.print();
                        $('input[name="input-search-product"]').val('');
                        load_alerts();
                        load_serie();
                        load_clients(2);
                        if(estado_conexion != false)
                        {
                            update_cdr(idfactura);
                        }
                    }).fail(function(jqxhr, textStatus, error){
                        $('.btn-confirm-pay').prop('disabled', false);
                        $('.text-confirm-pay').removeClass('d-none');
                        $('.text-confirm-payment').addClass('d-none');
                        $('#modalConfirmSale').modal('hide');
                        let pdf = `{{ asset('files/billings/ticket/${ticket}') }}`;
                        var iframe = document.createElement('iframe');
                        iframe.style.display = "none";
                        iframe.src = pdf;
                        document.body.appendChild(iframe);
                        iframe.contentWindow.focus();
                        iframe.contentWindow.print();
                        $('input[name="input-search-product"]').val('');
                        load_alerts();
                        load_serie();
                        load_clients(2);
                    });
            },
            dataType        : "json"
        });
    }

    function update_cdr(idfactura)
    {
        let resp = '';
        $.ajax({
            url     : "{{ route('admin.update_cdr_bf') }}",
            method  : 'POST',
            data    : {
                '_token'   : "{{ csrf_token() }}",
                idfactura  : idfactura
            },
            success : function(r){},
            dataType : 'json'
        });
    }

    $('body').on('click', '.btn-open-whatsapp', function()
    {
        let id                  = $(this).data('id'),
            idtipo_documento    = $(this).data('idtipo_documento'),
            idventa             = $(this).data('idventa'),
            type_document       = (idtipo_documento == "7") ? 'sale_note' : 'billing';
        $('#modalSendWpp .btn-whatsapp').attr('id', idventa);
        $('#modalSendWpp .btn-whatsapp').attr('type_document', type_document);
        $('#modalSendWpp').modal('show');
    });

    $('body').on('click', '#modalSendWpp .btn-whatsapp', function()
    {
        event.preventDefault();
        let id              = $(this).attr('id'),
            type_document   = $(this).attr('type_document'),
            input__phone    = $('#modalSendWpp input[name="input__phone"]').val(),
            html            = '';

        $.ajax({
            url     : "{{ route('admin.send_voucher') }}",
            method  : "POST",
            data    : {
                '_token'        : "{{ csrf_token() }}",
                id              : id,
                input__phone    : input__phone,
                type_document   : type_document
            },
            beforeSend   : function(){
                $('#modalSendWpp .text-send').addClass('d-none');
                $('#modalSendWpp .text-sending').removeClass('d-none');
            },
            success : function(r)
            {
                if(!r.status)
                {
                    $('#modalSendWpp .text-send').removeClass('d-none');
                    $('#modalSendWpp .text-sending').addClass('d-none');
                    toast_msg(r.msg, r.type);
                    return;
                }

                $('#modalSendWpp .text-send').removeClass('d-none');
                $('#modalSendWpp .text-sending').addClass('d-none');
                $('#modalSendWpp input[name="input__phone"]').val("");
                toast_msg(r.msg, r.type);
            },
            dataType: "json"
        });
    });
</script>