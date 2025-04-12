document.addEventListener('DOMContentLoaded', function () {
    const carrito = document.getElementById('cart-items'); // Contenedor del carrito
    const totalGeneral = document.getElementById('total-general'); // Total general del carrito
    const processPaymentButton = document.getElementById('processPaymentButton'); // Botón para procesar la orden
    const confirmarProcesarOrden = document.getElementById('confirmarProcesarOrden'); // Botón para confirmar el procesamiento
    const estadoPago = document.getElementById('estadoPago'); // Select para el estado de pago
    const metodoPago = document.getElementById('metodoPago'); // Select para el método de pago

    let carritoItems = []; // Array para almacenar los productos del carrito

    // Función para agregar productos al carrito
    function agregarAlCarrito(servicioId, nombre, cantidad, precio) {
        const total = cantidad * precio;

        // Verificar si el producto ya está en el carrito
        const productoExistente = carritoItems.find(item => item.id === servicioId);
        if (productoExistente) {
            productoExistente.cantidad += cantidad;
            productoExistente.subtotal += total;
        } else {
            carritoItems.push({
                id: servicioId,
                nombre: nombre,
                cantidad: cantidad,
                precio: precio,
                subtotal: total
            });
        }

        actualizarCarrito();
    }

    // Función para actualizar el carrito visualmente
    function actualizarCarrito() {
        carrito.innerHTML = ''; // Limpiar el contenido del carrito

        carritoItems.forEach(item => {
            const div = document.createElement('div');
            div.classList.add('cart-item', 'd-flex', 'justify-content-between', 'align-items-center', 'mb-2');
            div.innerHTML = `
                <span>${item.nombre} (${item.cantidad})</span>
                <span>$${item.subtotal.toFixed(2)}</span>
                <button class="btn btn-danger btn-sm eliminar-item">Eliminar</button>
            `;

            // Agregar evento para eliminar el producto del carrito
            div.querySelector('.eliminar-item').addEventListener('click', function () {
                carritoItems = carritoItems.filter(i => i.id !== item.id);
                actualizarCarrito();
            });

            carrito.appendChild(div);
        });

        // Actualizar el total general
        const total = carritoItems.reduce((sum, item) => sum + item.subtotal, 0);
        totalGeneral.textContent = total.toFixed(2);
    }

    // Procesar la orden
    processPaymentButton.addEventListener('click', function () {
        if (carritoItems.length === 0) {
            alert('El carrito está vacío. Agrega productos antes de procesar la orden.');
            return;
        }

        const modal = new bootstrap.Modal(document.getElementById('modalProcesarOrden'));
        modal.show();
    });

    confirmarProcesarOrden.addEventListener('click', function () {
        const customerId = document.getElementById('customerId').value;
        const estadoPago = document.getElementById('estadoPago').value;
        const metodoPago = document.getElementById('metodoPago').value;

        if (!customerId) {
            alert('Selecciona un cliente antes de procesar la orden.');
            return;
        }

        if (!estadoPago || !metodoPago) {
            alert('Por favor, selecciona el estado de pago y el método de pago.');
            return;
        }

        console.log('Contenido del carrito antes de enviar:', carritoItems);

        const payload = {
            customerId: customerId,
            estado: estadoPago,
            metodo_pago: metodoPago,
            total: parseFloat(totalGeneral.textContent),
            items: carritoItems
        };

        console.log('Datos enviados al servidor:', payload);

        // Enviar los datos al servidor
        fetch('process_sale.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(payload)
        })
            .then(response => response.json())
            .then(data => {
                console.log('Respuesta del servidor:', data);
                if (data.success) {
                    alert('Orden procesada correctamente.');
                    carritoItems = []; // Vaciar el carrito
                    actualizarCarrito(); // Actualizar visualmente
                    const modal = bootstrap.Modal.getInstance(document.getElementById('modalProcesarOrden'));
                    modal.hide(); // Cerrar el modal
                    location.reload(); // Recargar la página
                } else {
                    alert('Error al procesar la orden: ' + data.error);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al procesar la solicitud.');
            });
    });
});