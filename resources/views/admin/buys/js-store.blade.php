<script>
    $('body').on('click', '.btn-print', function()
    {
        let id = $(this).data('id');
        $.ajax({
            url: "{{ route('admin.print_buy') }}",
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
                let pdf                 =   `{{ asset('files/buys/${r.pdf}') }}`;
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
        url_print           = `${base_url}/download-buy/${id}`;
        window.open(url_print);
    });

    $('body').on('click', '.btn-confirm', function()
    {
        event.preventDefault();
        let id      = $(this).data('id');
        Swal.fire({
            title: 'Eliminar',
            text: "¿Desea eliminar el registro?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Si, eliminar',
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
                    url         : "{{ route('admin.delete_buy') }}",
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

    $('body').on('click', '.btn-open-whatsapp', function()
    {
        let id = $(this).data('id');
        $('#modalSendWpp .btn-whatsapp').attr('id', id);
        $('#modalSendWpp .btn-whatsapp').attr('type_document', 'buy');
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