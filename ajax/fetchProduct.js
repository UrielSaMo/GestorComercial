/* document.getElementById('search-btn').addEventListener('click', function () {
     const query = document.getElementById('search-bar').value;

     fetch(`./php/buscarProductos.php?q=${encodeURIComponent(query)}`)
         .then(response => response.json())
         .then(data => {
             if (data.success && data.productos.length > 0) {
                 const producto = data.productos[0]; // Tomar solo el primer resultado para agregar.
                 agregarProductoATabla(producto.Nombre, producto.Precio);
             } else {
                 alert('Producto no encontrado.');
             }
         })
         .catch(error => console.error('Error:', error));
 });

 function agregarProductoATabla(Nombre, precio) {
     const tableBody = document.getElementById('product-table-body');

     // Crear fila de producto
     const row = document.createElement('tr');

     const nombreCell = document.createElement('td');
     nombreCell.textContent = Nombre;
     nombreCell.className = 'producto';  // Asignar clase para nombre del producto

     const precioCell = document.createElement('td');
     precioCell.textContent = `$${precio}`;
     precioCell.className = 'precio';  // Asignar clase para precio
     const cantidadCell = document.createElement('td');
     cantidadCell.innerHTML = `<input type="number" class="form-control text-center cantidad" value="1" min="1" onchange="actualizarTotal(this)">`;
   //  cantidadCell.innerHTML = `<input type="number" class="form-control text-center" value="1" min="1" onchange="actualizarTotal(this)">`;

     const totalCell = document.createElement('td');
     totalCell.textContent = `$${precio}`;
     totalCell.className = 'total';

     const eliminarCell = document.createElement('td');
     eliminarCell.innerHTML = `<button class="btn btn-danger btn-sm" onclick="eliminarProducto(this)">
         <img src="icon/icons8-x-25.png" alt="eliminar">
     </button>`;

     row.appendChild(nombreCell);
     row.appendChild(precioCell);
     row.appendChild(cantidadCell);
     row.appendChild(totalCell);
     row.appendChild(eliminarCell);

     tableBody.appendChild(row);
     actualizarTotales();
 }

 function actualizarTotal(input) {
     const cantidad = parseInt(input.value) || 1;
     const precio = parseFloat(input.closest('tr').children[1].textContent.slice(1));
     const totalCell = input.closest('tr').querySelector('.total');
     totalCell.textContent = `$${(cantidad * precio).toFixed(2)}`;
     actualizarTotales();
 }

 function actualizarTotales() {
     const filas = document.querySelectorAll('#product-table-body tr');
     let subtotal = 0;

     filas.forEach(fila => {
         const total = parseFloat(fila.querySelector('.total').textContent.slice(1));
         subtotal += total;
     });

     const iva = subtotal * 0.16;
     const total = subtotal + iva;

     document.querySelector('.subtotal span').textContent = `$${subtotal.toFixed(2)}`;
     document.querySelector('.iva span').textContent = `$${iva.toFixed(2)}`;
     document.querySelector('.total span').textContent = `$${total.toFixed(2)}`;
 }

 function eliminarProducto(button) {
     button.closest('tr').remove();
     actualizarTotales();
 }*/
     document.getElementById('search-btn').addEventListener('click', async function () {
        const query = document.getElementById('search-bar').value.trim();
    
        try {
            const response = await fetch(`./php/buscarProductos.php?q=${encodeURIComponent(query)}`);
            const data = await response.json();
    
            if (data.success && data.productos.length > 0) {
                data.productos.forEach(producto => agregarProductoATabla(producto.IDProducto, producto.Nombre, producto.Precio));
            } else {
                alert('Producto no encontrado.');
            }
        } catch (error) {
            console.error('Error:', error);
        }
    });
    
    function agregarProductoATabla(idProducto, nombre, precio) {
        const tableBody = document.getElementById('product-table-body');
        const row = document.createElement('tr');
    
        row.innerHTML = `
            <td class="idProducto">${idProducto}</td> <!-- Muestra el ID del producto -->
            <td class="producto">${nombre}</td>
            <td class="precio">$${precio}</td>
            <td>
                <input type="number" class="form-control text-center cantidad" value="1" min="1" onchange="actualizarTotales()">
            </td>
            <td>
                <button class="btn btn-danger btn-sm" onclick="eliminarProducto(this)">
                    <img src="icon/icons8-x-25.png" alt="eliminar">
                </button>
            </td>
        `;
    
        tableBody.appendChild(row);
        actualizarTotales();
    }
    
    function actualizarTotales() {
        const filas = document.querySelectorAll('#product-table-body tr');
        let subtotal = 0;
    
        filas.forEach(fila => {
            const precio = parseFloat(fila.querySelector('.precio').textContent.slice(1));
            const cantidad = parseFloat(fila.querySelector('.cantidad').value) || 1;
            subtotal += precio * cantidad;
        });
    
        const iva = subtotal * 0.16;
    
        document.querySelector('.subtotal span').textContent = `$${subtotal.toFixed(2)}`;
        document.querySelector('.iva span').textContent = `$${iva.toFixed(2)}`;
    }
    
    function eliminarProducto(button) {
        button.closest('tr').remove();
        actualizarTotales();
    }
    