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
    htmlString += `<h2>${data[0][estatDeLaPartida.contadorPreguntes].pregunta}</h2>`; // afegir la pregunta
    
    // iterem sobre les respostes
    for (let j = 0; j < data[0][preguntaIndex].respostes.length; j++) {
      htmlString += `<button class="resposta-button" data-index="${j}">${opcions[j]}</button> ${data[0][preguntaIndex].respostes[j]}<br>`;
    }

    partidaDiv.innerHTML = htmlString; // injectar el HTML
    partidaDiv.innerHTML += `<p>Puntuació actual: ${puntuacio}/10</p>`; // mostrar la puntuació

    //event listeners
    const buttons = partidaDiv.querySelectorAll('.resposta-button');
    buttons.forEach(button => {
      button.addEventListener('click', (event) => {
        const index = event.target.getAttribute('data-index');
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

  const respostaCorrecta = data[0][preguntaIndex].resposta_correcta; // obtenir la resposta correcta
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