<script>
    $('body').on('click', '.window-open-pos', function(e)
    {
        e.preventDefault();
        let iduser      = $(this).data('iduser'),
            idcash      = $(this).data('idcash');
        $.ajax({
            url         : "{{ route('admin.check_cash_active') }}",
            method      : "POST",
            data        : {
                '_token': "{{ csrf_token() }}",
                iduser  : iduser,
                idcash  : idcash
            },
            success     : function(r){
                if(!r.status){
                    console.log('Hacaskda');
                    toast_msg(r.msg, r.type);
                    return;
                }

                var h    = window.innerHeight;
                var w    = window.innerWidth;
                var href = "{{ route('admin.pos') }}";
                window.open(href,'targetWindow','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes, width='+w+',height='+h);
            },
            dataType    : "json"
        });
        
    });

    function load_alerts()
    {
        $.ajax({
            url         : "{{ route('admin.load_alerts') }}",
            method      : "POST",
            data        : {
                '_token': "{{ csrf_token() }}"
            },
            success     : function(r){
                if(!r.status){
                    toast_msg(r.msg, r.type);
                    return;
                }

                if(parseInt(r.quantity) < 1)
                {
                    $('#wrapper_f').addClass('d-none');
                    $('#wrapper_b').addClass('d-none');
                    $('#wrapper_s').addClass('d-none');
                    $('#wrapper_e').addClass('d-none');
                    $('#wrapper_empty').removeClass('d-none');
                    $('#wrapper_badge_noti').addClass('d-none').text('');
                    return;
                }
                
                $('#wrapper_empty').addClass('d-none');
                $('#wrapper_badge_noti').removeClass('d-none').text(r.quantity);
                if(r.facturas < 1){
                    $('#wrapper_f').addClass('d-none');
                } else {
                    $('#wrapper_f').removeClass('d-none');
                    $('#wrapper_f h6').text(r.facturas + ' FACTURAS');
                }

                if(r.boletas < 1){
                    $('#wrapper_b').addClass('d-none');
                } else {
                    $('#wrapper_b').removeClass('d-none');
                    $('#wrapper_b h6').text(r.boletas + ' BOLETAS');
                }

                if(r.stock < 1){
                    $('#wrapper_s').addClass('d-none');
                } else {
                    $('#wrapper_s').removeClass('d-none');
                    $('#wrapper_s h6').text(r.stock + ' PRODUCTOS');
                }

                if(r.expirations < 1){
                    $('#wrapper_e').addClass('d-none');
                } else {
                    $('#wrapper_e').removeClass('d-none');
                    $('#wrapper_e h6').text(r.expirations + ' PRODUCTOS');
                }
            },
            dataType    : "json"
        });
    }
    load_alerts();
</script>