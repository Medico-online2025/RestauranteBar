<script>
    $('body').on('click', '.btn-create', function()
    {
        event.preventDefault();
        $('#modalArchingCash').modal('show');
    });

    $('body').on('click', '.btn-save', function()
    {
        event.preventDefault();
        let form            = $('#form_save').serialize(),
            monto_inicial   = $('input[name="monto_inicial"]');

        if(monto_inicial.val() == '')
            monto_inicial.addClass('is-invalid');
        else
            monto_inicial.removeClass('is-invalid');

        if(monto_inicial.val().trim() != '')
        {
            $.ajax({
                url         :  "{{ route('admin.save_arching_cash') }}",
                method      : 'POST',
                data        : form,
                beforeSend  : function(){
                    $('.btn-save').prop('disabled', true);
                    $('.text-saving').removeClass('d-none');
                    $('.text-save').addClass('d-none');
                },
                success     : function(r)
                {
                    if(!r.status)
                    {
                        $('.btn-save').prop('disabled', false);
                        $('.text-saving').addClass('d-none');
                        $('.text-save').removeClass('d-none');
						toast_msg(r.msg, r.type);
                        return;
                    }

                    $('#modalArchingCash').modal('hide');
                    $('#form_save').trigger('reset');
                    $('.btn-save').prop('disabled', false);
                    $('.text-save').removeClass('d-none');
                    $('.text-saving').addClass('d-none');
                    toast_msg(r.msg, r.type);
                    reload_table();
                },
                dataType    : 'json'
            });
            return;
        }
    });

    $('body').on('click', '.btn-confirm', function()
    {
        event.preventDefault();
        let id      = $(this).data('id');
        Swal.fire({
            title: 'Confirmar cierre de caja',
            text: "¿Desea cerrar caja?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Si, cerrar',
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
                    url         : "{{ route('admin.close_cash') }}",
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

    $('body').on('click', '.btn-detail-cash', function()
    {
        event.preventDefault();
        let id          = $(this).data('id');
        $.ajax({
            url         : "{{ route('admin.get_detail_cash') }}",
            method      : "POST",
            data        : {
                '_token': "{{ csrf_token() }}",
                id      : id
            },
            beforeSend  : function(){
                block_content('#layout-content');
            },
            success     : function(r){
                if(!r.status)
                {
                    close_block('#layout-content');
                    toast_msg(r.msg, r.type);
                    return;
                }

                close_block('#layout-content');
                load_datatable_detail(r.id);
                $('#modalDetailArchingCash').modal('show');
            },
            dataType    : 'json'
        });
    });

    function load_datatable_detail(id)
    {
        let datatable = $('#table_detail').DataTable({
            serverSide  :true,
            "paging"    :true,
            "searching" :true,
            "destroy"   :true,
            responsive  :true,
            ordering    :false,
            autoWidth   :false,
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
            language: {
                "decimal": "",
                "emptyTable": "No hay información",
                "info": "Mostrando _START_ a _END_ de _TOTAL_ Registros",
                "infoEmpty": "Mostrando 0 a 0 de 0 Registros",
                "infoFiltered": "(Filtrado de _MAX_ total Registros)",
                "infoPostFix": "",
                "thousands": ",",
                "lengthMenu": "Mostrar _MENU_ Registros",
                "loadingRecords": "Cargando...",
                "processing": "Procesando...",
                "search": "",
                "searchPlaceholder": "",
                "zeroRecords": "Sin resultados encontrados",
                "paginate": {
                    "first": "Primero",
                    "last": "Ultimo",
                    "next": "Siguiente",
                    "previous": "Anterior"
                }
            },
            "ajax"      : {
                'url'   : "{{ route('admin.get_detail_cashes') }}",
                'data'  : {
                    '_token'    : "{{ csrf_token() }}",
                    'id'        : id
                },
                'type'  : 'POST'
            },
            "columns"   : [
                {
                    data        : 'id',
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                    className   : 'text-center'
                },
                {
                    data        : 'fecha',
                    className   : 'text-center'
                },
                {
                    data        : 'hora',
                    className   : 'text-center'
                },
                {
                    data        : 'cliente',
                    className   : 'text-center'
                },
                {
                    data        : 'documento',
                    className   : 'text-center'
                },
                {
                    data        : 'documento',
                    className   : 'text-center'
                },
                {
                    data        : 'total',
                    className   : 'text-center'
                }
            ]
        });
    }

    $('body').on('click', '.btn-summary', function()
    {
        event.preventDefault();
        let id          = $(this).data('id');
        $.ajax({
            url         : "{{ route('admin.get_summary') }}",
            method      : "POST",
            data        : {
                '_token': "{{ csrf_token() }}",
                id      : id
            },
            beforeSend  : function(){
                block_content('#layout-content');
            },
            success     : function(r){
                if(!r.status)
                {
                    close_block('#layout-content');
                    toast_msg(r.msg, r.type);
                    return;
                }

                close_block('#layout-content');
                $('#modalSummary .starting_amount').html(`S/${r.monto_inicial}`);
                if(r.bills_empty)
                {
                    $('#modalSummary #wrapper_bills').addClass('d-none');
                    $('#modalSummary .bill_empty').removeClass('d-none');
                    $('#modalSummary .bill_empty').html(r.html_bills);
                }
                else
                {
                    $('#modalSummary #wrapper_bills').removeClass('d-none');
                    $('#modalSummary .bill_empty').addClass('d-none');
                    $('#modalSummary #wrapper_bills').html(r.html_bills);
                }

                if(r.sales_empty) {
                    $('#modalSummary #wrapper_sales').addClass('d-none');
                    $('#modalSummary .sale_empty').removeClass('d-none');
                    $('#modalSummary .sale_empty').html(r.html_sales);
                }
                else {
                    $('#modalSummary #wrapper_sales').removeClass('d-none');
                    $('#modalSummary .sale_empty').addClass('d-none');
                    $('#modalSummary #wrapper_sales').html(r.html_sales);
                }

                $('#modalSummary .sales_amount').html(`S/${r.monto_ventas}`);
                $('#modalSummary .quantity').html(r.cantidad_ventas);
                $('#modalSummary .total').html(`S/${r.total}`);
                $('#modalSummary').modal('show');
            },
            dataType    : "json"
        });
    });

    $('body').on('click', '.btn-download', function()
    {
        event.preventDefault();
        let id              = $(this).data('id');
        base_url            = "{{ url('/') }}",
        url_print           = `${base_url}/download-detail-cash/${id}`;
        window.open(url_print);
    });
</script>