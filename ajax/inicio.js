document.querySelector('#loginForm').addEventListener('submit', function (event) {
    event.preventDefault(); // Evita el envío tradicional del formulario

    const formData = new FormData(this);

    fetch('/ServicioSocial/GestorComercial/php/InicioSesion.php', {
        method: 'POST',
        body: formData,
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('Error de red o servidor.');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: data.message,
                    confirmButtonText: 'Continuar',
                }).then(() => {
                    window.location.href = data.redirectUrl; // Redirige a la URL correspondiente
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message,
                });
            }
        })
        .catch(error => {
            console.error('Error en el fetch:', error.message); // Muestra solo el mensaje del error
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: error.message || 'No se pudo completar la solicitud. Inténtalo más tarde.',
            });
        });
});
