document.addEventListener('DOMContentLoaded', function () {
    const searchCustomer = document.getElementById('searchCustomer');
    const customerResults = document.getElementById('customerResults');
    const customerForm = document.getElementById('customerForm');
    const customerId = document.getElementById('customerId');
    const customerName = document.getElementById('customerName');
    const customerPhone = document.getElementById('customerPhone');
    const customerAddress = document.getElementById('customerAddress');

    searchCustomer.addEventListener('input', function () {
        if (this.value.length > 2) {
            fetch(`search_customer.php?term=${this.value}`)
                .then(response => response.json())
                .then(data => {
                    customerResults.innerHTML = data.map(customer => `
                        <a href="#" class="list-group-item list-group-item-action" onclick="selectCustomer(${customer.id_cliente}, '${customer.nombre}', '${customer.telefono || ''}', '${customer.direccion || ''}')">
                            ${customer.nombre}
                        </a>
                    `).join('');
                    document.addEventListener('DOMContentLoaded', function () {
                        const searchCustomer = document.getElementById('searchCustomer');
                        const customerResults = document.getElementById('customerResults');
                        const customerForm = document.getElementById('customerForm');
                        const customerId = document.getElementById('customerId');
                        const customerName = document.getElementById('customerName');
                        const customerPhone = document.getElementById('customerPhone');
                        const customerAddress = document.getElementById('customerAddress');
                    
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
                    
                        // Aquí puedes agregar el código para procesar la venta
                        document.getElementById('processButton').addEventListener('click', function () {
                            // Lógica para procesar la venta
                        });
                    });
                });
        } else {
            customerResults.innerHTML = '';
        }
    });

    customerForm.addEventListener('submit', function (e) {
        e.preventDefault();
        
        // Deshabilitar el botón para evitar múltiples envíos
        const submitButton = this.querySelector('button[type="submit"]');
        submitButton.disabled = true;

        const formData = new FormData(this);
        fetch('save_customer.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            alert(data.success ? 'Cliente guardado con éxito' : 'Error al guardar el cliente: ' + data.error);
            if (data.success) {
                customerId.value = data.customerId;
                resetForm();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Ocurrió un error al procesar la solicitud.');
        })
        .finally(() => {
            // Volver a habilitar el botón al finalizar
            submitButton.disabled = false;
        });
    });

    window.selectCustomer = function(id, name, phone, address) {
        customerId.value = id;
        customerName.value = name;
        customerPhone.value = phone;
        customerAddress.value = address;
        customerResults.innerHTML = '';
    };

    function resetForm() {
        customerId.value = '';
        customerName.value = '';
        customerPhone.value = '';
        customerAddress.value = '';
        customerResults.innerHTML = '';
    }
});