let data; // variable global

fetch('http://localhost:80/projecte1edu/data.json')
  .then(response => response.json())
  .then(dades => {
    data = dades.preguntes; // assigna les preguntes a la variable global
    console.log(data);
    mostrarPregunta(); // crida la funció després d'assignar les dades
  });

  let iniciTemps = null; // variable per guardar l'hora d'inici desde el primer click
  let opcions = ['A', 'B', 'C', 'D']; // opcions per a les respostes
  let puntuacio = 0; // variable per comptar respostes correctes
  let preguntaIndex = 0; // índex de la pregunta actual
  let totalTemps = 0; // temps total de la partida
  

function mostrarPregunta() {
  const partidaDiv = document.getElementById('partida');
  partidaDiv.innerHTML = ''; // buidar el contingut anterior

  if (preguntaIndex < 10) {
    let htmlString = ''; // variable per construir el HTML
    let pregunta = data[preguntaIndex]; // obtenir la pregunta actual
    htmlString += `<h2>${pregunta.pregunta}</h2>`; // afegir la pregunta

    // Iterar sobre les respostes
    for (let j = 0; j < pregunta.respostes.length; j++) {
      htmlString += `<button onclick="gestionarResposta(${j})">${opcions[j]}</button> ${pregunta.respostes[j]}<br>`;
    }

    partidaDiv.innerHTML = htmlString; // injectar el HTML
    partidaDiv.innerHTML += `<p>Puntuació actual:${puntuacio}/10</p>`; // mostrar la puntuació
   
  } else {
    mostrarResultats(); // si no hi ha més preguntes, mostrar resultats
  }
}

function gestionarResposta(respostaUsuari) {
  //guarda l'hora d'inici
  if (iniciTemps === null) {
    iniciTemps = Date.now(); // guardem el temps en mil·lisegons
  }
  const respostaCorrecta = data[preguntaIndex].resposta_correcta; // obtenir la resposta correcta
  const correcioDiv = document.getElementById('correcio'); // espai per correcio

  // comprovar si la resposta és correcta
  if (respostaUsuari === respostaCorrecta) {
    puntuacio++;
    correcioDiv.innerHTML = 'Correcte!'; // mostrar missatge de correcte
  } else {
    correcioDiv.innerHTML = 'Incorrecte! La resposta correcta és: ' + data[preguntaIndex].respostes[respostaCorrecta]; // mostrar resposta correcta
  }  
  // if(preguntaIndex>preguntes.length){
    setTimeout(mostrarPregunta,2000); // mostrar la següent pregunta després de 2 segons
    preguntaIndex++; // avançar a la següent pregunta
  // }else{
    // preguntaIndex++; // avançar a la següent pregunta

  // }
}

// això es del Álvaro, no facis cas.
function actualitzarMarcador(){
  let htmlString='';
  htmlString=`<h2>${estatPartida.preguntaFeta}/10</h2>`
  htmlString+=`<table>`
  for (let index = 0; index < estatPartida.respostes.length; index++){
    htmlString+= `<tr><td>Pregunta ${index+1}</td><td>${estatPartida.preguntes[index].resposta+1}</td></tr>`;  
  }
  document.getElementById("marcador").innerHTML= htmlString;
}
 
// funció per mostrar els resultats i que diu el temps total transcorregut
function mostrarResultats() {
  
  const fiTemps = Date.now(); // agafem el temps actual en acabar el joc
  totalTemps = Math.floor((fiTemps - iniciTemps) / 1000); // temps transcorregut en segons

  const partidaDiv = document.getElementById('partida');
  partidaDiv.innerHTML = `<h2>El teu resultat final és: ${puntuacio} de 10</h2>`;
  partidaDiv.innerHTML += `<p>Temps total: ${totalTemps} segons</p>`;  
  // botó per reiniciar el joc
  partidaDiv.innerHTML += `Vols tornar a jugar? <p>`;
  partidaDiv.innerHTML += `<p> <button onclick="reiniciarJoc()">Sí</button>`;
}

// funció per reiniciar el joc
function reiniciarJoc() {
  puntuacio = 0;
  preguntaIndex = 0;
  iniciTemps = null; // reiniciar l'hora d'inici
  fetch('http://localhost:80/projecte1edu/data.json') // tornar a carregar les dades
    .then(response => response.json())
    .then(dades => {
      data = dades.preguntes; // assignar les preguntes a la variable global
      mostrarPregunta(); // reiniciar el joc
    });
}



