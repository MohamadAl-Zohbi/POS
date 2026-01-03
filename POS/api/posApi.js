const apiUrl = "http://localhost/POS/sys/posApi.php";

function addItems() {
    const payload = {
        "items": cart,
        "total": total,
        "customerId": customerId
    };
    fetch(apiUrl, {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(payload)
        })
        .then(async(response) => {
            if (!response.ok) {
                const errorText = await response.text();
                throw new Error(`Error ${response.status}: ${errorText}`);
            }
            return response.json();
        })
        .then((data) => {
            if (data.details == "done") {
                cart = {};
                customerId = "";
                document.getElementById("result").innerText = "";

                updateCart();
                document.getElementById("barcode").focus();
                let index = 0;
                document.querySelectorAll('.table').forEach((tb) => {
                    index++;
                    if (tb.classList.value.includes('active')) {
                        localStorage.removeItem("window" + index);
                    }
                });
                return;
            }
            alert("empty facture")
        })
        .catch((error) => {
            console.error("❌ Request failed:", error.message);
        });
}