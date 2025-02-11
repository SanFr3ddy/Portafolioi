document.getElementById('processPaymentButton').addEventListener('click', function() {
    // Obtener los productos del carrito
    const cartItems = []; // Aquí debes obtener los elementos del carrito
    const total = document.getElementById('total-general').innerText; // Total del carrito
    const customerId = document.getElementById('customerId').value; // ID del cliente

    // Recopilar los datos de los productos en el carrito
    document.querySelectorAll('#cart-items .cart-item').forEach(item => {
        const productId = item.getAttribute('data-product-id'); // Asegúrate de tener un atributo data-product-id
        const quantity = item.querySelector('.quantity').value; // Asegúrate de tener un campo para la cantidad
        const subtotal = item.querySelector('.subtotal').innerText; // Asegúrate de tener un campo para el subtotal
        cartItems.push({ productId, quantity, subtotal });
    });

    // Crear el objeto de datos a enviar
    const data = {
        customerId: customerId,
        total: total,
        items: cartItems
    };

    // Enviar los datos al servidor
    fetch('ruta/a/tu/endpoint/para/procesar/pago.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Pago procesado con éxito');
            // Aquí puedes redirigir o limpiar el carrito
        } else {
            alert('Error al procesar el pago: ' + data.error);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al procesar el pago');
    });
});