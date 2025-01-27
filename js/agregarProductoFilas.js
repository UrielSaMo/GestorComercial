document.addEventListener('DOMContentLoaded', () => {
    const productosBody = document.getElementById('productos-body');

    // Escuchamos los clics en los botones "Eliminar"
    productosBody.addEventListener('click', (event) => {
        if (event.target.classList.contains('eliminar-btn')) {
            // Encontramos la fila que contiene el botón "Eliminar"
            const row = event.target.closest('tr');

            // Permitir eliminar la primera fila solo si hay más de una fila
            if (productosBody.childElementCount > 1 || row !== productosBody.firstElementChild) {
                row.remove();
            } 
        }
    });

    // Manejo de agregar nueva fila
    productosBody.addEventListener('input', (event) => {
        const allRows = productosBody.querySelectorAll('tr');
        const lastRow = allRows[allRows.length - 1];

        if (event.target.closest('tr') === lastRow) {
            if (event.target.tagName === 'INPUT' || event.target.tagName === 'SELECT') {
                const inputs = lastRow.querySelectorAll('input');
                let allFilled = true;

                inputs.forEach(input => {
                    if (!input.value) {
                        allFilled = false;
                    }
                });

                if (allFilled) {
                    addNewRow();
                } else {
                    let anyFilled = Array.from(inputs).some(input => input.value.trim() !== '');
                    if (anyFilled) {
                        inputs.forEach(input => {
                            input.setAttribute('required', 'required');
                        });
                    } else {
                        inputs.forEach(input => {
                            input.removeAttribute('required');
                        });
                    }
                }
            }
        }
    });

    function addNewRow() {
        const allRows = productosBody.querySelectorAll('tr');

        // Aseguramos que todas las filas previas tengan el "required"
        allRows.forEach(row => {
            row.querySelectorAll('input').forEach(input => {
                input.setAttribute('required', 'required');
            });
        });

        // Clonamos la última fila (que es la fila vacía)
        const newRow = document.querySelector('.producto-row').cloneNode(true);

        // Limpiamos los valores de la nueva fila
        newRow.querySelectorAll('input').forEach(input => {
            input.value = '';
            input.removeAttribute('required');
        });

        // Reseteamos los selectores
        newRow.querySelectorAll('select').forEach(select => {
            select.selectedIndex = 0;
        });

        // Cambiar el texto del botón "Eliminar"
        const eliminarBtn = newRow.querySelector('.eliminar-btn');
        eliminarBtn.textContent = 'Eliminar';
        
        // Añadimos la nueva fila al final
        productosBody.appendChild(newRow);
    }
});
