<script>
    $('body').on('click', '.btn-create-client', function()
    {
        event.preventDefault();
        open_modal_client();
        $('#modalAddClient').modal('show');
    });

    // Type document
    $('#form_save_client select[name="tipo_documento"]').on('change', function() {
        let value = $(this).val();
        switch (value) {
            case '2':
                $('#form_save_client #wrapper-input-reniec').removeClass('d-none');
                $('#form_save_client #wrapper-input-reniec').removeClass('d-none');
                $('#form_save_client .input-text-reniec').text('RENIEC');
                $('#form_save_client .input-text-reniec').text('RENIEC');
                break;

            case '4':
                $('#wrapper-input-reniec').removeClass('d-none');
                $('#form_save_client #wrapper-input-reniec').removeClass('d-none');
                $('#form_save_client .input-text-reniec').text('SUNAT');
                $('#form_save_client .input-text-reniec').text('SUNAT');
                break;

            default:
                $('#form_save_client #wrapper-input-reniec').addClass('d-none');
                $('#form_save_client #wrapper-input-reniec').addClass('d-none');
                break;
        }
    });

    function load_ubigeo() {
        $.ajax({
            url: "{{ route('admin.load_ubigeo_client') }}",
            method: 'POST',
            data: {
                '_token': "{{ csrf_token() }}"
            },
            success: function(r) {
                let html_department = '<option></option>';
                $.each(r.departments, function(index, department) {
                    html_department +=
                        `<option value="${department.codigo}">${department.descripcion}</option>`;
                });
                $('#form_save_client select[name="departamento"]').html(html_department).select2({
                    placeholder     : "[SELECCIONE]",
                    dropdownParent  : $('#modalAddClient')
                });
            },
            dataType: 'json'
        });
    }
    load_ubigeo();

    $('#form_save_client select[name="departamento"]').on('change', function() {
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

                $('#form_save_client #wrapper_province').removeClass('d-none');
                $('#form_save_client select[name="provincia"]').html(html_province).select2({
                    placeholder     : "[SELECCIONE]",
                    dropdownParent  : $('#modalAddClient')
                });
            },
            dataType: 'json'
        });
    });

    $('#form_save_client select[name="provincia"]').on('change', function() {
        let value = $(this).val(),
            codigo_departamento = $('#form_save_client select[name="departamento"]').val();

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

                $('#form_save_client #wrapper_district').removeClass('d-none');
                $('#form_save_client select[name="distrito"]').html(html_district).select2({
                    placeholder     : "[SELECCIONE]",
                    dropdownParent  : $('#modalAddClient')
                });
            },
            dataType: 'json'
        });
    });

    $('body').on('click', '#form_save_client .btn-search-dniruc', function() {
        event.preventDefault();
        $('#form_save_client #wrapper_province').addClass('d-none');
        $('#form_save_client #wrapper_district').addClass('d-none');
        let type_document = $('#form_save_client select[name="tipo_documento"]').val(),
            dni_ruc = $('#form_save_client input[name="dni_ruc"]').val();

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
                $('#form_save_client .btn-search-dniruc').prop('disabled', true);
                $('#form_save_client .text-search').addClass('d-none');
                $('#form_save_client .text-searching').removeClass('d-none');
            },
            success: function(r) {
                if (!r.status) {
                    $('#form_save_client .btn-search-dniruc').prop('disabled', false);
                    $('#form_save_client .text-search').removeClass('d-none');
                    $('#form_save_client .text-searching').addClass('d-none');
                    toast_msg(r.msg, r.type);
                    return;
                }

                let ubigeo  = r.ubigeo;
                if(ubigeo != "")
                    if(ubigeo != null)
                        active__ubigeo(ubigeo);

                $('#form_save_client .btn-search-dniruc').prop('disabled', false);
                $('#form_save_client .text-search').removeClass('d-none');
                $('#form_save_client .text-searching').addClass('d-none');
                $('#form_save_client input[name="razon_social"]').val(r.nombres);
                $('#form_save_client input[name="direccion"]').val(r.direccion);
            },
            dataType: 'json'
        });
    });

    function active__ubigeo(ubigeo)
    {
        let departamento = ubigeo.substring(0, 2),
            provincia    = ubigeo.substring(0, 4),
            distrito     = ubigeo.substring(0, 6);

        $('#form_save_client select[name="departamento"]').val(departamento).trigger('change');
        setTimeout(() => {
            $('#form_save_client select[name="provincia"]').val(provincia).trigger('change');
        }, 600);
        setTimeout(() => {
            $('#form_save_client select[name="distrito"]').val(distrito).trigger('change');
        }, 900);
    }

    $('body').on('click', '#form_save_client .btn-save-client', function()
    {
        event.preventDefault();
        let form            = $('#form_save_client').serialize(),
            tipo_documento  = $('#form_save_client select[name="tipo_documento"]'),
            dni_ruc         = $('#form_save_client input[name="dni_ruc"]'),
            razon_social    = $('#form_save_client input[name="razon_social"]'),
            direccion       = $('#form_save_client input[name="direccion"]');

        if (tipo_documento.val() == '0')
            tipo_documento.addClass('is-invalid');
        else
            tipo_documento.removeClass('is-invalid');

        if (dni_ruc.val().trim() == '')
            dni_ruc.addClass('is-invalid');
        else
            dni_ruc.removeClass('is-invalid');

        if (razon_social.val().trim() == '')
            razon_social.addClass('is-invalid');
        else
            razon_social.removeClass('is-invalid');

        if (direccion.val().trim() == '')
            direccion.addClass('is-invalid');
        else
            direccion.removeClass('is-invalid');

        if (tipo_documento.val() != '0' && dni_ruc.val().trim() != '' &&
            razon_social.val().trim() != '' && direccion.val().trim() != '') {
            $.ajax({
                url: "{{ route('admin.save_client') }}",
                method: 'POST',
                data: form,
                beforeSend: function() {
                    $('#form_save_client .btn-save-client').prop('disabled', true);
                    $('#form_save_client .text-save-client').addClass('d-none');
                    $('#form_save_client .text-saving-client').removeClass('d-none');
                },
                success: function(r) {
                    if (!r.status) {
                        $('#form_save_client .btn-save-client').prop('disabled', false);
                        $('#form_save_client .text-save-client').removeClass('d-none');
                        $('#form_save_client .text-saving-client').addClass('d-none');
                        toast_msg(r.msg, r.type);
                        return;
                    }

                    $('#form_save_client').trigger('reset');
                    $('#form_save_client #wrapper_province').addClass('d-none');
                    $('#form_save_client #wrapper_district').addClass('d-none');
                    $('#form_save_client .btn-save-client').prop('disabled', false);
                    $('#form_save_client .text-save-client').removeClass('d-none');
                    $('#form_save_client .text-saving-client').addClass('d-none');
                    $('#modalAddClient').modal('hide');
                    $('#modalConfirmSale').css('z-index', '');
                    success_save_client(r.msg, r.type, r.idtipo_comprobante_, r.last_id);
                },
                dataType: 'json'
            });
        }
    });
</script>