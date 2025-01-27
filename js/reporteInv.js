document.addEventListener("DOMContentLoaded", () => {
    // Botón para generar reporte de inventario
    const reporteInventarioBtn = document.getElementById("reporte-btn");
    reporteInventarioBtn.addEventListener("click", () => {
        generarReporte("inventario");
    });

    // Botón para generar reporte de ventas
    const reporteVentasBtn = document.getElementById("reporte_venta_btn");
    reporteVentasBtn.addEventListener("click", () => {
        generarReporte("ventas");
    });

    // Función para generar reporte
    function generarReporte(tipo) {
        fetch(`./php/generarReporte.php?tipo=${tipo}`, {
            method: "GET",
        })
            .then((response) => {
                if (!response.ok) {
                    throw new Error("Error al generar el reporte.");
                }
                return response.blob();
            })
            .then((blob) => {
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement("a");
                a.style.display = "none";
                a.href = url;
                a.download = `Reporte_${tipo}.pdf`;
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
            })
            .catch((error) => {
                console.error("Error:", error);
                alert("Hubo un error al generar el reporte.");
            });
    }
});
