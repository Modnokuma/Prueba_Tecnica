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
  e.preventDefault();
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

    //botonedit = '<td><img class="titulo_edit" src="./images/edit4.png" onclick="crearformEDITusuario('+datosfila+');" width="50" height="50"></td>';
    //botondelete = '<td><img class="titulo_delete" src="./images/delete4.png" width="50" height="50" onclick="crearformDELETEusuario('+datosfila+');"></td>';
    //botonshowcurrent = '<td><img class="titulo_showcurrent" src="./images/detail4.png" width="50" height="50" onclick="crearformSHOWCURRENTusuario('+datosfila+');"></td>';

    //lineatabla += botonedit+botondelete+botonshowcurrent+"</tr>";

    $("#id_datosusuarios").append(lineatabla);
  }
}
//Cargar automáticamente al abrir la página
document.addEventListener("DOMContentLoaded", devolverusuariosajax);
