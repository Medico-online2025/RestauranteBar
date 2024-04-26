function reload_table() {
    $('#table').DataTable().ajax.reload();
}

function touch_down(input = '', form = '') {
    if (input == '') {
        toast_msg('Establezca un campo de entrada', 'warning');
        return;
    }
    $('body').on('click', `.bootstrap-touchspin-down-${form}`, function (event) {
        event.preventDefault();
        let contador = 1,
            cantidad_actual = parseInt($(input).val());

        if (cantidad_actual <= 1) {
            toast_msg('La cantidad no puede ser menor a 1', 'warning');
            return;
        }

        $(input).val(cantidad_actual - contador);
    });
}

function touch_up(input = '', form = '') {
    if (input == '') {
        toast_msg('Establezca un campo de entrada', 'warning');
        return;
    }
    $('body').on('click', `.bootstrap-touchspin-up-${form}`, function (event) {
        event.preventDefault();
        let contador = 1,
            cantidad_actual = parseInt($(input).val());
        $(input).val(cantidad_actual + contador);
    });
}

function toast_msg(msg, type_msg) {
    var noty;

    setTimeout(() => {
        noty = new PNotify({
            text: msg,
            type: type_msg,
            addclass: 'ui-pnotify-no-icon stack-topright always',
            icon: false,
            buttons: {
                closer: false,
                sticker: false
            }
        });
    }, 100);

    setTimeout(() => {
        noty.remove();
    }, 2000);
}

function block_content(elemento)
{
    $(elemento).block({
        message: `<div class="sk-grid sk-primary mx-auto">
                    <div class="sk-grid-cube"></div>
                    <div class="sk-grid-cube"></div>
                    <div class="sk-grid-cube"></div>
                    <div class="sk-grid-cube"></div>
                    <div class="sk-grid-cube"></div>
                    <div class="sk-grid-cube"></div>
                    <div class="sk-grid-cube"></div>
                    <div class="sk-grid-cube"></div>
                    <div class="sk-grid-cube"></div>
                </div>`,
        css: {
            backgroundColor: "transparent",
            color: "#fff",
            border: "0"
        }
    })
}

function close_block(elemento)
{
    $(elemento).unblock();
}

$('body').on('click', '.btn-close-modal-client', function(event)
{
    event.preventDefault();
    $('#modalConfirmSale').css('z-index', '');
    $('#modalAddClient').modal('hide');
});

// Disabled click
/* document.oncontextmenu = function (e) 
{
    e.preventDefault();
    return false;
} */