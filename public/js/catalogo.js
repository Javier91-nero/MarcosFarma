document.addEventListener('DOMContentLoaded', () => {
    const buscarInput = document.getElementById('buscarInput');
    const filtroOferta = document.getElementById('filtroOferta');
    const filtroPrecio = document.getElementById('filtroPrecio');

    buscarInput.addEventListener('input', filtrarProductos);
    filtroOferta.addEventListener('change', filtrarProductos);
    filtroPrecio.addEventListener('change', filtrarProductos);
});

function agregarAlCarrito(productId) {
    console.log(`Producto con ID ${productId} agregado al carrito`);
    // Aquí puedes agregar la lógica real para agregar al carrito (ajax, etc.)
}

function filtrarProductos() {
    const busqueda = document.getElementById('buscarInput').value.toLowerCase();
    const filtroOferta = document.getElementById('filtroOferta').value;
    const filtroPrecio = document.getElementById('filtroPrecio').value;

    document.querySelectorAll('.producto-item').forEach(producto => {
        const nombre = producto.dataset.nombre;
        const oferta = producto.dataset.oferta === '1';
        const precio = parseFloat(producto.dataset.precio);

        const coincideBusqueda = nombre.includes(busqueda);
        const coincideOferta = (filtroOferta === 'todos') ||
                               (filtroOferta === 'oferta' && oferta) ||
                               (filtroOferta === 'sinOferta' && !oferta);

        let coincidePrecio = true;
        switch (filtroPrecio) {
            case 'menor50':
                coincidePrecio = precio < 50;
                break;
            case 'entre50y100':
                coincidePrecio = precio >= 50 && precio <= 100;
                break;
            case 'mayor100':
                coincidePrecio = precio > 100;
                break;
        }

        producto.style.display = (coincideBusqueda && coincideOferta && coincidePrecio) ? '' : 'none';
    });
}