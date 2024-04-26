<script>
    $('body').on('click', '.btn-search', function()
    {
        event.preventDefault();
        let form        = $('#form-salles-products').serialize();
        $.ajax({
            url         : "{{ route('admin.search_sales_product') }}",
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
                $.each(r.products, function(index, product){
                    html_tbody += `<tr>
                            <td class="text-center">${(product.codigo == null) ? '-' : product.codigo}</td>
                            <td class="text-left">${product.producto}</td>
                            <td class="text-center">${parseInt(product.cantidad)}</td>
                            <td class="text-center">${product.precio_total}</td>
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