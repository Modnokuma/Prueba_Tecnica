console.log("Menu cargado correctamente");

    // Redirecciones simples
    document.getElementById("btn_tareas").addEventListener("click", () => {
      console.log("Redirigiendo a gestión de tareas...");
      window.location.href = "tareas.html";
    });

    document.getElementById("btn_usuarios").addEventListener("click", () => {
      console.log("Redirigiendo a gestión de usuarios...");
      window.location.href = "usuarios.html";
    });