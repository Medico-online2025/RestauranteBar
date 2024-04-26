<script>
    $('body').on('click', '.btn-search', function()
    {
        event.preventDefault();
        let form        = $('#form-contacts-providers').serialize();
        $.ajax({
            url         : "{{ route('admin.search_contacts_providers') }}",
            method      : "POST",
            data        : form,
            beforeSend  : function(){
                $('.btn-search').prop('disabled', true);
                $('.text-search').addClass('d-none');
                $('.text-searching').removeClass('d-none');
            },
            success     : function(r){
                if(!r.status)
                {
                    $('.btn-search').prop('disabled', false);
                    $('.text-search').removeClass('d-none');
                    $('.text-searching').addClass('d-none');
                    toast_msg(r.msg, r.type);
                    return;
                }

                let html_tbody = '';
                $('.btn-search').prop('disabled', false);
                $('.text-search').removeClass('d-none');
                $('.text-searching').addClass('d-none');
                $('.quantity').html(r.quantity);
                $.each(r.providers, function(index, provider){
                    html_tbody += `<tr>
                            <td class="text-center">${provider.documento}</td>
                            <td class="text-center">${provider.dni_ruc}</td>
                            <td class="text-left">${provider.nombres}</td>`;
                            if(provider.correo == null || provider.correo == '')
                                html_tbody += `<td class="text-center">-</td>`;
                            else
                                html_tbody += `<td class="text-center">${provider.correo}</td>`;
                            html_tbody += `<td class="text-center">${provider.direccion}</td>
                            </tr>`;
                });
                $('#wrapper_tbody').html(html_tbody);
                $('#wrapper_tbody').addClass('d-none');
                $('#wrapper_tbody').fadeIn('slow');
                $('#wrapper_tbody').removeClass('d-none');
            },
            dataType    : "json"
        });
    });
</script>