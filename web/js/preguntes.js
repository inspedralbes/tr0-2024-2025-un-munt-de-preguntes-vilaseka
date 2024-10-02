let data;

fetch('../back/getPreguntes.php?num=10')
  .then(response => response.json())
  .then(dades => {
    data = dades;
    //console.log(data);
    mostrarPregunta();
  });

let iniciTemps = null; // variable per guardar l'hora d'inici des del primer clic
let opcions = ['A', 'B', 'C', 'D']; // opcions per a les respostes
let puntuacio = 0; // variable per comptar respostes correctes
let preguntaIndex = 0; // índex de la pregunta actual
let totalTemps = 0; // temps total de la partida
let estatDeLaPartida = {
  contadorPreguntes: 0,
  preguntes: [
    { id: 1, feta: false, resposta: 0 },
    { id: 2, feta: false, resposta: 0 },
    { id: 3, feta: false, resposta: 0 },
    { id: 4, feta: false, resposta: 0 },
    { id: 5, feta: false, resposta: 0 },
    { id: 6, feta: false, resposta: 0 },
    { id: 7, feta: false, resposta: 0 },
    { id: 8, feta: false, resposta: 0 },
    { id: 9, feta: false, resposta: 0 },
    { id: 10, feta: false, resposta: 0 }
  ]
};

function actualitzarMarcador() {
  let htmlString = '';
  htmlString = `<h2>${estatDeLaPartida.contadorPreguntes}/10</h2>`;
  htmlString += `<table>`;

  for (let index = 0; index < estatDeLaPartida.preguntes.length; index++) {
    htmlString += `<tr><td> - Pregunta ${index + 1}</td><td>`;

    if (estatDeLaPartida.preguntes[index].feta) {
      htmlString += "Feta";
    } else {
      htmlString += "Pendent";
    }
    htmlString += `</td></tr>`;
  }
  htmlString += `</table>`;
  document.getElementById("marcador").innerHTML = htmlString;
}

function mostrarPregunta() {
  const partidaDiv = document.getElementById('partida');
  partidaDiv.innerHTML = '';

  if (preguntaIndex < estatDeLaPartida.preguntes.length) {
    let htmlString = ''; // variable per construir el HTML
    htmlString += `<h2>${data[estatDeLaPartida.contadorPreguntes].pregunta}</h2>`; // afegir la pregunta

    // iterem sobre les respostes
    for (let j = 0; j < data[preguntaIndex].respostes.length; j++) {
      htmlString += `<button class="resposta-button" data-index="${j}">${opcions[j]}</button> ${data[preguntaIndex].respostes[j]}<br>`;
    }


    htmlString += `<button onclick="actualitzarPregunta(${data[preguntaIndex].id})">Modificar Pregunta</button>`;
    htmlString += `<button onclick="eliminarPregunta(${data[preguntaIndex].id})">Eliminar Pregunta</button>`;
    htmlString += `<button onclick="afegirPregunta(${data[preguntaIndex].id})">Afegir Pregunta</button>`;

    partidaDiv.innerHTML = htmlString; // injectar el HTML
    partidaDiv.innerHTML += `<p>Puntuació actual: ${puntuacio}/10</p>`; // mostrar la puntuació

    //event listeners
    const buttons = partidaDiv.querySelectorAll('.resposta-button');
    buttons.forEach(button => {
      button.addEventListener('click', (event) => {
        const index = parseInt(event.target.getAttribute('data-index'), 10);
        gestionarResposta(index);
      });
    });
  } else {
    mostrarResultats();
  }
}

function gestionarResposta(respostaUsuari) {
  // guarda l'hora d'inici
  if (iniciTemps === null) {
    iniciTemps = Date.now(); // guardem el temps en mil·lisegons
  }

  const respostaCorrecta = parseInt(data[preguntaIndex].resposta_correcta, 10); // obtenir la resposta correcta
  const correcioDiv = document.getElementById('correcio'); // espai per correcció

  // comprovar si la resposta és correcta
  if (respostaUsuari === respostaCorrecta) {
    puntuacio++;
    correcioDiv.innerHTML = 'Correcte!'; // mostrar missatge de correcte
  } else {
    correcioDiv.innerHTML = 'Incorrecte!'; // mostrar missatge de incorrecte
  }

  // Actualitzem l'estat de la partida
  estatDeLaPartida.preguntes[preguntaIndex].feta = true;
  estatDeLaPartida.contadorPreguntes++;
  estatDeLaPartida.preguntes[preguntaIndex].resposta = respostaUsuari;

  actualitzarMarcador();

  preguntaIndex++; // incrementar l'índex de la pregunta

  // Mostrar la següent pregunta després de 1 segon
  setTimeout(mostrarPregunta, 500);
}

// funció per mostrar els resultats i temps
function mostrarResultats() {
  const fiTemps = Date.now(); // agafem el temps actual en acabar el joc
  totalTemps = Math.floor((fiTemps - iniciTemps) / 1000); // temps transcorregut en segons

  const partidaDiv = document.getElementById('partida');
  partidaDiv.innerHTML = `<h2>El teu resultat final és: ${puntuacio} de 10</h2>`;
  partidaDiv.innerHTML += `<p>Temps total: ${totalTemps} segons</p>`;
  partidaDiv.innerHTML += `Vols tornar a jugar? <p>`;
  // botó per reiniciar
  partidaDiv.innerHTML += `<p> <button onclick="reiniciarJoc()">Sí</button>`;

  document.getElementById('reiniciar-joc').addEventListener('click', reiniciarJoc);
}

