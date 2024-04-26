<script>
    function success_save_client(msg = null, type = null, idtipocomprobante = null, last_id = null)
    {
        toast_msg(msg, type);
        reload_table();
    }

    function open_modal_client()
    {}

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
                    url         : "{{ route('admin.delete_client') }}",
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

    $('body').on('click', '.btn-detail', function() {
        event.preventDefault();
        let id = $(this).data('id');
        $.ajax({
            url: "{{ route('admin.detail_client') }}",
            method: 'POST',
            data: {
                '_token': "{{ csrf_token() }}",
                id: id
            },
            beforeSend  : function(){
                block_content('#layout-content');
            },
            success: function(r) {
                if (!r.status) {
                    close_block('#layout-content');
                    toast_msg(r.msg, r.title, r.type);
                    return;
                }
                close_block('#layout-content');
                html_department = '';
                html_province = '';
                html_district = '';

                $(`#form_edit_client select[name="tipo_documento"] option[value="${r.client.iddoc}"]`)
                    .prop('selected', true);
                switch (r.client.iddoc) {
                    case 2:
                        $('#form_edit_client #wrapper-input-reniec').removeClass('d-none');
                        $('#form_edit_client .input-text-reniec').text('RENIEC');
                        break;

                    case 4:
                        $('#form_edit_client #wrapper-input-reniec').removeClass('d-none');
                        $('#form_edit_client .input-text-reniec').text('SUNAT');
                        break;

                    default:
                        $('#form_edit_client #wrapper-input-reniec').addClass('d-none');
                        break;
                }

                $(`#form_edit_client input[name="id"]`).val(r.client.id);
                $(`#form_edit_client input[name="dni_ruc"]`).val(r.client.dni_ruc);
                $(`#form_edit_client input[name="razon_social"]`).val(r.client.nombres);
                $(`#form_edit_client input[name="direccion"]`).val(r.client.direccion);
                $(`#form_edit_client input[name="telefono"]`).val(r.client.telefono);

                // Ubigeo
                $.each(r.departments, function(index, department) {
                    if (department.codigo == r.department.codigo)
                        html_department +=
                        `<option value="${department.codigo}" selected>${department.descripcion}</option>`;
                    else
                        html_department +=
                        `<option value="${department.codigo}">${department.descripcion}</option>`;
                });

                $.each(r.provinces, function(index, province) {
                    if (province.codigo == r.province.codigo)
                        html_province +=
                        `<option value="${province.codigo}" selected>${province.descripcion}</option>`;
                    else
                        html_province +=
                        `<option value="${province.codigo}">${province.descripcion}</option>`;
                });

                $.each(r.districts, function(index, district) {
                    if (district.codigo == r.district.codigo)
                        html_district +=
                        `<option value="${district.codigo}" selected>${district.descripcion}</option>`;
                    else
                        html_district +=
                        `<option value="${district.codigo}">${district.descripcion}</option>`;
                });

                $('#form_edit_client select[name="departamento"]').html(html_department).select2({
                    placeholder     : "[SELECCIONE]",
                    dropdownParent  : $('#modalEditClient')
                });
                $('#form_edit_client select[name="provincia"]').html(html_province).select2({
                    placeholder     : "[SELECCIONE]",
                    dropdownParent  : $('#modalEditClient')
                });
                
                $('#form_edit_client select[name="distrito"]').html(html_district).select2({
                    placeholder     : "[SELECCIONE]",
                    dropdownParent  : $('#modalEditClient')
                });

                $('#modalEditClient').modal('show');
            },
            dataType: 'json'
        });
    });

    $('#form_edit_client select[name="tipo_documento"]').on('change', function() {
        let value = $(this).val();
        switch (value) {
            case '2':
                $('#form_edit_client #wrapper-input-reniec').removeClass('d-none');
                $('#form_edit_client #wrapper-input-reniec').removeClass('d-none');
                $('#form_edit_client .input-text-reniec').text('RENIEC');
                $('#form_edit_client .input-text-reniec').text('RENIEC');
                break;

            case '4':
                $('#wrapper-input-reniec').removeClass('d-none');
                $('#form_edit_client #wrapper-input-reniec').removeClass('d-none');
                $('#form_edit_client .input-text-reniec').text('SUNAT');
                $('#form_edit_client .input-text-reniec').text('SUNAT');
                break;

            default:
                $('#form_edit_client #wrapper-input-reniec').addClass('d-none');
                $('#form_edit_client #wrapper-input-reniec').addClass('d-none');
                break;
        }
    });

    $('#form_edit_client select[name="departamento"]').on('change', function() {
        let value = $(this).val();
        $.ajax({
            url: "{{ route('admin.load_provinces') }}",
            method: 'POST',
            data: {
                '_token': "{{ csrf_token() }}",
                codigo: value
            },
            success: function(r) {
                let html_province = '<option></option>';
                $.each(r.provinces, function(index, province) {
                    html_province +=
                        `<option value="${province.codigo}">${province.descripcion}</option>`;
                });

                $('#form_edit_client #wrapper_province').removeClass('d-none');
                $('#form_edit_client select[name="provincia"]').html(html_province).select2({
                    placeholder     : "[SELECCIONE]",
                    dropdownParent  : $('#modalEditClientClient')
                });
            },
            dataType: 'json'
        });
    });

    $('#form_edit_client select[name="provincia"]').on('change', function() {
        let value = $(this).val(),
            codigo_departamento = $('#form_edit_client select[name="departamento"]').val();

        $.ajax({
            url: "{{ route('admin.load_districts') }}",
            method: 'POST',
            data: {
                '_token': "{{ csrf_token() }}",
                codigo: value,
                codigo_departamento: codigo_departamento
            },
            success: function(r) {
                let html_district = '<option></option>';
                $.each(r.districts, function(index, district) {
                    html_district +=
                        `<option value="${district.codigo}">${district.descripcion}</option>`;
                });

                $('#form_edit_client #wrapper_district').removeClass('d-none');
                $('#form_edit_client select[name="distrito"]').html(html_district).select2({
                    placeholder     : "[SELECCIONE]",
                    dropdownParent  : $('#modalEditClientClient')
                });
            },
            dataType: 'json'
        });
    });

    $('body').on('click', '#form_edit_client .btn-search-dniruc', function() {
        event.preventDefault();
        $('#form_edit_client #wrapper_province').addClass('d-none');
        $('#form_edit_client #wrapper_district').addClass('d-none');
        let type_document = $('#form_edit_client select[name="tipo_documento"]').val(),
            dni_ruc = $('#form_edit_client input[name="dni_ruc"]').val();

        if (type_document == 2) {
            if (dni_ruc.length != 8) {
                toast_msg('Ingrese un número válido', 'warning');
                return;
            }
        }

        if (type_document == 4) {
            if (dni_ruc.length != 11) {
                toast_msg('Ingrese un número válido', 'warning');
                return;
            }
        }

        $.ajax({
            url: "{{ route('admin.search_dni_ruc') }}",
            method: 'POST',
            data: {
                '_token': "{{ csrf_token() }}",
                type_document: type_document,
                dni_ruc: dni_ruc
            },
            beforeSend: function() {
                $('#form_edit_client .btn-search-dniruc').prop('disabled', true);
                $('#form_edit_client .text-search').addClass('d-none');
                $('#form_edit_client .text-searching').removeClass('d-none');
            },
            success: function(r) {
                if (!r.status) {
                    $('#form_edit_client .btn-search-dniruc').prop('disabled', false);
                    $('#form_edit_client .text-search').removeClass('d-none');
                    $('#form_edit_client .text-searching').addClass('d-none');
                    toast_msg(r.msg, r.type);
                    return;
                }

                let ubigeo  = r.ubigeo;
                if(ubigeo != "")
                    if(ubigeo != null)
                        active__ubigeo__edit(ubigeo);

                $('#form_edit_client .btn-search-dniruc').prop('disabled', false);
                $('#form_edit_client .text-search').removeClass('d-none');
                $('#form_edit_client .text-searching').addClass('d-none');
                $('#form_edit_client input[name="razon_social"]').val(r.nombres);
                $('#form_edit_client input[name="direccion"]').val(r.direccion);
            },
            dataType: 'json'
        });
    });

    function active__ubigeo__edit(ubigeo)
    {
        let departamento = ubigeo.substring(0, 2),
            provincia    = ubigeo.substring(0, 4),
            distrito     = ubigeo.substring(0, 6);

        $('#form_edit_client select[name="departamento"]').val(departamento).trigger('change');
        setTimeout(() => {
            $('#form_edit_client select[name="provincia"]').val(provincia).trigger('change');
        }, 600);
        setTimeout(() => {
            $('#form_edit_client select[name="distrito"]').val(distrito).trigger('change');
        }, 900);
    }

    $('body').on('click', '#form_edit_client .btn-store-client', function()
    {
        event.preventDefault();
        let form                = $('#form_edit_client').serialize(),
            tipo_documento      = $('#form_edit_client select[name="tipo_documento"]'),
            dni_ruc             = $('#form_edit_client input[name="dni_ruc"]'),
            razon_social        = $('#form_edit_client input[name="razon_social"]'),
            direccion           = $('#form_edit_client input[name="direccion"]');


            if (tipo_documento.val() == '0')
            tipo_documento.addClass('is-invalid');
            else
            tipo_documento.removeClass('is-invalid');

            if(dni_ruc.val().trim() == '')
                dni_ruc.addClass('is-invalid');
            else
                dni_ruc.removeClass('is-invalid');

            if(razon_social.val().trim() == '')
                razon_social.addClass('is-invalid');
            else
                razon_social.removeClass('is-invalid');

            if(direccion.val().trim() == '')
                direccion.addClass('is-invalid');
            else
                direccion.removeClass('is-invalid');

            if(tipo_documento.val() != '0' && dni_ruc.val().trim() != '' && 
                razon_social.val().trim() != '' && direccion.val().trim() != '')
            {
                $.ajax({
                    url         : "{{ route('admin.store_client') }}",
                    method      : 'POST',
                    data        : form,
                    beforeSend  : function(){
                        $('.btn-store-client').prop('disabled', true);
                        $('.text-store-client').addClass('d-none');
                        $('.text-storing-client').removeClass('d-none');
                    },
                    success     : function(r){
                        if(!r.status)
                        {
                            $('.btn-store-client').prop('disabled', false);
                            $('.text-store-client').removeClass('d-none');
                            $('.text-storing-client').addClass('d-none');
                            toast_msg(r.msg, r.type);
                            return; 
                        }
                        
                        $('.btn-store-client').prop('disabled', false);
                        $('.text-store-client').removeClass('d-none');
                        $('.text-storing-client').addClass('d-none');
                        $('#modalEditClient').modal('hide');
                        toast_msg(r.msg, r.type);
                        reload_table();
                    },
                    dataType    : 'json'
                });
            }
    });
</script>