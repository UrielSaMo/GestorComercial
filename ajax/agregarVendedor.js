document.querySelector('#agregarVendedor').addEventListener('submit', function (event) {
    event.preventDefault(); // Evita el envío tradicional del formulario

    const formData = new FormData(this);

    fetch('/ServicioSocial/GestorComercial/php/agregarVendedor.php', {
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
            console.error('Error en el fetch:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudo completar la solicitud. Inténtalo más tarde.',
            });
        });
});
