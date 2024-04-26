<script>
    function load_tables() {
        $.ajax({
            url: "{{ route('admin.load_tables_order') }}",
            method: "POST",
            data: {
                '_token': "{{ csrf_token() }}"
            },
            success: function(r) {
                if (!r.status) {
                    toast_msg(r.msg, r.type);
                    return;
                }
                $('#wrapper_tables').html(r.html);
            },
            dataType: "json"
        });
    }

    function load_navs()
    {
        $('.nav-link').each(function(){
            var currElem = $(this);
            if(currElem.data('id') == 1)
            {
                currElem.addClass('active');
            }
            else {
                currElem.removeClass('active');
            }
        });  
    }
    
    $('document').ready(function(){
        load_navs();
        load_tables();

        Echo.channel('new-order').listen('NewOrderEvent', (e) => {
            load_navs();
            load_tables();
        });

        Echo.channel('reload-table').listen('UpdateOrderEvent', (e) => {
            load_navs();
            load_tables();
        });
    });
</script>
