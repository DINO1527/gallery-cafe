document.addEventListener('DOMContentLoaded', function() {
    const filterMenuItems = document.querySelectorAll('.filters_menu li');
    const searchButton = document.querySelector('.search-btn');
    const searchBox = document.querySelector('.search-box');

    filterMenuItems.forEach(item => {
        item.addEventListener('click', function() {
            // Remove active class from all items
            filterMenuItems.forEach(li => li.classList.remove('active'));
            this.classList.add('active');

            // Get filter value
            const filterValue = this.getAttribute('data-filter');
            
            // Create AJAX request to fetch category food items
            const xhr = new XMLHttpRequest();
            xhr.open('POST', '/gallarycafe/php/menu/food_menu.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

            xhr.onreadystatechange = function() {
                if (this.readyState === 4 && this.status === 200) {
                    // Inject the HTML response into the '.filters-content' div
                    const filtersContent = document.querySelector('.filters-content');
                    if (filtersContent) {
                        filtersContent.innerHTML = this.responseText;
                        // Optionally, reinitialize any JavaScript plugins if needed
                    } else {
                        console.error('Filters content container not found.');
                    }
                }
            };
            
            // Send the selected category in the POST request
            xhr.send('category=' + encodeURIComponent(filterValue.substring(1))); // Remove the leading dot (.)
            
            // Optional: Clear search box when a category is selected
            searchBox.value = '';
        });
    });

    // Search Button Event Listener
    searchButton.addEventListener('click', function() {
        const query = searchBox.value.trim();

        // Optional: Remove active class from category filters
        filterMenuItems.forEach(li => li.classList.remove('active'));
        // Optionally, set "All" as active if you have an "All" filter
        const allFilter = document.querySelector('.filters_menu li[data-filter="*"]');
        if (allFilter) {
            allFilter.classList.add('active');
        }

        // Create AJAX request to fetch search results
        const xhr = new XMLHttpRequest();
        xhr.open('POST', '/gallarycafe/php/menu/food_menu.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

        xhr.onreadystatechange = function() {
            if (this.readyState === 4 && this.status === 200) {
                // Inject the HTML response into the '.filters-content' div
                const filtersContent = document.querySelector('.filters-content');
                if (filtersContent) {
                    filtersContent.innerHTML = this.responseText;
                    // Optionally, reinitialize any JavaScript plugins if needed
                } else {
                    console.error('Filters content container not found.');
                }
            }
        };

        // Send the search query in the POST request
        xhr.send('search=' + encodeURIComponent(query));
    });

    let cart = [];

    // Function to render the cart items in the popup
    function renderCart() {
        const cartItemsContainer = document.getElementById('cartItems');
        cartItemsContainer.innerHTML = '';

        if (cart.length === 0) {
            cartItemsContainer.innerHTML = '<p>Your cart is empty.</p>';
            document.getElementById('cartTotal').innerText = '0.00';
            return;
        }

        let total = 0;

        cart.forEach(item => {
            const itemTotal = item.price * item.quantity;
            total += itemTotal;

            const itemDiv = document.createElement('div');
            itemDiv.classList.add('cart-item');
            itemDiv.innerHTML = `
                <div class="item-name">${item.name}</div>
                <div class="item-quantity">
                   <button class="quantity-btn minus" data-id="${item.id}">-</button>
                   <input type="number" class="inputbox" min="1" value="${item.quantity}" data-id="${item.id}">
                   <button class="quantity-btn plus" data-id="${item.id}">+</button>
                </div>
                <div class="item-total">$${itemTotal.toFixed(2)}</div>
                <button class="remove-item" data-id="${item.id}">Remove</button>
            `;
            cartItemsContainer.appendChild(itemDiv);
        });

        document.getElementById('cartTotal').innerText = total.toFixed(2);

        // Add event listeners for quantity buttons
        document.querySelectorAll('.quantity-btn').forEach(button => {
            button.addEventListener('click', function() {
                const input = this.closest('.item-quantity').querySelector('input');
                let currentValue = parseInt(input.value);
                const itemId = this.getAttribute('data-id');
                const item = cart.find(item => item.id == itemId);

                if (this.classList.contains('plus')) {
                    currentValue++;
                } else if (this.classList.contains('minus')) {
                    currentValue = currentValue > 1 ? currentValue - 1 : currentValue;
                }

                input.value = currentValue;
                item.quantity = currentValue;
                renderCart(); // Recalculate and re-render cart
            });
        });

        // Add event listeners for remove buttons
        document.querySelectorAll('.remove-item').forEach(button => {
            button.addEventListener('click', function() {
                const itemId = this.getAttribute('data-id');
                cart = cart.filter(item => item.id !== itemId);
                renderCart();
            });
        });
    }

    // Event delegation for "Add to Cart" buttons
    document.addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('add-to-cart')) {
            const foodId = e.target.getAttribute('data-id');
            const foodName = e.target.getAttribute('data-name');
            const price = parseFloat(e.target.getAttribute('data-price'));

            const existingItem = cart.find(item => item.id === foodId);
            if (existingItem) {
                existingItem.quantity += 1;
            } else {
                cart.push({
                    id: foodId,
                    name: foodName,
                    price: price,
                    quantity: 1
                });
            }

            Swal.fire({
                icon: 'success',
                title: 'Added to Cart',
                text: `${foodName} has been added to your cart.`,
                timer: 1500,
                showConfirmButton: false
            });

            renderCart(); // Re-render cart after adding item
        }
    });

    

    // Handle "View Cart" button click
    document.getElementById("popupBtn").addEventListener("click", function() {
        renderCart();
        document.getElementById("popupPage").style.display = "block";
    });

    // Handle closing the cart popup
    document.getElementById("closeBtn").addEventListener("click", function() {
        document.getElementById("popupPage").style.display = "none";
    });
    
    
    document.querySelectorAll('.quantity-btn').forEach(button => {
        button.addEventListener('click', function() {
            const input = this.closest('.item-quantity').querySelector('input');
            let currentValue = parseInt(input.value);
            
            if (this.classList.contains('plus')) {
                input.value = currentValue + 1;
            } else if (this.classList.contains('minus')) {
                if (currentValue > 1) {
                    input.value = currentValue - 1;
                }
            }
    
            // Update the item total and cart total logic here
        });
    });
    
    
    // Handle changes in quantity within the cart
    document.getElementById('cartItems').addEventListener('input', function(e) {
        if (e.target && e.target.matches('input[type="number"]')) {
            const itemId = e.target.getAttribute('data-id');
            const newQuantity = parseInt(e.target.value);
            if (newQuantity > 0) {
                const item = cart.find(item => item.id === itemId);
                if (item) {
                    item.quantity = newQuantity;
                    renderCart();
                }
            } else {
                // If quantity is less than 1, remove the item
                cart = cart.filter(item => item.id !== itemId);
                renderCart();
            }
        }
    });

    // Handle removing items from the cart
    document.getElementById('cartItems').addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('remove-item')) {
            const itemId = e.target.getAttribute('data-id');
            cart = cart.filter(item => item.id !== itemId);
            renderCart();
        }
    });

    // Handle "Confirm Order" button click
    document.getElementById('confirmCart').addEventListener('click', function() {
        if (cart.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Cart is Empty',
                text: 'Please add items to your cart before confirming the order.'
            });
            return;
        }
        document.getElementById("popupPage").style.display = "none";
        document.getElementById("confirmationPopup").style.display = "block";
    });

    // Handle closing the confirmation popup
    document.getElementById("closeConfirmBtn").addEventListener("click", function() {
        document.getElementById("confirmationPopup").style.display = "none";
    });

    // Handle "Take Away" option
    document.getElementById('takeAway').addEventListener('click', function() {
        // Prepare the order data
        const orderData = {
            cart: cart,
            order_type: 'takeaway',
            total_amount: calculateTotal()
        };

        // Send the order data via AJAX
        fetch('/gallarycafe/php/book_table/process_order.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(orderData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Order Placed',
                    text: 'Your Take Away order has been placed successfully!',
                    confirmButtonText: 'OK'
                }).then(() => {
                    // Clear the cart
                    cart = [];
                    renderCart();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message,
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'An unexpected error occurred.',
            });
        });

        // Close the confirmation popup
        document.getElementById("confirmationPopup").style.display = "none";
    });

    // Handle "Book a Table" option
    document.getElementById('bookTable').addEventListener('click', function() {
        // Show confirmation dialog
        Swal.fire({
            title: 'Confirm Table Booking',
            text: 'Do you want to confirm your table booking?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'No'
        }).then((result) => {
            if (result.isConfirmed) {
                if (cart.length > 0) {
                    // Prepare order data
                    const orderData = {
                        cart: cart,
                        order_type: 'table_booked',
                        total_amount: calculateTotal()
                    };

                    // Send AJAX request to process_order.php
                    fetch('/gallarycafe/php/book_table/process_order.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(orderData)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Order Confirmed',
                                text: 'Your table booking and order have been confirmed.',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                // Clear the cart
                                cart = [];
                                renderCart();
                                // Redirect to book_table.php
                                window.location.href = '/gallarycafe/php/book_table/table_form.php';
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: data.message,
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'An unexpected error occurred.',
                        });
                    });
                } else {
                    // No cart items, redirect to book_table.php to book table only
                    window.location.href = '/gallarycafe/php/book_table/table_form.php';
                }
            }
        });

        // Close the confirmation popup if it's open
        const confirmationPopup = document.getElementById("confirmationPopup");
        if (confirmationPopup) {
            confirmationPopup.style.display = "none";
        }
    });

    // Function to calculate total amount
    function calculateTotal() {
        return cart.reduce((acc, item) => acc + (item.price * item.quantity), 0).toFixed(2);
    }

    // Close popups if user clicks outside the popup content
    window.addEventListener("click", function(event) {
        const popupPage = document.getElementById("popupPage");
        const confirmationPopup = document.getElementById("confirmationPopup");
        if (event.target === popupPage) {
            popupPage.style.display = "none";
        }
        if (event.target === confirmationPopup) {
            confirmationPopup.style.display = "none";
        }
    });
});
