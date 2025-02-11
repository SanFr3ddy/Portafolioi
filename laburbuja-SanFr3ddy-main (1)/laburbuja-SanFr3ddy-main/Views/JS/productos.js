// Variables globales
let cart = [];
let listaProductos; // Declarar listaProductos globalmente

document.addEventListener('DOMContentLoaded', function () {
    // Elementos del DOM
    const searchCustomer = document.getElementById('searchCustomer');
    const customerResults = document.getElementById('customerResults');
    const customerForm = document.getElementById('customerForm');
    const customerId = document.getElementById('customerId');
    const customerName = document.getElementById('customerName');
    const customerPhone = document.getElementById('customerPhone');
    const customerAddress = document.getElementById('customerAddress');

    // Funcionalidad de búsqueda de clientes
    searchCustomer.addEventListener('input', function () {
        if (this.value.length > 2) {
            fetch('search_customer.php?term=' + this.value)
                .then(response => response.json())
                .then(data => {
                    customerResults.innerHTML = '';
                    data.forEach(customer => {
                        const item = document.createElement('a');
                        item.href = '#';
                        item.className = 'list-group-item list-group-item-action';
                        item.textContent = customer.nombre;
                        item.onclick = function (e) {
                            e.preventDefault();
                            customerId.value = customer.id_cliente;
                            customerName.value = customer.nombre;
                            customerPhone.value = customer.telefono || '';
                            customerAddress.value = customer.direccion || '';
                            customerResults.innerHTML = '';
                        };
                        customerResults.appendChild(item);
                    });
                });
        } else {
            customerResults.innerHTML = '';
        }
    });

    customerForm.addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        fetch('save_customer.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Cliente guardado con éxito');
                    customerId.value = data.customerId;
                } else {
                    alert('Error al guardar el cliente: ' + data.error);
                }
            });
    });

    // Funcionalidad de búsqueda de productos
    const buscarProductosInput = document.getElementById('buscar-productos');
    listaProductos = document.getElementById('lista-productos'); // Asignar el elemento
    let timeoutId;

    buscarProductosInput.addEventListener('input', function () {
        clearTimeout(timeoutId);
        const query = this.value;
        timeoutId = setTimeout(() => buscarProductos(query), 300);
    });

    // Función para buscar productos
    function buscarProductos(query) {
        if (query.length < 2) {
            listaProductos.innerHTML = '';
            return;
        }

        listaProductos.innerHTML = '<div class="list-group-item">Buscando...</div>';

        fetch(`buscar_productos.php?q=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(data => {
                listaProductos.innerHTML = '';
                if (data.success && data.data.length > 0) {
                    data.data.forEach(producto => {
                        const item = document.createElement('a');
                        item.href = '#';
                        item.className = 'list-group-item list-group-item-action';
                        item.textContent = `${producto.nombre} - $${producto.precio}`;
                        item.onclick = function (e) {
                            e.preventDefault();
                            seleccionarProducto(producto);
                        };
                        listaProductos.appendChild(item);
                    });
                } else {
                    listaProductos.innerHTML = '<div class="list-group-item">No se encontraron productos</div>';
                }
            })
            .catch(error => {
                console.error('Error en la búsqueda:', error);
                listaProductos.innerHTML = '<div class="list-group-item text-danger">Error al buscar productos</div>';
            });
    }

    // Función para seleccionar un producto
    function seleccionarProducto(producto) {
        document.getElementById('producto-id').value = producto.id;
        document.getElementById('nombre-producto').value = producto.nombre;
        document.getElementById('precio-producto').value = producto.precio;
        document.getElementById('cantidad-producto').value = 1; 
        actualizarTotal(); 
        listaProductos.innerHTML = ''; 
    }

    // Función para actualizar el total en el modal
    function actualizarTotal() {
        const precio = parseFloat(document.getElementById('precio-producto').value) || 0;
        const cantidad = parseInt(document.getElementById('cantidad-producto').value) || 0;
        document.getElementById('total-producto').textContent = (precio * cantidad).toFixed(2);
    }

    // Agregar producto al carrito
    document.getElementById('agregar-producto-carrito').addEventListener('click', function () {
        const id = document.getElementById('producto-id').value;
        const nombre = document.getElementById('nombre-producto').value;
        const precio = parseFloat(document.getElementById('precio-producto').value);
        const cantidad = parseInt(document.getElementById('cantidad-producto').value);

        if (!nombre || isNaN(precio) || isNaN(cantidad) || cantidad <= 0) {
            alert('Por favor, seleccione un producto y especifique una cantidad válida.');
            return;
        }

        const item = {
            id: id,
            name: nombre,
            price: precio,
            quantity: cantidad,
            total: precio * cantidad
        };

        addItemToCart(item);

        // Cerrar el modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('Plastico'));
        modal.hide();

        // Limpiar los campos del modal
        document.getElementById('producto-id').value = '';
        document.getElementById('nombre-producto').value = '';
        document.getElementById('precio-producto').value = '';
        document.getElementById('cantidad-producto').value = '1';
        document.getElementById('total-producto').textContent = '0.00';
    });

    // Funciones para manejar el carrito
    function addItemToCart(item) {
        cart.push(item);
        updateCartDisplay();
    }
    function updateCartDisplay() {
        const cartItems = document.getElementById('cart-items');
        cartItems.innerHTML = '';
    
        cart.forEach(servicio => {
            const itemDiv = document.createElement('div');
            itemDiv.classList.add('cart-item');
            itemDiv.innerHTML = `
                <p>${servicio.name} - Kilos: ${servicio.quantity} - Total: $${(servicio.price * servicio.quantity).toFixed(2)}</p>
                <button class="btn btn-warning btn-sm" onclick="editItem(${servicio.id})">Editar</button>
                <button class="btn btn-danger btn-sm" onclick="removeItem(${servicio.id})">Eliminar</button>
            `;
            cartItems.appendChild(itemDiv);
        });
    
        const totalGeneral = cart.reduce((acc, item) => acc + (item.price * item.quantity), 0);
        document.getElementById('total-general').innerText = totalGeneral.toFixed(2);
    }

function removeItem(id) {
        cart = cart.filter(item => item.id !== id); // Filtrar el carrito para eliminar el item
        updateCartDisplay(); // Actualizar la visualización del carrito
    }

    // Event listener para procesar el pago
    document.getElementById('processPaymentButton').addEventListener('click', function() {
        const customerId = document.getElementById('customerId').value;
        const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);

        if (!customerId) {
            alert('Por favor, seleccione o agregue un cliente.');
            return;
        }

        if (cart.length === 0) {
            alert('El carrito está vacío. Agregue productos antes de pagar.');
            return;
        }

        fetch('process_sale.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                customerId: customerId,
                total: total,
                cartItems: cart
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Venta registrada con éxito!');
                cart = []; // Limpiar el carrito después de la venta
                updateCartDisplay(); // Actualizar la visualización del carrito
                document.getElementById('customerName').value = ''; // Limpiar el nombre del cliente
            } else {
                alert('Error al registrar la venta: ' + data.error);
            }
        })
        .catch(error => {
            console.error('Error en la solicitud:', error);
            alert('Error al procesar la venta: ' + error.message);
        });
    });
});