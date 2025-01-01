document.querySelector('#registroForm').addEventListener('submit', function (event) {
    event.preventDefault(); // Evita el envío del formulario estándar

    const formData = new FormData(this);

    fetch('/ServicioSocial/php/registrarse.php', {
        method: 'POST',
        body: formData,
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: data.message,
                    confirmButtonText: 'Ir al inicio de sesión',
                }).then(() => {
                    window.location.href = 'inicioSesion.php';
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message,
                });
            }
        })
        .catch(() => {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudo completar la solicitud. Inténtalo más tarde.',
            });
        });
});
