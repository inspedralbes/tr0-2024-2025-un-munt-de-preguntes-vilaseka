let data;
document.getElementById('crud').addEventListener('click',function(){
  window.location.href = 'index1.html';
});
document.getElementById('començarJoc').addEventListener('click', () => {
    const nomJugador = document.getElementById('nomJugador').value.trim();
    if (nomJugador === "") {
        alert("Si us plau, introdueix el teu nom.");
        return;
    }
    document.getElementById('inici').style.display = 'none'; // Ocultar la secció d'inici
    document.getElementById('partida').style.display = 'block'; // Mostrar la secció de partida
    fetchPreguntes(); // Iniciar el procés de fetch per les preguntes
});

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
let correctes = 0;
let incorrectes = 0;
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
    partidaDiv.innerHTML = htmlString; // injectar el HTML
    //event listeners
    const buttons = partidaDiv.querySelectorAll('.resposta-button');
    buttons.forEach(button => {
      button.addEventListener('click', (event) => {
        const index = parseInt(event.target.getAttribute('data-index'));
        gestionarResposta(index);
      });
    });
  } else {
    mostrarResultats();
  }
}

function gestionarResposta(respostaUsuari) {
  // Comprovar si és la primera vegada que s'inicia el temporitzador
  if (iniciTemps === null) {
    iniciTemps = Date.now(); // Guardem el temps en mil·lisegons
  }

  // Obtenir la resposta correcta
  const respostaCorrecta = parseInt(data[preguntaIndex].resposta_correcta, 10); // Garantir que és un número
  const correcioDiv = document.getElementById('correcio'); // Espai per a mostrar la correcció

  // Comprovar si la resposta és correcta
  if (respostaUsuari === respostaCorrecta) {
    puntuacio++; // Incrementar la puntuació total
    correctes++; // Incrementar el comptador de respostes correctes
    correcioDiv.innerHTML = 'Correcte!'; // Mostrar que la resposta és correcta
  } else {
    incorrectes++; // Incrementar el comptador de respostes incorrectes
    correcioDiv.innerHTML = 'Incorrecte!'; // Mostrar que la resposta és incorrecta
  }

  // Actualitzar l'estat de la partida
  estatDeLaPartida.preguntes[preguntaIndex].feta = true; // Marcar la pregunta com a feta
  estatDeLaPartida.contadorPreguntes++; // Incrementar el comptador de preguntes fetes
  estatDeLaPartida.preguntes[preguntaIndex].resposta = respostaUsuari; // Guardar la resposta de l'usuari

  // Actualitzar el marcador (puntuació, respostes correctes, incorrectes)
  actualitzarMarcador();

  // Incrementar l'índex de la pregunta
  preguntaIndex++;

  // Comprovar si hi ha més preguntes disponibles
  if (preguntaIndex < data.preguntes.length) {
    setTimeout(mostrarPregunta, 500); // Mostrar la següent pregunta després d'un petit retard
  } else {
    mostrarResultats(); // Si no hi ha més preguntes, mostrar els resultats finals
  }
}

function gestionarResposta(respostaUsuari) {
  // guarda l'hora d'inici
  if (iniciTemps === null) {
    iniciTemps = Date.now(); // guardem el temps en mil·lisegons
  }

  // Obtenim la resposta correcta
  const respostaCorrecta = parseInt(data[preguntaIndex].resposta_correcta); // garantir que és un número
  const correcioDiv = document.getElementById('correcio'); // espai per correcció

  // comprovar si la resposta és correcta
  if (respostaUsuari === respostaCorrecta) {
    puntuacio++;
    correctes++;
    correcioDiv.innerHTML = 'Correcte!'; // Mostrar la correcció
  } else {
    incorrectes++;
    correcioDiv.innerHTML = 'Incorrecte!'; // Mostrar la correcció
  }

  //actualitzem l'estat de la partida
  estatDeLaPartida.preguntes[preguntaIndex].feta = true;
  estatDeLaPartida.contadorPreguntes++;
  estatDeLaPartida.preguntes[preguntaIndex].resposta = respostaUsuari;
  actualitzarMarcador();
  preguntaIndex++; // incrementar l'índex de la pregunta
  setTimeout(mostrarPregunta, 500);
}

// funció per mostrar els resultats i temps
function mostrarResultats() {
  const fiTemps = Date.now(); // agafem el temps actual en acabar el joc
  totalTemps = Math.floor((fiTemps - iniciTemps) / 1000); // temps transcorregut en segons

  const partidaDiv = document.getElementById('partida');
  partidaDiv.innerHTML = `<h2>El teu resultat final és: ${puntuacio} de 10</h2>`;
  partidaDiv.innerHTML += `<p>Correctes: ${correctes}</p>`; // Mostrar respostes correctes
  partidaDiv.innerHTML += `<p>Incorrectes: ${incorrectes}</p>`; // Mostrar respostes incorrectes
  partidaDiv.innerHTML += `<p>Temps total: ${totalTemps} segons.</p>`;
  partidaDiv.innerHTML += `<p>Vols tornar a jugar?</p>`;
  // botó per reiniciar partida
  partidaDiv.innerHTML += `<button onclick="reiniciarJoc()">Sí</button>`;
}


// funció per reiniciar el joc
function reiniciarJoc() {
  puntuacio = 0;
  preguntaIndex = 0;
  iniciTemps = null; // reiniciar l'hora d'inici
  estatDeLaPartida.contadorPreguntes = 0;
  correctes = 0;
  incorrectes = 0;

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
