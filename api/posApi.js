// URL of your PHP API
const apiUrl = "http://localhost/sys/posApi.php";

// Data you want to send
function addItems() {
    // console.log(cart)
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
            // If response is not ok, throw error with details
            if (!response.ok) {
                const errorText = await response.text(); // get error details
                throw new Error(`Error ${response.status}: ${errorText}`);
                // throw new Error(`Error ${response.status}: you are sending an empty fucture`);

            }
            return response.json();
        })
        .then((data) => {

            // location.reload();
            // console.log(data);
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