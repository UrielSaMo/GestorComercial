document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('form'); // Seleccionar el formulario

    form.addEventListener('submit', (e) => {
        e.preventDefault(); // Prevenir el envío tradicional del formulario

        const formData = new FormData(form); // Crear un objeto FormData con los datos del formulario

        fetch('php/procesarActualizacion.php', {
            method: 'POST',
            body: formData,
        })
        .then(response => response.json()) // Convertir la respuesta a JSON
        .then(data => {
            if (data.success) {
                // Mostrar mensaje de éxito
                Swal.fire({
                    title: '¡Éxito!',
                    text: data.message,
                    icon: 'success',
                    confirmButtonText: 'Aceptar',
                }).then(() => {
                    window.location.href = 'estadoInventario.php'; // Redirigir después del éxito
                });
            } else {
                // Mostrar mensaje de error
                Swal.fire({
                    title: 'Error',
                    text: data.message,
                    icon: 'error',
                    confirmButtonText: 'Aceptar',
                });
            }
        })
        .catch(error => {
            // Mostrar mensaje de error por problemas de conexión
            Swal.fire({
                title: 'Error',
                text: 'Ocurrió un problema al enviar la solicitud. Inténtalo de nuevo.',
                icon: 'error',
                confirmButtonText: 'Aceptar',
            });
            console.error('Error:', error); // Registrar el error en la consola
        });
    });
});
