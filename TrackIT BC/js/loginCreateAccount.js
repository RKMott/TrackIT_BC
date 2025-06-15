// JavaScript Document

const signInBtn = document.getElementById("signIn");
const createAccountBtn = document.getElementById("createAccount");
const authForm = document.getElementById("authForm");
const extraFields = document.getElementById("extraFields");
const submitBtn = authForm.querySelector(".submit");

signInBtn.addEventListener("click", () => {
    extraFields.innerHTML = "";
    submitBtn.textContent = "Log In";
    document.getElementById("formMode").value = "login";
});

createAccountBtn.addEventListener("click", () => {
    extraFields.innerHTML = `
        <label for="firstName">First Name</label><br>
        <input type="text" name="first_name" id="firstName" required><br>

        <label for="lastName">Last Name</label><br>
        <input type="text" name="last_name" id="lastName" required><br>

        <label for="confirmPassword">Confirm Password</label><br>
        <input type="password" name="confirm_password" id="confirmPassword" required><br>
    `;
    submitBtn.textContent = "Create Account";
    document.getElementById("formMode").value = "register";
});


authForm.addEventListener("submit", function (e) {
	e.preventDefault(); // Stop normal form submission

	const mode = document.getElementById("formMode").value;
	const formData = new FormData(authForm);

	if (mode === "register") {
		const password = document.getElementById("password").value;
		const confirmPasswordField = document.getElementById("confirmPassword");
		const confirmPassword = confirmPasswordField ? confirmPasswordField.value : "";

		if (password !== confirmPassword) {
			alert("Passwords do not match.");
			return;
		}

		// Register the user via fetch
		fetch(authForm.action, {
			method: "POST",
			body: formData
		})
		.then(response => response.text())
		.then(data => {
			if (data.includes("Account created successfully")) {
				alert("Account created successfully!");
				location.reload(); // go back to login state
			} else {
				alert("Registration Error:\n" + data);
			}
		})
		.catch(error => {
			alert("An error occurred:\n" + error);
		});
	}

	else if (mode === "login") {
		// Login the user via fetch
		fetch(authForm.action, {
			method: "POST",
			body: formData
		})
		.then(response => response.text())
		.then(data => {
			if (data.trim() === "success") {
				window.location.href = "mainMenu.php";
			} else {
				alert("Login failed:\n" + data);
			}
		})
		.catch(error => {
			alert("An error occurred:\n" + error);
		});
	}
});


window.addEventListener("DOMContentLoaded", () => {
	extraFields.innerHTML = ""; // remove create account fields
	submitBtn.textContent = "Log In"; // set button label
});






