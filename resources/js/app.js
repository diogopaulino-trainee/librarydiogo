import './bootstrap';

document.addEventListener("DOMContentLoaded", function () {
    const cartSidebar = document.getElementById("cartSidebar");
    const closeCart = document.getElementById("closeCart");
    const cartItemsContainer = document.getElementById("cartItems");
    const cartItemCount = document.getElementById("cartItemCount");

    if (!cartSidebar || !cartItemsContainer) {
        return;
    }

    function loadCartItems() {
        fetch("/cart/items")
            .then(response => response.json())
            .then(data => {
                cartItemsContainer.innerHTML = "";
                let total = 0;
    
                if (data.cartItems.length === 0) {
                    cartItemsContainer.innerHTML = `<p class="text-gray-500 text-center">Your cart is empty.</p>`;
                } else {
                    data.cartItems.forEach(item => {
                        let itemPrice = parseFloat(item.price.replace(",", "."));

                        total += itemPrice;
    
                        cartItemsContainer.innerHTML += `
                            <div class="flex py-2 border-b items-center justify-between">
                                <div class="flex-1 flex items-start gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" 
                                        class="h-6 w-6 text-blue-600 flex-shrink-0" 
                                        viewBox="0 0 24 24" 
                                        fill="none" 
                                        stroke="currentColor" 
                                        stroke-width="2" 
                                        stroke-linecap="round" 
                                        stroke-linejoin="round">
                                        <path d="M12 7v14"/>
                                        <path d="M16 12h2"/>
                                        <path d="M16 8h2"/>
                                        <path d="M3 18a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h5a4 4 0 0 1 4 4 4 4 0 0 1 4-4h5a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1h-6a3 3 0 0 0-3 3 3 3 0 0 0-3-3z"/>
                                        <path d="M6 12h2"/>
                                        <path d="M6 8h2"/>
                                    </svg>
                                    <div class="leading-tight">
                                        <p class="font-semibold text-gray-800">
                                            ${item.title}
                                        </p>
                                    </div>
                                </div>
    
                                <span class="font-bold text-gray-800 whitespace-nowrap">
                                    €${item.price}
                                </span>
                            </div>
                        `;
                    });
                }
    
                document.getElementById("cartTotal").textContent = `€${total.toFixed(2)}`;
    
            })
            .catch(error => console.error("Error loading cart items:", error));
    }

    function toggleCartSidebar() {
        const isClosed = cartSidebar.classList.contains("translate-x-full");

        if (isClosed) {
            cartSidebar.classList.remove("translate-x-full");
            loadCartItems();
        } else {
            cartSidebar.classList.add("translate-x-full");
        }
    }

    document.querySelectorAll("#cartIconBtn, #openCart").forEach(button => {
        button.addEventListener("click", toggleCartSidebar);
    });

    if (closeCart) {
        closeCart.addEventListener("click", function () {
            cartSidebar.classList.add("translate-x-full");
        });
    }
});
