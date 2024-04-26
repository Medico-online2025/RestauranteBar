<script>
    $('body').on('click', '.btn-print', function() {
        event.preventDefault();
        let id = $(this).data('id');
        $.ajax({
            url: "{{ route('admin.print_quote') }}",
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
                let pdf                 =   `{{ asset('files/quotes/${r.pdf}') }}`;
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

    $('body').on('click', '.btn-download', function()
    {
        event.preventDefault();
        let id              = $(this).data('id');
        base_url            = "{{ url('/') }}",
        url_print           = `${base_url}/download-quote/${id}`;
        window.open(url_print);
    });

    $('body').on('click', '.btn-open-whatsapp', function()
    {
        let id = $(this).data('id');
        $('#modalSendWpp .btn-whatsapp').attr('id', id);
        $('#modalSendWpp .btn-whatsapp').attr('type_document', 'quote');
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

    $('body').on('click', '.btn-confirm', function() {
        event.preventDefault();
        let id = $(this).data('id'),
        idtipo_comprobante  = $(this).data('idtipo_comprobante');
        Swal.fire({
            title: 'Generar Comprobante',
            text: "¿Desea generar el comprobante de esta cotización?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Si, generar',
            cancelButtonText: 'Cancelar',
            customClass: {
                confirmButton: 'btn btn-primary',
                cancelButton: 'btn btn-outline-danger ml-1'
            },
            buttonsStyling: false
        }).then(function (result) {
            if (result.value) 
            {
                block_content('#layout-content');
                $.ajax({
                    url         : "{{ route('admin.gen_quote_voucher') }}",
                    method      : 'POST',
                    data        : {
                        '_token': "{{ csrf_token() }}",
                        id      : id,
                        idtipo_comprobante: idtipo_comprobante
                    },
                    success     : function(r){
                        if(!r.status)
                        {
                            close_block('#layout-content');
                            toast_msg(r.msg, r.type);
                            return;
                        }

                        reload_table();
                        send_data_sunat(r.id, r.pdf);
                    },
                    dataType    : 'json'
                });
            }
        });
    });

    function send_data_sunat(id, ticket)
    {
        $.ajax({
            url             : "{{ route('admin.send_bf') }}",
            method          : "POST",
            data            : {
                '_token'    : "{{ csrf_token() }}",
                id          : id
            },
            success         : function(r){
                if(!r.status){}

                let ip          = r.empresa.url_api,
                    api         = "Api/index.php",
                    datosJSON   = JSON.stringify(r.data);
                    datosJSON   = unescape(encodeURIComponent(datosJSON)),
                    idfactura   = parseInt(r.idfactura);

                    $.ajax({    
                        url         : ip + api,
                        method      : 'POST',
                        data        : {datosJSON},
                    }).done(function(res){
                        close_block('#layout-content');
                        if (res.trim() == "No se registró") 
                        {
                            toast_msg('El número de comprobante electrónico esta duplicado, revise la base de datos', 'error');
                            return;
                        }

                        let respuesta_sunat = JSON.parse(res),
                            estado_conexion = JSON.parse(respuesta_sunat).status;
                         
                        let pdf = `{{ asset('files/billings/ticket/${ticket}') }}`;
                        var iframe = document.createElement('iframe');
                        iframe.style.display = "none";
                        iframe.src = pdf;
                        document.body.appendChild(iframe);
                        iframe.contentWindow.focus();
                        iframe.contentWindow.print();
                        load_alerts();
                        if(estado_conexion != false)
                        {
                            update_cdr(idfactura);
                        }
                    }).fail(function(jqxhr, textStatus, error){
                        close_block('#layout-content');
                        let pdf = `{{ asset('files/billings/ticket/${ticket}') }}`;
                        var iframe = document.createElement('iframe');
                        iframe.style.display = "none";
                        iframe.src = pdf;
                        document.body.appendChild(iframe);
                        iframe.contentWindow.focus();
                        iframe.contentWindow.print();
                        load_alerts();
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
</script>