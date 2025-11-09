//Función ajax con promesas
console.log("tareas.js se ha cargado correctamente");

function devolverTareasAjaxPromesa() {
  return new Promise(function (resolve, reject) {
    $.ajax({
      method: "GET",
      url: "http://localhost/Proyectos/Prueba_Tecnica/api/tareas.php",
      contentType: "application/json; charset=utf-8",
      dataType: "json",
    })
      .done((res) => {
        console.log("Respuesta del backend (tareas):", res);
        if (res.ok != true) {
          reject(res);
        } else {
          resolve(res);
        }
      })
      .fail((jqXHR, textStatus, errorThrown) => {
        console.log("Error en AJAX (tareas):", jqXHR, textStatus, errorThrown);
        reject({ ok: false, error: "Error de conexión" });
      });
  });
}

async function devolvertareasajax(e) {
  e.preventDefault();
  console.log("Función devolvertareasajax() llamada");

  await devolverTareasAjaxPromesa()
    .then((res) => {
      console.log("Respuesta del backend (tareas):", res);
      getListTareas(res.resources);
    })
    .catch((res) => {
      console.log("Error en la respuesta (tareas):", res.code);
    });
}

function getListTareas(listatareas) {
  // Vaciar contenedor
  $("#id_datosTareas").html("");

  for (let tarea of listatareas) {
    // Construir los valores que se pasarán a los formularios (id_tarea, id_usuario, nombre, descripcion, fechas, completada)
    datosfila =
      "'" +
      tarea.id_tarea +
      "'," +
      "'" +
      tarea.id_usuario +
      "'," +
      "'" +
      tarea.nombre_tarea +
      "'," +
      "'" +
      tarea.descripcion_tarea +
      "'," +
      "'" +
      tarea.fecha_inicio_tarea +
      "'," +
      "'" +
      tarea.fecha_fin_tarea +
      "'," +
      "'" +
      tarea.completada_tarea +
      "'";

    // Construir fila con columnas según el HTML: id, nombre, descripcion, fecha_inicio, fecha_fin, completada, id_usuario
    lineatabla =
      "<tr><td>" +
      tarea["id_tarea"] +
      "</td><td>" +
      tarea["nombre_tarea"] +
      "</td><td>" +
      tarea["descripcion_tarea"] +
      "</td><td>" +
      tarea["fecha_inicio_tarea"] +
      "</td><td>" +
      tarea["fecha_fin_tarea"] +
      "</td><td>" +
      tarea["completada_tarea"] +
      "</td><td>" +
      tarea["id_usuario"] +
      "</td>";

    // Botones de acción (editar, borrar, ver) - funciones creadoras de formularios deben existir en el proyecto
    /*botonedit =
      '<td><img class="titulo_edit" src="./images/edit4.png" onclick="crearformEDITtarea(' +
      datosfila +
      ');" width="50" height="50"></td>';
    botondelete =
      '<td><img class="titulo_delete" src="./images/delete4.png" width="50" height="50" onclick="crearformDELETEtarea(' +
      datosfila +
      ');"></td>';
    botonshowcurrent =
      '<td><img class="titulo_showcurrent" src="./images/detail4.png" width="50" height="50" onclick="crearformSHOWCURRENTtarea(' +
      datosfila +
      ');"></td>';

    lineatabla += botonedit + botondelete + botonshowcurrent + "</tr>";
*/
    $("#id_datosTareas").append(lineatabla);
  }
}
//Cargar automáticamente al abrir la página
document.addEventListener("DOMContentLoaded", devolvertareasajax);
