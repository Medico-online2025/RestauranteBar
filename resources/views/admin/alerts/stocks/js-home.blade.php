<script>
    function load_tbody()
    {
        $.ajax({
            url         : "{{ route('admin.load_tbody_stocks') }}",
            method      : "POST",
            data        : {
                '_token': "{{ csrf_token() }}"
            },
            success     : function(r){
                if(!r.status)
                {
                    toast_msg(r.msg, r.type);
                    return;
                }

                let tbody = '';
                $.each(r.products, function(item, product){
                    tbody += `<tr>
                        <td class="text-center">${item + 1}</td>
                        <td class="text-center">${product.codigo_interno}</td>
                        <td>${product.descripcion}</td>
                        <td class="text-center">${product.stock}</td>
                        <td class="text-center"><a href="{{ route('admin.create_buy') }}" class="dt-button create-new btn btn-sm btn-danger waves-effect waves-light">
                        <span><i class="fa fa-shopping-cart"></i> <span class="d-none d-sm-inline-block">Comprar</span></span>
                    </a></td>
                    </tr>`;
                });

                $('#wrapper_tbody').html(tbody);
            },
            dataType    : "json"
        });
    }

    load_tbody();
</script>