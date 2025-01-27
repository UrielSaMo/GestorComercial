document.getElementById('loginForm').addEventListener('submit', function (event) {
    event.preventDefault();

    const formData = new FormData(this);

    // Log the form data to verify it is being captured correctly
    for (let [key, value] of formData.entries()) {
        console.log(`${key}: ${value}`);
    }

    fetch('php/Auth.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.text(); // Cambiar a text() para verificar el contenido
    })
    .then(text => {
        console.log('Server response:', text); // Agregar un log para ver la respuesta del servidor
        try {
            if (text.trim() === "") {
                throw new Error('Empty response from server');
            }
            const data = JSON.parse(text); // Intentar parsear el texto como JSON
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Éxito',
                    text: data.message,
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    window.location.href = data.redirectUrl;
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message
                });
            }
        } catch (error) {
            console.error('Error parsing JSON:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'La respuesta del servidor no es un JSON válido.'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Ocurrió un error inesperado.'
        });
    });
});