// funció per reiniciar el joc
function reiniciarJoc() {
  puntuacio = 0;
  preguntaIndex = 0;
  iniciTemps = null; // reiniciar l'hora d'inici
  estatDeLaPartida.contadorPreguntes = 0;

  for (let i = 0; i < estatDeLaPartida.preguntes.length; i++) {
    estatDeLaPartida.preguntes[i].feta = false;
    estatDeLaPartida.preguntes[i].resposta = 0;
  }
  fetch('../back/getPreguntes.php?num=10') // tornar a carregar les dades
    .then(response => response.json())
    .then(dades => {
      data = dades; // assignar les preguntes a la variable global
      mostrarPregunta(); // reiniciar el joc
    });

}
//CRUD

//actulitzarPregunta
function actualitzarPregunta(id) {
  const pregunta = data[preguntaIndex];

  // Crear un formulari per editar la pregunta
  let htmlString = `
    <button onclick="mostrarPregunta()">Inici</button>
    <h3>Modificar Pregunta</h3>
    <label>Pregunta:</label>
    <input type="text" id="novaPregunta" value="${pregunta.pregunta}"><br>
    <label>Respostes:</label><br>
    ${pregunta.respostes.map((resposta, index) => `
      <input type="text" id="novaResposta${index}" value="${resposta}">
    `).join('<br>')}
    <br>
    <button onclick="guardarActualitzacio(${id})">Guardar Canvis</button>
  `;

  const partidaDiv = document.getElementById('partida');
  partidaDiv.innerHTML = htmlString; // Mostrar formulari per modificar
}

function guardarActualitzacio(id) {
  const novaPregunta = document.getElementById('novaPregunta').value;
  const novesRespostes = [];

  for (let i = 0; i < 4; i++) {
    novesRespostes.push(document.getElementById(`novaResposta${i}`).value);
  }

  const actualitzacioData = {
    id: id,
    pregunta: novaPregunta,
    respostes: novesRespostes,
    resposta_correcta: data[preguntaIndex].resposta_correcta // mantenir la resposta correcta
  };

  // Enviar petició per actualitzar la pregunta
  fetch('../back/actualitzarPregunta.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify(actualitzacioData)
  })
    .then(response => response.json())
    .then(result => {
      // Aquí pots gestionar la resposta de la teva API
      if (result.success) {
        alert('Pregunta actualitzada amb èxit!');
        reiniciarJoc(); // Reiniciar el joc després d'actualitzar
      } else {
        alert('Error en actualitzar la pregunta: ' + result.message);
      }
    });
}

//eliminarPregunta
function eliminarPregunta(id) {
  if (confirm("Estàs segur que vols eliminar aquesta pregunta?")) {
    fetch('../back/eliminarPregunta.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({ id: id })
    })
      .then(response => response.json())
      .then(result => {
        if (result.success) {
          alert('Pregunta eliminada amb èxit!');
          reiniciarJoc(); // Reiniciar el joc després d'eliminar
        } else {
          alert('Error en eliminar la pregunta: ' + result.message);
        }
      });
  }
}

//afegirPregunta
function afegirPregunta() {
  let htmlString = `
    <button onclick="mostrarPregunta()">Inici</button>
    <h3>Afegir Nova Pregunta</h3>
    <label>Pregunta:</label>
    <input type="text" id="novaPregunta" placeholder="Escriu la pregunta aquí"><br>
    <label>Respostes:</label><br>
    <input type="text" id="novaResposta0" placeholder="Resposta A"><br>
    <input type="text" id="novaResposta1" placeholder="Resposta B"><br>
    <input type="text" id="novaResposta2" placeholder="Resposta C"><br>
    <input type="text" id="novaResposta3" placeholder="Resposta D"><br>
    <label>Resposta Correcta (0-3):</label>
    <input type="number" id="respostaCorrecta" min="0" max="3"><br>
    <button onclick="guardarNovaPregunta()">Guardar Pregunta</button>
  `;

  const partidaDiv = document.getElementById('partida');
  partidaDiv.innerHTML = htmlString; // Mostrar formulari per afegir nova pregunta
}

function guardarNovaPregunta() {
  const novaPregunta = document.getElementById('novaPregunta').value;
  const novesRespostes = [
    document.getElementById('novaResposta0').value,
    document.getElementById('novaResposta1').value,
    document.getElementById('novaResposta2').value,
    document.getElementById('novaResposta3').value,
  ];
  const respostaCorrecta = parseInt(document.getElementById('respostaCorrecta').value, 10);

  const novaPreguntaData = {
    pregunta: novaPregunta,
    respostes: novesRespostes,
    resposta_correcta: respostaCorrecta
  };

  // Enviar petició per afegir la nova pregunta
  fetch('../back/crearPregunta.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify(novaPreguntaData)
  })
    .then(response => response.json())
    .then(result => {
      if (result.success) {
        alert('Pregunta afegida amb èxit!');
        reiniciarJoc(); // Reiniciar el joc després d'afegir
      } else {
        alert('Error en afegir la pregunta: ' + result.message);
      }
    });
}
