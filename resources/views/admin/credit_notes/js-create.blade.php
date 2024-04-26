<script>
    $('body').on('click', '.btn-save', function()
    {
        event.preventDefault();
        let form    = $('#form_save_credit_note').serialize(),
            motivo  = $('input[name="motivo"]');

            if(motivo.val().trim() == '')
                motivo.addClass('is-invalid');
            else
                motivo.removeClass('is-invalid');

            if(motivo.val().trim() != '')
            {
                $.ajax({
                    url         : "{{ route('admin.save_nc') }}",
                    method      : 'POST',
                    data        : form,
                    beforeSend  : function(){
                        $('.btn-save').prop('disabled', true);
                        $('.text-save').addClass('d-none');
                        $('.text-saving').removeClass('d-none');
                    },
                    success     : function(r){
                        if(!r.status)
                        {
                            $('.btn-save').prop('disabled', false);
                            $('.text-save').removeClass('d-none');
                            $('.text-saving').addClass('d-none');
                            toast_msg(r.msg, r.type);
                            return; 
                        }

                        $('.btn-save').prop('disabled', false);
                        $('.text-save').removeClass('d-none');
                        $('.text-saving').addClass('d-none');
                        send_nc(r.id, r.idrelacionado,r.pdf);
                    },
                    dataType    : 'json'
                });
            }
    });

    function send_nc(id, idrelacionado, ticket)
    {
        $.ajax({
            url         : "{{ route('admin.send_nc') }}",
            method      : 'POST',
            data        : {'_token' : "{{ csrf_token() }}", id : id},
            beforeSend  : function(){
                $('.btn-save').prop('disabled', true);
                $('.text-save').addClass('d-none');
                $('.text-saving').removeClass('d-none');
            },
            success     : function(r){
                if(!r.status){}

                $('.btn-save').prop('disabled', false);
                $('.text-save').removeClass('d-none');
                $('.text-saving').addClass('d-none');
                
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
                            $('.btn-save').prop('disabled', true);
                            $('.text-save').addClass('d-none');
                            $('.text-saving').removeClass('d-none');
                    },
                    }).done(function(res){

                        $('.btn-save').prop('disabled', false);
                        $('.text-save').removeClass('d-none');
                        $('.text-saving').addClass('d-none');
                        if (res.trim() == "No se registró") 
                        {
                            toast_msg('El número de comprobante electrónico esta duplicado, revise la base de datos', 'error');
                            return;
                        }

                        load_alerts();
                        let respuesta_sunat = JSON.parse(res),
                            estado_conexion = JSON.parse(respuesta_sunat).status;
                        let pdf = `{{ asset('files/billings/ticket/${ticket}') }}`;
                        var iframe = document.createElement('iframe');
                        iframe.style.display = "none";
                        iframe.src = pdf;
                        document.body.appendChild(iframe);
                        iframe.contentWindow.focus();
                        iframe.contentWindow.print();
                        if(estado_conexion != false)
                        {
                            update_cdr_nc(id, idrelacionado);
                        }
                    }).fail(function(jqxhr, textStatus, error){
                        $('.btn-save').prop('disabled', false);
                        $('.text-save').removeClass('d-none');
                        $('.text-saving').addClass('d-none');
                        load_alerts();
                        let pdf = `{{ asset('files/billings/ticket/${ticket}') }}`;
                        var iframe = document.createElement('iframe');
                        iframe.style.display = "none";
                        iframe.src = pdf;
                        document.body.appendChild(iframe);
                        iframe.contentWindow.focus();
                        iframe.contentWindow.print();
                    });
            },
            dataType    : 'json'
        });
    }

    function update_cdr_nc(id, idrelacionado)
    {
        $.ajax({
            url     : "{{ route('admin.update_cdr_nc') }}",
            method  : 'POST',
            data    : {
                '_token'    : "{{ csrf_token() }}",
                id   : id,
                idrelacionado: idrelacionado
            },
            success : function(r)
            {
                return true;
            },
            dataType : 'json'
        });
    }
</script>