<script>
    $('body').on('click', '.btn-send-sunat', function() {
        event.preventDefault();
        block_content('#layout-content');
        let id = $(this).data('id');
        $.ajax({
            url: "{{ route('admin.send_bf') }}",
            method: 'POST',
            data: {
                '_token': "{{ csrf_token() }}",
                id: id
            },
            success: function(r) {
                if (!r.status) {
                    close_block('#layout-content');
                    toast_msg(r.msg, r.type);
                    return;
                }

                let ip = r.empresa.url_api,
                    api = "Api/index.php",
                    datosJSON = JSON.stringify(r.data);
                datosJSON = unescape(encodeURIComponent(datosJSON)),
                    idfactura = parseInt(r.idfactura);
                send(idfactura, datosJSON, ip, api);
            },
            dataType: 'json'
        });
    });

    function send(idfactura, datosJSON, ip, api) {
        $.ajax({
            url: ip + api,
            method: 'POST',
            data: {
                datosJSON
            },
        }).done(function(res) {
            close_block('#layout-content');
            if (res.trim() == "No se registró") {
                toast_msg('El número de comprobante electrónico esta duplicado, revise la base de datos',
                    'error');
                return;
            }
            let respuesta_sunat = JSON.parse(res),
                estado_conexion = JSON.parse(respuesta_sunat).status,
                cod_respuesta = JSON.parse(respuesta_sunat).codigo_respuesta[0],
                des_respuesta = JSON.parse(respuesta_sunat).des_respuesta[0];

            if (estado_conexion == false) {
                toast_msg("El comprobante no se envió, intente de nuevo", 'warning');
                return;
            }

            if (parseInt(cod_respuesta) == 0) {
                toast_msg(des_respuesta, 'success');
                update_cdr(idfactura);
            }
            load_alerts();
            reload_table();
        }).fail(function(jqxhr, textStatus, error) {
            let err = textStatus + ", " + error;
            close_block('#layout-content');
            toast_msg("Error al enviar: " + err + '. Consulte con el administrador', 'error');
        });
    }

    function update_cdr(idfactura) {
        let resp = '';
        $.ajax({
            url: "{{ route('admin.update_cdr_bf') }}",
            method: 'POST',
            data: {
                '_token': "{{ csrf_token() }}",
                idfactura: idfactura
            },
            success: function(r) {},
            dataType: 'json'
        });
    }

    $('body').on('click', '.btn-print', function() {
        let id = $(this).data('id');
        $.ajax({
            url: "{{ route('admin.print_billing') }}",
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
                let pdf = `{{ asset('files/billings/ticket/${r.pdf}') }}`;
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

    $('body').on('click', '.btn-download', function() {
        event.preventDefault();
        let id = $(this).data('id');
        base_url = "{{ url('/') }}",
            url_print = `${base_url}/download-billing/${id}`;
        window.open(url_print);
    });

    $('body').on('click', '.btn-open-whatsapp', function()
    {
        let id = $(this).data('id');
        $('#modalSendWpp .btn-whatsapp').attr('id', id);
        $('#modalSendWpp .btn-whatsapp').attr('type_document', 'billing');
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
