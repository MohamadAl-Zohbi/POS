// URL of your PHP API
const apiUrl = "http://localhost/POS/sys/posApi.php";

// Data you want to send
function addItems() {
    const payload = {
        "items": cart,
        "total": total
    };
    fetch(apiUrl, {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(payload)
        })
        .then(async(response) => {
            // If response is not ok, throw error with details
            if (!response.ok) {
                const errorText = await response.text(); // get error details
                throw new Error(`Error ${response.status}: ${errorText}`);
            }
            return response.json();
        })
        .then((data) => {
            console.log(data.details);
            if (data.details == "done") {
                cart = [];
                updateCart();
                alert('success');
                document.getElementById("barcode").focus();

                let index = 0;
                document.querySelectorAll('.table').forEach((tb) => {
                    index++;
                    if (tb.classList.value.includes('active')) {
                        localStorage.removeItem("window" + index);
                    }
                });

            }
        })
        .catch((error) => {
            console.error("❌ Request failed:", error.message);
        });
}