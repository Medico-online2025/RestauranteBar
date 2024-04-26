<script>
    function load_ubigeo()
    {
        $.ajax({
            url         : "{{ route('admin.load_ubigeo') }}",
            method      : 'POST',
            data        : {
                '_token'    : "{{ csrf_token() }}"
            },
            success     : function(r){
                let ubigeo          = r.ubigeo,
                    html_department = '',
                    html_province   = '',
                    html_district   = '';

                if(ubigeo != null)
                {
                    $('#wrapper_district').removeClass('d-none');
                    $('#wrapper_province').removeClass('d-none');

                    $.each(r.departments, function(index, department){
                        if(department.codigo == r.department.codigo)
                        html_department += `<option value="${department.codigo}" selected>${department.descripcion}</option>`;
                        else
                        html_department += `<option value="${department.codigo}">${department.descripcion}</option>`;
                    });

                    $.each(r.provinces, function(index, province){
                        if(province.codigo == r.province.codigo)
                        html_province += `<option value="${province.codigo}" selected>${province.descripcion}</option>`;
                        else
                        html_province += `<option value="${province.codigo}">${province.descripcion}</option>`;
                    });

                    $.each(r.districts, function(index, district){
                        if(district.codigo == r.district.codigo)
                        html_district += `<option value="${district.codigo}" selected>${district.descripcion}</option>`;
                        else
                        html_district += `<option value="${district.codigo}">${district.descripcion}</option>`;
                    });

                    $('select[name="provincia"]').html(html_province);
                    $('select[name="distrito"]').html(html_district);
                }

                else
                {
                    html_department += `<option></option>`;
                    $.each(r.departments, function(index, department){
                        html_department += `<option value="${department.codigo}">${department.descripcion}</option>`;
                    });

                    $('#wrapper_province').addClass('d-none');
                    $('#wrapper_district').addClass('d-none');
                }

                $('select[name="departamento"]').html(html_department).select2({
                    placeholder     : "[SELECCIONE]"
                });
            },
            dataType    : 'json'
        });
    }

    load_ubigeo();

    $('select[name="departamento"]').on('change', function()
    {
        let value = $(this).val();
        $.ajax({
            url         : "{{ route('admin.load_provinces') }}",
            method      : 'POST',
            data        : {
                '_token': "{{ csrf_token() }}",
                codigo  : value
            },
            success     : function(r)
            {
                let html_province = '<option></option>';
                $.each(r.provinces, function(index, province){
                    html_province += `<option value="${province.codigo}">${province.descripcion}</option>`;
                });

                $('#wrapper_province').removeClass('d-none');
                $('select[name="provincia"]').html(html_province).select2({
                    placeholder     : "[SELECCIONE]"
                });
            },
            dataType    : 'json'
        });
    });

    $('select[name="provincia"]').on('change', function()
    {
        let value               = $(this).val(),
            codigo_departamento = $('select[name="departamento"]').val();

        $.ajax({
            url         : "{{ route('admin.load_districts') }}",
            method      : 'POST',
            data        : {
                '_token': "{{ csrf_token() }}",
                codigo              : value,
                codigo_departamento : codigo_departamento
            },
            success     : function(r)
            {
                let html_district = '<option></option>';
                $.each(r.districts, function(index, district){
                    html_district += `<option value="${district.codigo}">${district.descripcion}</option>`;
                });

                $('#wrapper_district').removeClass('d-none');
                $('select[name="distrito"]').html(html_district).select2({
                    placeholder     : "[SELECCIONE]"
                });
            },
            dataType    : 'json'
        });
    });
    
    $(".select2_department").select2();
    $(".select2_province").select2({
        placeholder     : "[SELECCIONE]"
    });
    $(".select2_district").select2({
        placeholder     : "[SELECCIONE]"
    });

    // Save info business
    $('body').on('click', '.btn-save-info', function()
    {
        event.preventDefault();
        let form = $('#form-info').serialize();
        $.ajax({
            url         : "{{ route('admin.save_info_business') }}",
            method      : 'POST',
            data        : form,
            beforeSend  : function(){
                $('.btn-save-info').prop('disabled', true);
                $('.text-save-info').addClass('d-none');
                $('.text-saving-info').removeClass('d-none');
            },
            success     : function(r){
                if(!r.status)
                {
                    $('.btn-save-info').prop('disabled', false);
                    $('.text-save-info').removeClass('d-none');
                    $('.text-saving-info').addClass('d-none');
                    toast_msg(r.msg, r.type);
                    return;
                }

                $('.btn-save-info').prop('disabled', false);
                $('.text-save-info').removeClass('d-none');
                $('.text-saving-info').addClass('d-none');
                toast_msg(r.msg, r.type);
            },
            dataType    : 'json'
        });
    });

    $('body').on('click', '.btn-save-user', function()
    {
        event.preventDefault();
        let form = new FormData($('#form_info_user')[0]);
        $.ajax({
            url         : "{{ route('admin.save_info_user') }}",
            method      : 'POST',
            data        : form,
            cache       : false,
            processData : false,
            contentType : false,
            beforeSend  : function(){
                $('.btn-save-user').prop('disabled', true);
                $('.text-save-user').addClass('d-none');
                $('.text-saving-user').removeClass('d-none');
            },
            success     : function(r){
                if(!r.status)
                {
                    $('.btn-save-user').prop('disabled', false);
                    $('.text-save-user').removeClass('d-none');
                    $('.text-saving-user').addClass('d-none');
                    toast_msg(r.msg, r.type);
                    return;
                }

                $('.btn-save-user').prop('disabled', false);
                $('.text-save-user').removeClass('d-none');
                $('.text-saving-user').addClass('d-none');
                toast_msg(r.msg, r.type);
            },
            dataType    : 'json'
        });
    });

    $('body').on('click', '.btn-gen-json', function()
    {
        event.preventDefault();
        $.ajax({
            url         : "{{ route('admin.gen_json') }}",
            method      : "POST",
            data        : {
                '_token'    : "{{ csrf_token() }}"
            },
            beforeSend  : function(){
                $('.btn-gen-json').prop('disabled', true);
                $('.text-gen').addClass('d-none');
                $('.text-load-gen').removeClass('d-none');
            },
            success     : function(r){
                if(!r.status)
                {
                    $('.btn-gen-json').prop('disabled', false);
                    $('.text-gen').removeClass('d-none');
                    $('.text-load-gen').addClass('d-none');
                    toast_msg(r.msg, r.type);
                    return;
                }

                $('.btn-gen-json').prop('disabled', false);
                $('.text-gen').removeClass('d-none');
                $('.text-load-gen').addClass('d-none');
                toast_msg(r.msg, r.type);
            },
            dataType    : 'json'
        });
        return;
    });
</script>