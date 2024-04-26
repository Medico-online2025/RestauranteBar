<script>
    $('body').on('click', '.btn-create', function() {
        event.preventDefault();
        $('#modalAddUser').modal('show');
    });

    $('body').on('click', '.btn-save', function() {
        event.preventDefault();
        let form = $('#form_save').serialize(),
            nombres = $('input[name="nombres"]'),
            user = $('input[name="user"]'),
            password = $('input[name="password"]');

        if (nombres.val() == '')
            nombres.addClass('is-invalid');
        else
            nombres.removeClass('is-invalid');

        if (user.val() == '')
            user.addClass('is-invalid');
        else
            user.removeClass('is-invalid');

        if (password.val() == '')
            password.addClass('is-invalid');
        else
            password.removeClass('is-invalid');

        if (nombres.val().trim() != '' && user.val().trim() != '' && password.val().trim() != '') {
            $.ajax({
                url: "{{ route('admin.save_user') }}",
                method: 'POST',
                data: form,
                beforeSend: function() {
                    $('.btn-save').prop('disabled', true);
                    $('.text-saving').removeClass('d-none');
                    $('.text-save').addClass('d-none');
                },
                success: function(r) {
                    if (!r.status) {
                        $('.btn-save').prop('disabled', false);
                        $('.text-saving').addClass('d-none');
                        $('.text-save').removeClass('d-none');
                        toast_msg(r.msg, r.type);
                        return;
                    }

                    $('#modalAddUser').modal('hide');
                    $('#form_save').trigger('reset');
                    $('.btn-save').prop('disabled', false);
                    $('.text-save').removeClass('d-none');
                    $('.text-saving').addClass('d-none');
                    toast_msg(r.msg, r.type);
                    reload_table();
                },
                dataType: 'json'
            });
            return;
        }
    });

    $('body').on('click', '.btn-detail', function() {
        event.preventDefault();
        let id = $(this).data('id');
        $.ajax({
            url: "{{ route('admin.detail_user') }}",
            method: 'POST',
            beforeSend: function() {
                block_content('#layout-content');
            },
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

                close_block('#layout-content');
                $('#form_edit input[name="id"]').val(r.user.id);
                $('#form_edit input[name="nombres"]').val(r.user.nombres);
                $('#form_edit input[name="user"]').val(r.user.user);
                $(`#form_edit select[name="idcaja"] option[value="${r.user.idcaja}"]`).prop(
                    'selected', true);
                $('#modalEditUser').modal('show');
            },
            dataType: 'json'
        });
        return;
    });

    $('body').on('click', '.btn-store', function() {
        event.preventDefault();
        let form = $('#form_edit').serialize();
        $.ajax({
            url: "{{ route('admin.store_user') }}",
            method: 'POST',
            data: form,
            beforeSend: function() {
                $('.btn-store').prop('disabled', true);
                $('.text-storing').removeClass('d-none');
                $('.text-store').addClass('d-none');
            },
            success: function(r) {
                if (!r.status) {
                    $('.btn-store').prop('disabled', false);
                    $('.text-storing').addClass('d-none');
                    $('.text-store').removeClass('d-none');
                    toast_msg(r.msg, r.type);
                    return;
                }

                $('#modalEditUser').modal('hide');
                $('.btn-store').prop('disabled', false);
                $('.text-store').removeClass('d-none');
                $('.text-storing').addClass('d-none');
                toast_msg(r.msg, r.type);
                reload_table();
            },
            dataType: 'json'
        });
    });

    $('body').on('click', '.btn-view-roles', function() {
        event.preventDefault();
        let id = $(this).data('id');
        $.ajax({
            url: "{{ route('admin.view_role') }}",
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

                let html_roles = '';
                close_block('#layout-content');
                $('#modalUpdateRole input[name="id"]').val(r.data.user.id);
                $('#modalUpdateRole input[name="usuario"]').val(r.data.user.nombres);
                $.each(r.data.roles, function(index, role) {
                    html_roles += `<div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox" value="${role.id}" name="roles[]" id="roles_${role.id}"  ${r.data.selRoles.includes(role.id) ? 'checked' : ''}>
                                <label class="form-check-label" for="roles_${role.id}">
                                    ${role.name}
                                </label>
                                </div>`;
                });
                $('#modalUpdateRole #wrapper_roles').html(html_roles);
                $('#modalUpdateRole').modal('show');
            },
            dataType: "json"
        });
    });

    $('body').on('click', '.btn-update-role', function() {
        event.preventDefault();
        let form = $('#form_update_role').serialize();
        $.ajax({
            url: "{{ route('admin.update_role') }}",
            method: "POST",
            data: form,
            beforeSend: function() {
                $('#modalUpdateRole .btn-update-role').prop('disabled', true);
                $('#modalUpdateRole .text-update-role').addClass('d-none');
                $('#modalUpdateRole .text-saving-role').removeClass('d-none');
            },
            success: function(r) {
                if (!r.status) {
                    $('#modalUpdateRole .btn-update-role').prop('disabled', false);
                    $('#modalUpdateRole .text-update-role').removeClass('d-none');
                    $('#modalUpdateRole .text-saving-role').addClass('d-none');
                    toast_msg(r.msg, r.type);
                    return;
                }

                $('#modalUpdateRole .btn-update-role').prop('disabled', false);
                $('#modalUpdateRole .text-update-role').removeClass('d-none');
                $('#modalUpdateRole .text-saving-role').addClass('d-none');
                toast_msg(r.msg.r.type);
            },
            dataType: "json"
        });
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
                    url         : "{{ route('admin.delete_user') }}",
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
</script>
