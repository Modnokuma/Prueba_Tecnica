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
  if (e && e.preventDefault) e.preventDefault();
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

function addTarea() {
  window.location.href = "addTarea.html";
}

function editTarea(id) {
  window.location.href = `editTarea.html?id_tarea=${id}`;
}

function deleteTarea(id) {
  window.location.href = `deleteTarea.html?id_tarea=${id}`;
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

    botonedit =
      '<td><span class="material-symbols-outlined button-icon" onclick="editTarea(' +
      tarea.id_tarea +
      ')">edit</span></td>';
    botondelete =
      '<td><span class="material-symbols-outlined button-icon" onclick="deleteTarea(' +
      tarea.id_tarea +
      ')">delete</span></td>';
   
    lineatabla += botonedit + botondelete + "</tr>";
    $("#id_datosTareas").append(lineatabla);
  }
}

// Añadir tarea
function peticionADDtareaPromesa() {
  const id_usuario = document.getElementById("id_id_usuario").value;
  const nombre = document.getElementById("id_nombre_tarea").value;
  const descripcion = document.getElementById("id_descripcion_tarea").value;
  const fecha_inicio_raw = document.getElementById("id_fecha_inicio_tarea").value;
  const fecha_fin_raw = document.getElementById("id_fecha_fin_tarea").value;
  const completada = document.getElementById("id_completada_tarea").value;

  fecha_inicio = fecha_inicio_raw.replace("T", " ") + ":00";
  fecha_fin = fecha_fin_raw.replace("T", " ") + ":00";

  return new Promise(function (resolve, reject) {
    $.ajax({
      method: "PUT",
      url: "http://localhost/Proyectos/Prueba_Tecnica/api/tareas.php",
      contentType: "application/json; charset=utf-8",
      dataType: "json",
      data: JSON.stringify({
        id_usuario: id_usuario,
        nombre_tarea: nombre,
        descripcion_tarea: descripcion,
        fecha_inicio_tarea: fecha_inicio,
        fecha_fin_tarea: fecha_fin,
        completada_tarea: completada,
      }),
    })
      .done((res) => {
        console.log("Respuesta del backend (ADD tarea):", res);
        if (res.ok != true) {
          reject(res);
        } else {
          resolve(res);
        }
      })
      .fail((jqXHR) => {
        console.log("Error en AJAX (ADD tarea):", jqXHR);
        reject({ ok: false, error: "Error de conexión" });
      });
  });
}

async function peticionADDtarea(e) {
  if (e && e.preventDefault) e.preventDefault();
  console.log("Función peticionADDtarea() llamada");

  await peticionADDtareaPromesa()
    .then((res) => {
      if (res.code == "SQL_OK") {
        res.code = "add_tarea_OK";
      }
      window.location.href = "tareas.html";
      devolvertareasajax();
    })
    .catch((res) => {
      console.log("Error en la respuesta (ADD tarea):", res.code);
    });
}

// Cargar datos de una tarea
async function cargarDatosTarea(id) {
  try {
    const res = await $.ajax({
      method: "GET",
      url: `http://localhost/Proyectos/Prueba_Tecnica/api/tareas.php?id_tarea=${id}`,
      dataType: "json",
    });

    if (res.ok && res.resources && res.resources.length > 0) {
      const t = res.resources[0];
      document.getElementById("id_tarea").value = t.id_tarea;
      document.getElementById("id_id_usuario").value = t.id_usuario;
      document.getElementById("id_nombre_tarea").value = t.nombre_tarea;
      document.getElementById("id_descripcion_tarea").value =
        t.descripcion_tarea;
      document.getElementById("id_fecha_inicio_tarea").value =
        t.fecha_inicio_tarea;
      document.getElementById("id_fecha_fin_tarea").value = t.fecha_fin_tarea;
      document.getElementById("id_completada_tarea").value = t.completada_tarea;
    } else {
      $("#mensaje").text("Tarea no encontrada").css("color", "red");
    }
  } catch (err) {
    console.error("Error cargando tarea:", err);
    $("#mensaje").text("Error cargando tarea").css("color", "red");
  }
}

// EDIT
function peticionEDITtareaPromesa() {
  const id = document.getElementById("id_tarea").value;
  const id_usuario = document.getElementById("id_id_usuario").value;
  const nombre = document.getElementById("id_nombre_tarea").value;
  const descripcion = document.getElementById("id_descripcion_tarea").value;
  const fecha_inicio_raw = document.getElementById("id_fecha_inicio_tarea").value;
  const fecha_fin_raw = document.getElementById("id_fecha_fin_tarea").value;
  const completada = document.getElementById("id_completada_tarea").value;
console.log("Enviando edición:", {
  id_tarea: document.getElementById("id_tarea").value,
  id_usuario: document.getElementById("id_id_usuario").value,
  nombre_tarea: document.getElementById("id_nombre_tarea").value
});
 const fecha_inicio = fecha_inicio_raw.replace("T", " ") + ":00";
  const fecha_fin = fecha_fin_raw.replace("T", " ") + ":00";

  return new Promise(function (resolve, reject) {
    $.ajax({
      method: "POST",
      url: "http://localhost/Proyectos/Prueba_Tecnica/api/tareas.php",
      contentType: "application/json; charset=utf-8",
      dataType: "json",
      data: JSON.stringify({
        id_tarea: id,
        id_usuario: id_usuario,
        nombre_tarea: nombre,
        descripcion_tarea: descripcion,
        fecha_inicio_tarea: fecha_inicio,
        fecha_fin_tarea: fecha_fin,
        completada_tarea: completada,
      }),
    })
      .done((res) => {
        console.log("Respuesta del backend (EDIT tarea):", res);
        if (res.ok != true) {
          reject(res);
        } else {
          resolve(res);
        }
      })
      .fail((jqXHR) => {
        console.log("Error en AJAX (EDIT tarea):", jqXHR);
        reject({ ok: false, error: "Error de conexión" });
      });
  });
}

