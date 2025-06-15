document.addEventListener("DOMContentLoaded", () => {
    const quantityInputs = document.querySelectorAll(".quantity-input");

    quantityInputs.forEach((input) => {
        input.addEventListener("change", (event) => {
            const itemId = event.target.dataset.itemId;
            const newQuantity = parseInt(event.target.value);

            if (isNaN(newQuantity) || newQuantity < 1) {
                event.target.value = 1;
            }

            updateCartItem(itemId, newQuantity);
        });
    });

    async function updateCartItem(itemid, quantity) {
        try {
            const response = await fetch("/cart/update-quantity", {
                method: "POST",
                body: JSON.stringify({ item_id: itemid, quantity: quantity }),
            });
            console.log(response);
        } catch (error) {
            console.log("Error with request", error);
            alert("Error, try later.");
        }
    }
});
