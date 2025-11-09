//Función ajax con promesas
console.log("usuarios.js se ha cargado correctamente");
function devolverusuariosAjaxPromesa() {
  return new Promise(function (resolve, reject) {
    $.ajax({
      method: "GET",
      //url: "http://193.147.87.202/Back/index.php",
      url: "http://localhost/Proyectos/Prueba_Tecnica/api/usuarios.php",
      contentType: "application/json; charset=utf-8",
      dataType: "json",
    })
      .done((res) => {
        console.log("Respuesta del backend:", res);
        if (res.ok != true) {
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

async function devolverusuariosajax(e) {
  if (e && e.preventDefault) e.preventDefault();
  console.log("Función devolverusuariosajax() llamada");

  await devolverusuariosAjaxPromesa()
    .then((res) => {
      console.log("Respuesta del backend:", res);
      getListUsuarios(res.resources);
    })
    .catch((res) => {
      console.log("Error en la respuesta:", res.code);
    });
}

function addUsuario() {
  window.location.href = "addUsuario.html";
}
function editUsuario(id) {
  window.location.href = `editUsuario.html?id_usuario=${id}`;
}

function deleteUsuario(id) {
  window.location.href = `deleteUsuario.html?id_usuario=${id}`;
}

//Mostrar lista de usuarios
function getListUsuarios(listausuarios) {
  $("#id_datosusuarios").html(""); // Lo vacias, aunque existiera algo.

  for (let usuario of listausuarios) {
    // Construir los valores que se pasarán a los formularios (id, correo, nombre, apellidos)
    datosfila =
      "'" +
      usuario.id_usuario +
      "'," +
      "'" +
      usuario.correo_usuario +
      "'," +
      "'" +
      usuario.contrasena_usuario +
      "'," +
      "'" +
      usuario.nombre_usuario +
      "'," +
      "'" +
      usuario.apellidos_usuario +
      "'";

    // Fila con columnas: id, correo, contrasena, nombre, apellidos
    lineatabla =
      "<tr><td>" +
      usuario["id_usuario"] +
      "</td><td>" +
      usuario["correo_usuario"] +
      "</td><td>" +
      usuario["contrasena_usuario"] +
      "</td><td>" +
      usuario["nombre_usuario"] +
      "</td><td>" +
      usuario["apellidos_usuario"] +
      "</td>";
    //abrimos columna y ponemos un img con un class para que aparezca un texto al pasar el raton y un on click. Despues
    //concatenas con lo de antes y tenemos el boton de edit. Es la misma construccion estatica pero de manera dinamica
    //con los valores que sacamos.

    botonedit =
      '<td><span class="material-symbols-outlined button-icon" onclick="editUsuario(' +
      usuario.id_usuario +
      ')">edit</span></td>';
    botondelete =
      '<td><span class="material-symbols-outlined button-icon" onclick="deleteUsuario(' +
      usuario.id_usuario +
      ')">delete</span></td>';
    //botonshowcurrent = '<td><img class="titulo_showcurrent" src="./images/detail4.png" width="50" height="50" onclick="crearformSHOWCURRENTusuario('+datosfila+');"></td>';

    lineatabla += botonedit + botondelete + "</tr>";

    $("#id_datosusuarios").append(lineatabla);
  }
}

//Añadir usuario
//Función ajax con promesas ; usuarioADDAjaxPromesa
function peticionADDusuarioPromesa() {
  const correo = document.getElementById("id_correo_usuario").value;
  const contrasena = document.getElementById("id_contrasena_usuario").value;
  const nombre = document.getElementById("id_nombre_usuario").value;
  const apellidos = document.getElementById("id_apellidos_usuario").value;
  return new Promise(function (resolve, reject) {
    $.ajax({
      method: "PUT",
      url: "http://localhost/Proyectos/Prueba_Tecnica/api/usuarios.php",
      contentType: "application/json; charset=utf-8",
      dataType: "json",
      data: JSON.stringify({
        correo_usuario: correo,
        contrasena_usuario: contrasena,
        nombre_usuario: nombre,
        apellidos_usuario: apellidos,
      }),
    })
      .done((res) => {
        console.log("Respuesta del backend (ADD):", res);
        if (res.ok != true) {
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

//ADDusuarioajax()
async function peticionADDusuario(e) {
  e.preventDefault();
  console.log("Función peticionADDusuario() llamada");

  await peticionADDusuarioPromesa()
    .then((res) => {
      if ((res.code = "SQL_OK")) {
        res.code = "add_usuario_OK";
      }
      devolverusuariosajax(); // refresca la t
    })
    .catch((res) => {
      console.log("Error en la respuesta:", res.code);
    });
}

// --- CARGAR DATOS PARA EDITAR ---
async function cargarDatosUsuario(id) {
  try {
    const res = await $.ajax({
      method: "GET",
      url: `http://localhost/Proyectos/Prueba_Tecnica/api/usuarios.php?id_usuario=${id}`,
      dataType: "json",
    });

    if (res.ok && res.resources && res.resources.length > 0) {
      const u = res.resources[0];
      document.getElementById("id_usuario").value = u.id_usuario;
      document.getElementById("id_correo_usuario").value = u.correo_usuario;
      document.getElementById("id_contrasena_usuario").value = u.contrasena_usuario;
      document.getElementById("id_nombre_usuario").value = u.nombre_usuario;
      document.getElementById("id_apellidos_usuario").value =
        u.apellidos_usuario;
    } else {
      $("#mensaje").text("Usuario no encontrado").css("color", "red");
    }
  } catch (err) {
    console.error("Error cargando usuario:", err);
    $("#mensaje").text("Error cargando usuario").css("color", "red");
  }
}

//Función ajax con promesas
function peticionEDITusuarioPromesa() {
  const id = document.getElementById("id_usuario").value;
  const correo = document.getElementById("id_correo_usuario").value;
  const contrasena = document.getElementById("id_contrasena_usuario").value;
  const nombre = document.getElementById("id_nombre_usuario").value;
  const apellidos = document.getElementById("id_apellidos_usuario").value;

  return new Promise(function (resolve, reject) {
    $.ajax({
      method: "POST",
      url: "http://localhost/Proyectos/Prueba_Tecnica/api/usuarios.php",
      contentType: "application/json; charset=utf-8",
      dataType: "json",
      data: JSON.stringify({
        id_usuario: id,
        correo_usuario: correo,
        contrasena_usuario: contrasena,
        nombre_usuario: nombre,
        apellidos_usuario: apellidos,
      }),
    })
      .done((res) => {
        console.log("Respuesta del backend (EDIT):", res);
        if (!(res.ok == true)) {
          reject(res);
        } else {
          resolve(res);
        }
      })
      .fail((jqXHR) => {
        console.log("Error en AJAX (DELETE):", jqXHR);
        reject({ ok: false, error: "Error de conexión" });
      });
  });
}

// EDIT
async function peticionEDITusuario(e) {
  e.preventDefault();
    console.log("Función peticionEDITusuario() llamada");

  await peticionEDITusuarioPromesa()
    .then((res) => {
      if ((res.code = "SQL_OK")) {
        $("#mensaje")
          .text("Usuario editado correctamente")
          .css("color", "green");

        // Redirigir después de 1 segundo
        setTimeout(() => {
          window.location.href = "usuarios.html";
        }, 1000);
      } else {
        $("#mensaje").text(" Error al editar el usuario").css("color", "red");
      }
    })
    .catch((res) => {
      console.log("Error en la respuesta:", res.code);
    });
}

//Función ajax con promesas
function peticionDELETEusuarioPromesa() {
  const id = document.getElementById("id_usuario").value;
  return new Promise(function (resolve, reject) {
    $.ajax({
      method: "DELETE",
      url: "http://localhost/Proyectos/Prueba_Tecnica/api/usuarios.php",
      contentType: "application/json; charset=utf-8",
      dataType: "json",
      data: JSON.stringify({ id_usuario: id }),
    })
      .done((res) => {
        console.log("Respuesta del backend (DELETE):", res);
        if (!(res.ok == true)) {
          reject(res);
        } else {
          resolve(res);
        }
      })
      .fail((jqXHR) => {
        console.log("Error en AJAX (DELETE):", jqXHR);
        reject({ ok: false, error: "Error de conexión" });
      });
  });
}

async function peticionDELETEusuario(e) {
  e.preventDefault();
  console.log("Función peticionDELETEusuario() llamada");

  await peticionDELETEusuarioPromesa()
    .then((res) => {
      if ((res.code = "SQL_OK")) {
        $("#mensaje")
          .text("Usuario eliminado correctamente")
          .css("color", "green");

        // Redirigir después de 1 segundo
        setTimeout(() => {
          window.location.href = "usuarios.html";
        }, 1000);
      } else {
        $("#mensaje").text(" Error al eliminar el usuario").css("color", "red");
      }
    })
    .catch((res) => {
      console.log("Error en la respuesta:", res.code);
    });
}

function initUsuariosList() {
  console.log("initUsuariosList");
  devolverusuariosajax(); // carga tabla
}

function initUsuariosForm() {
  console.log("initUsuariosForm");
  const form = document.getElementById("addUsuarioForm");
  if (form) form.addEventListener("submit", peticionADDusuario);
}

/*document.addEventListener("DOMContentLoaded", () => {
  // Si existe la tabla, estamos en usuarios.html
  if (document.getElementById("id_datosusuarios")) {
    initUsuariosList();
  }

  // Si existe el form, estamos en formUsuario.html
  if (document.getElementById("addUsuarioForm")) {
    initUsuariosForm();
  }
});*/

document.addEventListener("DOMContentLoaded", async () => {
  const tabla = document.getElementById("id_datosusuarios");
  const formAdd = document.getElementById("addUsuarioForm");
  const formEdit = document.getElementById("editUsuarioForm");
  const formDelete = document.getElementById("deleteUsuarioForm");

  if (tabla) initUsuariosList();
  if (formAdd) initUsuariosForm();
  if (formEdit) {
    const params = new URLSearchParams(window.location.search);
    const id = params.get("id_usuario");
    if (id) await cargarDatosUsuario(id);
    console.log("Se ha encontrado el formEdit");
    formEdit.addEventListener("submit", peticionEDITusuario);
  }
  if (formDelete) {
    const params = new URLSearchParams(window.location.search);
    const id = params.get("id_usuario");
    if (id) await cargarDatosUsuario(id);
    console.log("Se ha encontrado el formDelete");
    formDelete.addEventListener("submit", peticionDELETEusuario);
  }
});

/*const idStr = params.get("id_usuario");
const id = idStr ? parseInt(idStr, 10) : null;
if (!id) {
  console.warn("id_usuario inválido o ausente");
  // mostrar mensaje o redirigir
}*/
