document.addEventListener("DOMContentLoaded", () => {
    const equipmentBody = document.getElementById("equipmentBody");
    const requestList = document.getElementById("requestList");
    const basket = {}; // Tracks items added to rental list

    // Fetch equipment data from backend
    fetch("php/getEquipment.php")
        .then(res => res.json())
        .then(data => {
            equipmentBody.innerHTML = ""; // Clear table
            data.forEach(item => {
                const row = document.createElement("tr");

                const available = item.available;
                const buttonId = `addBtn-${item.id}`;

                row.innerHTML = `
                    <td>${item.name}</td>
                    <td>${item.brand}</td>
                    <td>${available ? "Available" : "Not Available"}</td>
                    <td>${item.description}</td>
                    <td>
                        <button class="addBtn" id="${buttonId}" data-id="${item.id}" ${available ? "" : "disabled"}>
                            ${available ? "Add to Basket" : "Not Available"}
                        </button>
                    </td>
                `;

                equipmentBody.appendChild(row);

                // Add to basket handler
                if (available) {
                    const addBtn = row.querySelector(".addBtn");
                    addBtn.addEventListener("click", () => {
                        if (!basket[item.id]) {
                            basket[item.id] = item;
                            updateRentalList();

                            addBtn.textContent = "Already in Basket";
                            addBtn.disabled = true;
                        }
                    });
                }
            });
        })
        .catch(err => {
            console.error(err);
            equipmentBody.innerHTML = `<tr><td colspan="5">Error loading equipment list.</td></tr>`;
        });

    function updateRentalList() {
        requestList.innerHTML = "";

        Object.values(basket).forEach(item => {
            const li = document.createElement("li");

            li.innerHTML = `
                ${item.name} (${item.brand}) ...
                for 
                <select name="rental_days[${item.id}]">
                    ${[...Array(10)].map((_, i) => `<option value="${i + 1}">${i + 1}</option>`).join("")}
                </select>
                days
                <input type="hidden" name="item_ids[]" value="${item.id}">
                <button class="removeBtn" data-id="${item.id}" style="margin-left:10px;">❌ Remove</button>
            `;

            requestList.appendChild(li);

            // Handle Remove button
            li.querySelector(".removeBtn").addEventListener("click", () => {
                delete basket[item.id];
                updateRentalList();

                const originalBtn = document.getElementById(`addBtn-${item.id}`);
                if (originalBtn) {
                    originalBtn.textContent = "Add to Basket";
                    originalBtn.disabled = false;
                }
            });
        });
    }

    // Submit request
    document.getElementById("submitRequestBtn").addEventListener("click", () => {
        if (Object.keys(basket).length === 0) {
            alert("Your rental list is empty.");
            return;
        }

        const formData = new FormData();
        const selects = requestList.querySelectorAll("select");
        const itemInputs = requestList.querySelectorAll("input[type='hidden']");

        selects.forEach((select, i) => {
            const itemId = itemInputs[i].value;
            const days = select.value;
            formData.append("item_ids[]", itemId);
            formData.append(`rental_days[${itemId}]`, days);
        });

        fetch("php/submitRentalRequest.php", {
            method: "POST",
            body: formData
        })
        .then(res => res.text())
        .then(response => {
            alert("Rental request submitted!");
            location.reload(); // Reset the page
        })
        .catch(err => {
            alert("An error occurred while submitting your request.");
            console.error(err);
        });
    });
	
	// Live search filter
	document.getElementById("searchBar").addEventListener("input", function () {
    const filter = this.value.toLowerCase();
    const rows = document.querySelectorAll("#equipmentBody tr");

    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(filter) ? "" : "none";
    });
});

});



