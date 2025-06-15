// JavaScript Document

window.addEventListener("DOMContentLoaded", () => {
    const changeBtn = document.getElementById("changePasswordBtn");
    const deleteBtn = document.getElementById("deleteAccountBtn");
    const changeSection = document.getElementById("changePass");
    const deleteSection = document.getElementById("deleteConfirm");

    changeBtn.addEventListener("click", () => {
        changeSection.classList.toggle("hidden");
    });

    deleteBtn.addEventListener("click", () => {
        deleteSection.classList.toggle("hidden");
    });
});
