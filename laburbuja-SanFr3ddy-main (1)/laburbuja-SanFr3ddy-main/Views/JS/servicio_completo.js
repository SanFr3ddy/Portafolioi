document.getElementById('agregar-servicio-carrito').addEventListener('click', function() {
    const kilos = parseFloat(document.getElementById('kilos').value);
    const servicioId = document.getElementById('servicio-id').value;
    const servicioPrecio = parseFloat(document.getElementById('servicio-precio').value);

    // Crear un objeto para el servicio
    const servicio = {
        id: servicioId,
        name: 'Servicio Completo',
        quantity: kilos,
        price: servicioPrecio, // Aquí puedes calcular el total según los kilos si es necesario
        total: servicioPrecio * kilos // Total por el servicio basado en los kilos
    };

    // Agregar el servicio al carrito
    addItemToCart(servicio);

    // Cerrar el modal
    $('#Servicio').modal('hide');
    function updateTotal() {
    const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    document.getElementById('totalAmount').innerText = `Total: $${total.toFixed(2)}`; // Mostrar total en el carrito
}
});
function updateTotal() {
    const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    document.getElementById('totalAmount').innerText = `Total: $${total.toFixed(2)}`; // Mostrar total en el carrito
}