document.addEventListener('DOMContentLoaded', () => {
 // Función genérica para manejar el envío de formularios
 function handleFormSubmit(formSelector, url) {
    const form = document.querySelector(formSelector);
    if (form) { // Verifica que el formulario existe
        form.addEventListener('submit', function (event) {
            event.preventDefault(); // Evita el envío tradicional del formulario

            const formData = new FormData(this);

            fetch(url, {
                method: 'POST',
                body: formData,
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.text();
            })
            .then(text => {
                console.log('Server response:', text); // Log para depuración
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
                            timer: 1500,
                        }).then(() => {
                            window.location.href = data.redirectUrl;
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message,
                        });
                    }
                } catch (error) {
                    console.error('Error parsing JSON:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'La respuesta del servidor no es un JSON válido.',
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Ocurrió un error inesperado.',
                });
            });
        });
    }
}


    handleFormSubmit('#loginForm', './php/Auth.php'); 
    handleFormSubmit('#agregarVendedor', './php/agregarVendedor.php');
    handleFormSubmit('#actualizarVendedor', './php/actualizarVendedor.php');
    handleFormSubmit('#agregarProducto', './php/agregarProducto.php');
    handleFormSubmit('#actualizarProducto', './php/procesarActualizacion.php');

});
