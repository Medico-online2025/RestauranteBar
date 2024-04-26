<script>
    $('#form-purchases-expenses select[name="user"]').select2();
    $('body').on('click', '.btn-search', function()
    {
        event.preventDefault();
        let form        = $('#form-purchases-expenses').serialize();
        $.ajax({
            url         : "{{ route('admin.search_purchases_expenses') }}",
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
                $.each(r.expenses, function(index, expense){
                    total += parseFloat(expense.monto);
                    html_tbody += `<tr>
                            <td class="text-center">${expense.usuario}</td>
                            <td class="text-center">${moment( expense.fecha_emision).format('DD-MM-yyyy')}</td>
                            <td class="text-left">${expense.gasto}</td>
                            <td class="text-center">${expense.detalle}</td>
                            <td class="text-center">${expense.monto}</td>
                            </tr>`;
                });
                html_tbody += `<tr>
                                <th colspan="4" class="text-end">Total S/ </th>
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