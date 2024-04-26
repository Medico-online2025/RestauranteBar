<script>
    $('body').on('click', '.btn-search', function()
    {
        event.preventDefault();
        let form        = $('#form-inventory-products').serialize();
        $.ajax({
            url         : "{{ route('admin.search_inventory_products') }}",
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
                    html_tbody += `<tr>`;
                        if(product.codigo_interno == null)
                            html_tbody += `<td class="text-center">-</td>`;
                        else
                            html_tbody += `<td class="text-center">${product.codigo_interno}</td>`;
                        if(product.marca == '')
                        html_tbody += `<td>${product.descripcion}</td>`;
                        else
                        html_tbody += `<td>${product.descripcion} - ${product.marca}</td>`;
                        html_tbody += `<td class="text-center">${product.unidad}</td>
                            <td class="text-center">${product.marca}</td>
                            <td class="text-center">${product.presentacion}</td>
                            <td class="text-center">${product.precio_compra}</td>
                            <td class="text-center">${product.precio_venta}</td>`;
                            if(product.stock != null && product.stock <= 5)
                            html_tbody += `<td class="text-center text-danger font-weight-bold">${product.stock}</td>`;
                            else if(product.stock == null)
                            html_tbody += `<td class="text-center">-</td>`;
                            else
                            html_tbody += `<td class="text-center">${product.stock}</td>
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