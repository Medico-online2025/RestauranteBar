<script>
    $('#form-purchases-provider select[name="idtipo_documento"]').select2();
    $('#form-purchases-provider select[name="provider"]').select2();

    $('body').on('click', '.btn-search', function()
    {
        event.preventDefault();
        let form        = $('#form-purchases-provider').serialize();
        $.ajax({
            url         : "{{ route('admin.search_purchases_provider') }}",
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

                let html_tbody = '',
                    total      = 0;
                $('.btn-search').prop('disabled', false);
                $('.text-search').removeClass('d-none');
                $('.text-searching').addClass('d-none');
                $('.quantity').html(r.quantity);
                $.each(r.buys, function(index, buy){
                    total += parseFloat(buy.total);
                    html_tbody += `<tr>
                            <td class="text-center">${moment(buy.fecha_emision).format('DD-MM-yyyy')}</td>
                            <td class="text-center">${buy.serie}-${buy.correlativo}</td>
                            <td class="text-center">${buy.dni_ruc}</td>
                            <td class="text-center">${buy.proveedor}</td>
                            <td class="text-center">${buy.exonerada}</td>
                            <td class="text-center">${buy.gravada}</td>
                            <td class="text-center">${buy.inafecta}</td>
                            <td class="text-center">${buy.igv}</td>
                            <td class="text-center">${buy.total}</td>
                            </tr>`;
                });
                html_tbody += `<tr>
                                <th colspan="8" class="text-end">Total S/ </th>
                                <td class="text-center">${parseFloat(total).toFixed(2)}</td>
                            </tr>`;
                $('#wrapper_tbody').html(html_tbody);
                $('#wrapper_tbody').addClass('d-none');
                $('#wrapper_tbody').fadeIn('slow');
                $('#wrapper_tbody').removeClass('d-none');
            },
            dataType    : "json"
        });
    });
</script>