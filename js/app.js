
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

//ANIMACION PARA BARRA DE ESTADO
document.addEventListener("DOMContentLoaded", () => {

    const timelines = document.querySelectorAll(".timeline");

    timelines.forEach((timeline, index) => {

        const estadoReal = timeline.dataset.estado;
        const label = timeline.parentElement.querySelector(".status-label");

        // Reset timeline
        timeline.classList.remove("proceso","resuelto","cancelado");
        timeline.classList.add("pendiente");

        if(label){

            // 1. Forzar inicio en izquierda
            label.classList.remove("center","right");
            label.classList.add("left");
            label.style.opacity = "0";

        }

        setTimeout(() => {

            // Animar timeline
            timeline.classList.remove("pendiente");
            timeline.classList.add(estadoReal);

            if(label){

                // 2. Mover a su posición real
                label.classList.remove("left");

                if(estadoReal === "proceso"){
                    label.classList.add("center");
                }

                if(estadoReal === "resuelto" || estadoReal === "cancelado"){
                    label.classList.add("right");
                }

                // 3. Fade in
                label.style.opacity = "1";
            }

        }, 300 + (index * 150));

    });

});

//CANCELAR TICKET
function confirmarCancelacion(ticketId){

    if(confirm("¿Estás seguro de cancelar este ticket?")){
        window.location.href = "cancelar_ticket.php?id=" + ticketId;
    }

}