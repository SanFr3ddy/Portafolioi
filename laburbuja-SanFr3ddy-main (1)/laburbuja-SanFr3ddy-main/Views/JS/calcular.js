document.getElementById('agregar-servicio-carrito').addEventListener('click', function() {
    const kilos = parseFloat(document.getElementById('kilos').value);
    const servicioId = document.getElementById('servicio-id').value;
    const servicioPrecio = parseFloat(document.getElementById('servicio-precio').value);

    // Calcular el total basado en los kilos
    const total = servicioPrecio * kilos;

    // Crear un objeto para el servicio
    const servicio = {
        id: servicioId,
        name: 'Servicio Completo',
        quantity: kilos,
        price: servicioPrecio,
        total: total // Total por el servicio basado en los kilos
    };

    // Agregar el servicio al carrito
    addItemToCart(servicio);

    // Cerrar el modal
    $('#Servicio').modal('hide');
    updateTotal(); // Actualiza el total en el carrito

    // Mantener el estado del checkbox de "Pendiente"
    const isPendingCheckbox = document.getElementById('isPending');
    if (isPendingCheckbox.checked) {
        // Puedes agregar lógica aquí si necesitas manejar el estado del checkbox
    }
});