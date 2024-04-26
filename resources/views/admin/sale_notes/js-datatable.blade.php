<script>
    function load_datatable()
    {
        let datatable = $('#table').DataTable({
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
            "ajax"      : "{{ route('admin.get_sale_notes') }}",
            "columns"   : [
                {
                    data        : 'documento',
                    className   : 'text-center'
                },
                {
                    data        : 'fecha_de_emision',
                    className   : 'text-center'
                },
                {
                    data        : 'dni_ruc',
                    className   : 'text-center'
                },
                {
                    data        : 'cliente',
                    className   : 'text-left'
                },
                {
                    data        : 'total',
                    className   : 'text-center'
                },
                {
                    data        : 'estado',
                    className   : 'text-center'
                },
                {
                    data        : 'acciones',
                    className   : 'text-center'
                },
            ]
        });
    }
    load_datatable();
</script>