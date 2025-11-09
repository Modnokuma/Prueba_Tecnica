//Funcion ajax con promesas
console.log("login.js se ha cargado correctamente");
function loginAjaxPromesa() {
  const correo = document.getElementById("id_correo").value;
  const contrasena = document.getElementById("id_contrasena").value;

  return new Promise(function (resolve, reject) {
    $.ajax({
      method: "POST",
      //url: "http://193.147.87.202/Back/index.php",
      url: "http://localhost/Proyectos/Prueba_Tecnica/api/login.php",
      data: JSON.stringify({ correo, contrasena }),
      contentType: "application/json; charset=utf-8",
      dataType: "json",
    })
      .done((res) => {
         console.log("Respuesta del backend:", res);
        if (res.code != "login_OK") {
          reject(res);
        } else {
          resolve(res);
        }
      })
      .fail((jqXHR) => {
         console.log("Error en AJAX:", jqXHR);
        reject({ ok: false, error: "Error de conexión" });
      });
  });
}

async function login(e) {
  e.preventDefault();
  console.log("Función login() llamada");
  const mensaje = document.getElementById("id_mensaje");
  mensaje.textContent = "Verificando credenciales...";
  mensaje.style.color = "gray";

  await loginAjaxPromesa()
    .then((res) => {
      mensaje.textContent = `Bienvenido, ${res.resources.nombre_usuario}`;
      mensaje.style.color = "green";

      // Redirigir al menú principal
      console.log("Usuario autenticado:", res.resources);
      window.location.href = "menu.html";
    })
    .catch((res) => {
      mensaje.textContent = res.resources || "Error en el login";
      mensaje.style.color = "red";
    });
}

// Asociar evento al formulario
document.getElementById("id_loginForm").addEventListener("submit", login);
console.log("Listener asociado al formulario");
