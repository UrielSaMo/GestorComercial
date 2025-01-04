document.querySelector('#registroForm').addEventListener('submit', function (event) {
    event.preventDefault(); // Evita el envío del formulario estándar

    const formData = new FormData(this);

    fetch('/ServicioSocial/GestorComercial/php/RegistroTienda.php', {
        method: 'POST',
        body: formData,
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'info',
                    title: 'Nota Importante',
                    text: 'Por favor, asegúrate de conservar la Clave Única proporcionada en el siguiente mensaje. Será necesaria para futuras referencias.',
                    confirmButtonText: 'Entendido',
                }).then(() => {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        html: data.message,  // Usa 'html' en lugar de 'text' para mostrar formato HTML
                        confirmButtonText: 'Registrar Administrador',
                    }).then(() => {
                        window.location.href = 'registro.php';
                    });
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