async function peticionEDITtarea(e) {
  if (e && e.preventDefault) e.preventDefault();
  console.log("Función peticionEDITtarea() llamada");

  await peticionEDITtareaPromesa()
    .then((res) => {
      if (res.code == "SQL_OK") {
        $("#mensaje").text("Tarea editada correctamente").css("color", "green");
        setTimeout(() => (window.location.href = "tareas.html"), 1000);
      } else {
        $("#mensaje").text("Error al editar la tarea").css("color", "red");
      }
    })
    .catch((res) => {
      console.log("Error en la respuesta (EDIT tarea):", res.code);
    });
}

// DELETE 
function peticionDELETEtareaPromesa() {
  const id = document.getElementById("id_tarea").value;
  return new Promise(function (resolve, reject) {
    $.ajax({
      method: "DELETE",
      url: "http://localhost/Proyectos/Prueba_Tecnica/api/tareas.php",
      contentType: "application/json; charset=utf-8",
      dataType: "json",
      data: JSON.stringify({ id_tarea: id }),
    })
      .done((res) => {
        console.log("Respuesta del backend (DELETE tarea):", res);
        if (res.ok != true) {
          reject(res);
        } else {
          resolve(res);
        }
      })
      .fail((jqXHR) => {
        console.log("Error en AJAX (DELETE tarea):", jqXHR);
        reject({ ok: false, error: "Error de conexión" });
      });
  });
}

async function peticionDELETEtarea(e) {
  if (e && e.preventDefault) e.preventDefault();
  console.log("Función peticionDELETEtarea() llamada");

  await peticionDELETEtareaPromesa()
    .then((res) => {
      if (res.code == "SQL_OK") {
        $("#mensaje")
          .text("Tarea eliminada correctamente")
          .css("color", "green");
        setTimeout(() => (window.location.href = "tareas.html"), 1000);
      } else {
        $("#mensaje").text("Error al eliminar la tarea").css("color", "red");
      }
    })
    .catch((res) => {
      console.log("Error en la respuesta (DELETE tarea):", res.code);
    });
}

// SEARCH
function peticionSEARCHtareaPromesa() {
  const searchInput = document.getElementById("searchInput").value.trim();
  const nombre = searchInput;
  console.log("Buscando tareas con:", nombre);
  return new Promise(function (resolve, reject) {
    $.ajax({
      method: "GET",
      url: "http://localhost/Proyectos/Prueba_Tecnica/api/tareas.php",
      dataType: "json",
      data: {
        nombre_tarea: nombre,
      },
    })
      .done((res) => {
        console.log("Respuesta del backend (SEARCH tarea):", res);
        if (res.ok != true) {
          reject(res);
        } else {
          resolve(res);
        }
      })
      .fail((jqXHR) => {
        console.log("Error en AJAX SEARCH (tarea):", jqXHR);
        reject({ ok: false, error: "Error de conexión" });
      });
  });
}

async function peticionSEARCHtarea(query) {
  console.log("Función peticionSEARCHtarea() llamada", query);

  await peticionSEARCHtareaPromesa()
    .then((res) => {
      if (res.code === "RECORDSET_DATOS") {
        $("#mensaje").text("Resultados obtenidos").css("color", "green");
        getListTareas(res.resources);
      } else {
        $("#mensaje").text("Error al obtener resultados").css("color", "red");
      }
    })
    .catch((res) => {
      console.log("Error en la respuesta (SEARCH tarea):", res.code);
    });
}

function initTareasList() {
  console.log("initTareasList");
  devolvertareasajax();
}

function initTareasForm() {
  console.log("initTareasForm");
  const form = document.getElementById("addTareaForm");
  if (form) form.addEventListener("submit", peticionADDtarea);
}

function volverMenu() {
  window.location.href = "menu.html";
}


document.addEventListener("DOMContentLoaded", async () => {
  const tabla = document.getElementById("id_datosTareas");
  const formAdd = document.getElementById("addTareaForm");
  const formEdit = document.getElementById("editTareaForm");
  const formDelete = document.getElementById("deleteTareaForm");
  const searchInput = document.getElementById("searchInput");

  if (tabla) initTareasList();
  if (formAdd) initTareasForm();
  if (formEdit) {
    const params = new URLSearchParams(window.location.search);
    const id = params.get("id_tarea");
    if (id) await cargarDatosTarea(id);
    console.log("Se ha encontrado el formEdit (tarea)");
    formEdit.addEventListener("submit", peticionEDITtarea);
  }
  if (formDelete) {
    const params = new URLSearchParams(window.location.search);
    const id = params.get("id_tarea");
    if (id) await cargarDatosTarea(id);
    console.log("Se ha encontrado el formDelete (tarea)");
    formDelete.addEventListener("submit", peticionDELETEtarea);
  }
  if (searchInput) {
    searchInput.addEventListener("keyup", async (e) => {
      const query = e.target.value.trim();
      if (query.length === 0) {
        devolvertareasajax();
      } else {
        await peticionSEARCHtarea(query);
      }
    });
  }
});
