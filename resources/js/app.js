document.addEventListener("DOMContentLoaded", () => {
    const quantityInputs = document.querySelectorAll(".quantity-input");
    const subtotalSpan = document.getElementById("subtotal");
    const gstSpan = document.getElementById("gst");
    const qstSpan = document.getElementById("qst");
    const totalSpan = document.getElementById("total");

    const csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");

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

    async function updateCartItem(itemId, quantity) {
        try {
            const response = await fetch("/cart/update-quantity", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": csrfToken,
                    "Content-Type": "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                },
                body: JSON.stringify({ item_id: itemId, quantity: quantity }),
            });

            if (!response.ok) {
                const errorData = await response.json();
                console.error("Server error:", errorData);

                let errorMessage = "Error during cart update.";
                if (errorData.message) {
                    errorMessage = errorData.message;
                } else if (errorData.errors) {
                    errorMessage = Object.values(errorData.errors)
                        .flat()
                        .join("\n");
                }
                alert(errorMessage);
                return;
            }

            const data = await response.json();

            if (data.success) {
                subtotalSpan.textContent = data.totals.subtotal;
                gstSpan.textContent = data.totals.gst;
                qst.textContent = data.totals.qst;
                total.textContent = data.totals.total;
            }
        } catch (error) {
            console.log("Error with request", error);
            alert("Error, try later.");
        }
    }
});
