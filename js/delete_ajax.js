$(document).on('click', '.btn-delete-cookie', function(e) {
    e.preventDefault();
    var cookieName = $(this).data('name');
    var mensaje_ok = "<div class='alert alert-success'>Cookie borrada correctamente</div>";
    var mensaje_ko = "<div class='alert alert-danger'>Error al borrar la cookie</div>";
    $.ajax({
        url: 'funciones/delete_cookies.php',
        type: 'POST',
        data: { eliminar_cookie: cookieName },
        success: function(response) {
            location.reload(); // Recargar la página para actualizar la lista de cookies
            $("#msj_borrado").html(mensaje_ok);
        },
        error: function() {
            alert('Error al eliminar la cookie');
            $("#msj_borrado").html(mensaje_ok);
        }
    });
});