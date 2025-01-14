document.addEventListener('DOMContentLoaded', () => {
    const productosBody = document.getElementById('productos-body');
    const buscarInput = document.getElementById('buscar-producto');
    const categoriaSelect = document.getElementById('categoria-select');

    let productosCache = []; // Caché de productos para evitar múltiples solicitudes

    // Función para cargar todos los productos
    function cargarProductos() {
        fetch('php/obtenerProductos.php')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    productosCache = data.productos; // Almacenar los productos en caché
                    mostrarProductos(productosCache); // Mostrar todos los productos
                } else {
                    console.error(data.message);
                    productosBody.innerHTML = `<tr><td colspan="7">No se encontraron productos.</td></tr>`;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                productosBody.innerHTML = `<tr><td colspan="7">Error al cargar productos.</td></tr>`;
            });
    }

    // Función para mostrar productos en la tabla
    function mostrarProductos(productos) {
        productosBody.innerHTML = ''; // Limpiar la tabla
        if (productos.length === 0) {
            productosBody.innerHTML = `<tr><td colspan="7">No se encontraron productos.</td></tr>`;
            return;
        }

        productos.forEach(producto => {
            const row = document.createElement('tr');
            const precio = parseFloat(producto.Precio) || 0; // Convertir a número
            row.innerHTML = `
                <td>${producto.IDProducto}</td>
                <td>${producto.Nombre}</td>
                <td>$${precio.toFixed(2)}</td>
                <td>${producto.Categoria}</td>
                <td>${producto.Stock}</td>
                <td>${producto.Estado}</td>
                <td>
                    <button class="editar-btn" data-id="${producto.IDProducto}" 
                            data-nombre="${producto.Nombre}" 
                            data-precio="${producto.Precio}" 
                            data-categoria="${producto.Categoria}" 
                            data-stock="${producto.Stock}" 
                            data-estado="${producto.Estado}">
                        <img src="icon/icons8-pencil-24.png" alt="Editar" class="icono-editar">
                    </button>
                </td>
            `;
            productosBody.appendChild(row);
        });
    }
    document.addEventListener('click', event => {
        if (event.target.closest('.editar-btn')) {
            const button = event.target.closest('.editar-btn');
            const id = button.getAttribute('data-id');
            const nombre = button.getAttribute('data-nombre');
            const precio = button.getAttribute('data-precio');
            const categoria = button.getAttribute('data-categoria');
            const stock = button.getAttribute('data-stock');
            const estado = button.getAttribute('data-estado');
    
            // Redirigir a actualizarProducto.php con parámetros GET
            window.location.href = `actualizarProducto.php?id=${id}&nombre=${encodeURIComponent(nombre)}&precio=${precio}&categoria=${encodeURIComponent(categoria)}&stock=${stock}&estado=${encodeURIComponent(estado)}`;
        }
    });
    

    // Función para filtrar productos por categoría
    function filtrarPorCategoria() {
        const categoriaSeleccionada = categoriaSelect.value;

        if (categoriaSeleccionada === '') {
            // Mostrar todos los productos si no se selecciona ninguna categoría
            mostrarProductos(productosCache);
        } else {
            // Filtrar los productos según la categoría seleccionada
            const productosFiltrados = productosCache.filter(producto =>
                producto.Categoria === categoriaSeleccionada
            );
            mostrarProductos(productosFiltrados);
        }
    }

    // Evento para manejar la búsqueda
    buscarInput.addEventListener('input', () => {
        const query = buscarInput.value.trim().toLowerCase();

        if (query === '') {
            // Si el campo está vacío, mostrar todos los productos
            mostrarProductos(productosCache);
        } else {
            // Filtrar los productos según el nombre
            const productosFiltrados = productosCache.filter(producto =>
                producto.Nombre.toLowerCase().includes(query)
            );
            mostrarProductos(productosFiltrados);
        }
    });

    // Evento para manejar el cambio de categoría
    categoriaSelect.addEventListener('change', filtrarPorCategoria);

    // Cargar productos al cargar la página
    cargarProductos();
});
