
document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.querySelector('.sidebar');
    const menuBtn = document.querySelector('.menu_btn');

    // Expande o contrae el menú al hacer clic en el botón
    menuBtn.addEventListener('click', (event) => {
        event.stopPropagation(); // Evita que el clic en el botón cierre el menú
        sidebar.classList.toggle('active');
    });

    // Contrae la barra lateral si se hace clic fuera de ella
    document.addEventListener('click', (event) => {
        if (sidebar.classList.contains('active') && !sidebar.contains(event.target)) {
            sidebar.classList.remove('active');
        }
    });
});