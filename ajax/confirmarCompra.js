document.addEventListener('DOMContentLoaded', () => {
    function actualizarTotales() {
        const filas = document.querySelectorAll('#product-table-body tr');
        let subtotal = 0;
        const productos = [];

        filas.forEach(fila => {
            const idProducto = fila.querySelector('.idProducto').textContent;
            const nombre = fila.querySelector('.producto').textContent;
            const precio = parseFloat(fila.querySelector('.precio').textContent.slice(1));
            const cantidad = parseInt(fila.querySelector('.cantidad').value) || 1;
            subtotal += precio * cantidad;

            productos.push({ IDProducto: idProducto, Nombre: nombre, Precio: precio, Cantidad: cantidad });
        });

        const iva = subtotal * 0.16;
        const total = subtotal + iva;

        document.querySelector('.subtotal span').textContent = `$${subtotal.toFixed(2)}`;
        document.querySelector('.iva span').textContent = `$${iva.toFixed(2)}`;
        document.querySelector('.total span').textContent = `$${total.toFixed(2)}`;

        document.getElementById('productosInput').value = JSON.stringify(productos);
        document.getElementById('subtotal').value = subtotal.toFixed(2);
        document.getElementById('total').value = total.toFixed(2);
    }

    document.querySelector('#product-table-body').addEventListener('input', actualizarTotales);

    document.getElementById('confirmarCompraBtn').addEventListener('click', (e) => {
        e.preventDefault(); // Evitar que el formulario se envíe de forma tradicional

        const productos = JSON.parse(document.getElementById('productosInput').value || '[]');
        const subtotal = document.getElementById('subtotal').value;
        const total = document.getElementById('total').value;
        const idUsuario = document.getElementById('idUsuario').value;
        const idTienda = document.getElementById('idTienda').value;

        if (productos.length === 0) {
            Swal.fire('Error', 'El carrito está vacío. Agrega productos antes de confirmar la compra.', 'warning');
            return;
        }

        const invalidCantidad = productos.some(producto => producto.Cantidad <= 0);
        if (invalidCantidad) {
            Swal.fire('Error', 'Asegúrate de que todas las cantidades sean mayores a 0.', 'warning');
            return;
        }

        if (!idUsuario || !idTienda || !total) {
            Swal.fire('Error', 'Faltan datos obligatorios. Verifica tu información.', 'warning');
            return;
        }

        const formData = new FormData();
        formData.append('productos', JSON.stringify(productos));
        formData.append('idUsuario', idUsuario);
        formData.append('idTienda', idTienda);
        formData.append('subtotal', subtotal);
        formData.append('total', total);

        fetch('/ServicioSocial/GestorComercial/php/compra.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire('¡Compra realizada!', data.message, 'success').then(() => {
                    location.reload();
                });
            } else {
                Swal.fire('Error', data.message, 'error');
            }
        })
        .catch(error => {
            Swal.fire('Error de conexión', `No se pudo realizar la compra: ${error.message}`, 'error');
        });
    });

    actualizarTotales();
});