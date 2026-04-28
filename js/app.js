
function togglePassword() {
    const input = document.getElementById("password");
    const icon = document.querySelector(".toggle-password");

    if (input.type === "password") {
        input.type = "text";
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");
    } else {
        input.type = "password";
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");
    }
}

//ERROR DE ARCHIVOS MAX
document.addEventListener("DOMContentLoaded", function(){

    const archivos = document.getElementById("adjuntos");
    const error = document.getElementById("archivo-error");

    if(archivos){

        archivos.addEventListener("change", function(){

            if(this.files.length > 3){

                error.style.display = "block";
                error.textContent =
                "Solo puedes adjuntar máximo 3 archivos.";

                this.value = ""; // limpia selección

            } else {

                error.style.display = "none";
                error.textContent = "";
            }

        });

    }

});