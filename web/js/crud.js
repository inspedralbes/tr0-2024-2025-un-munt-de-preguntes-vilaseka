let data = [];

// Funció per mostrar el llistat de preguntes
function mostrarPreguntes() {
    const adminDiv = document.getElementById('admin');
    adminDiv.innerHTML = ''; // Reiniciar el div
    if (data.length > 0) {
        let htmlString = '<h2>Llistat de Preguntes</h2>';
        htmlString += '<table style="border-collapse: collapse; width: 100%;">';
        htmlString += `
            <thead>
                <tr>
                    <th style="border: 1px solid #ccc; padding: 8px;">Pregunta</th>
                    <th style="border: 1px solid #ccc; padding: 8px;">Respostes</th>
                    <th style="border: 1px solid #ccc; padding: 8px;">Accions</th>
                </tr>
            </thead>
            <tbody>
        `;

        data.forEach((pregunta) => {
            htmlString += `
                <tr>
                    <td style="border: 1px solid #ccc; padding: 8px;">${pregunta.pregunta}</td>
                    <td style="border: 1px solid #ccc; padding: 8px;">
                        1. ${pregunta.r1} <br>
                        2. ${pregunta.r2} <br>
                        3. ${pregunta.r3} <br>
                        4. ${pregunta.r4}
                    </td>
                    <td style="border: 1px solid #ccc; padding: 8px;">
                        <button onclick="editarPregunta(${pregunta.id})">Editar</button>
                        <button onclick="eliminarPregunta(${pregunta.id})">Eliminar</button>
                    </td>
                </tr>
            `;
        });

        htmlString += `
            </tbody>
            </table>
        `;
        adminDiv.innerHTML = htmlString; // Injectar el HTML
    } else {
        adminDiv.innerHTML = '<p>No hi ha preguntes disponibles.</p>'; // Mensaje si no hay preguntas
    }
}

// Funció per editar preguntes
function editarPregunta(id) {
    console.log(data);
    const preguntaIndex = data.findIndex(p => p.id === id);
    const pregunta = data[preguntaIndex];
    // Crear un formulari per editar la pregunta
    let htmlString = `
        <h3>Editar Pregunta</h3>
        <input type="text" id="novaPregunta" value="${pregunta.pregunta}"><br>
        <label>Respostes:</label><br>
        <input type="text" id="novaResposta0" value="${pregunta.r1}"><br>
        <input type="text" id="novaResposta1" value="${pregunta.r2}"><br>
        <input type="text" id="novaResposta2" value="${pregunta.r3}"><br>
        <input type="text" id="novaResposta3" value="${pregunta.r4}"><br>
        <br>
        <label>Resposta Correcta (0-3):</label>
        <input type="number" id="respostaCorrecta" min="0" max="3" value="${pregunta.resposta_correcta}"><br>
        <button onclick="guardarActualitzacio(${id})">Guardar Canvis</button>
        <button onclick="mostrarPreguntes()">Cancel·lar</button>
    `;
    document.getElementById('admin').innerHTML = htmlString; // Mostrar el formulari d'edició
}

function guardarActualitzacio(id) {
    const novaPregunta = document.getElementById('novaPregunta').value.trim();
    const novesRespostes = [];

    for (let i = 0; i < 4; i++) {
        novesRespostes.push(document.getElementById(`novaResposta${i}`).value.trim());
    }
    const respostaCorrecta = parseInt(document.getElementById('respostaCorrecta').value, 10);

    // Comprova que les dades són vàlides
    if (!novaPregunta || novesRespostes.some(resposta => resposta === '') || isNaN(respostaCorrecta)) {
        alert('Totes les dades són requerides.');
        return; // Sortir de la funció si falten dades
    }

    const actualitzacioData = {
        id: id,
        pregunta: novaPregunta,
        respostes: novesRespostes,
        resposta_correcta: respostaCorrecta
    };

    // Enviar petició per actualitzar la pregunta
    fetch('../back/actualitzarPregunta.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(actualitzacioData) // Convertir l'objecte a JSON
    })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                alert('Pregunta actualitzada amb èxit!');
                carregarPreguntes(); // Tornar a carregar les preguntes
            } else {
                alert('Error en actualitzar la pregunta: ' + result.message);
            }
        })
        .catch(error => {
            console.error('Error:', error); // Gestionar errors
            alert('Error en actualitzar la pregunta');
        });
}

// Funció per eliminar preguntes
function eliminarPregunta(id) {
    const confirmacion = confirm("Estàs segur que vols eliminar aquesta pregunta?");
    if (confirmacion) {
        fetch(`../back/eliminarPregunta.php?id=${id}`, {
            method: 'DELETE'
        })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    alert('Pregunta eliminada amb èxit!');
                    carregarPreguntes(); // Tornar a carregar les preguntes
                } else {
                    alert('Error en eliminar la pregunta: ' + result.message);
                }
            })
            .catch(error => {
                console.error('Error:', error); // Gestionar errors
                alert('Error en eliminar la pregunta');
            });
    }
}
// Funció per afegir preguntes
function mostrarFormulariAfegir() {
    const adminDiv = document.getElementById('admin');
    let htmlString = `
        <h3>Afegir Pregunta</h3>
        <input type="text" id="novaPregunta" placeholder="Nova Pregunta"><br>
        <label>Respostes:</label><br>
        <input type="text" id="novaResposta0" placeholder="Resposta 1"><br>
        <input type="text" id="novaResposta1" placeholder="Resposta 2"><br>
        <input type="text" id="novaResposta2" placeholder="Resposta 3"><br>
        <input type="text" id="novaResposta3" placeholder="Resposta 4"><br>
        <br>
        <label>Resposta Correcta (0-3):</label>
        <input type="number" id="respostaCorrecta" min="0" max="3"><br>
        <button onclick="afegirPregunta()">Guardar Pregunta</button>
        <button onclick="mostrarPreguntes()">Cancel·lar</button>
    `;
    adminDiv.innerHTML = htmlString; // Mostrar el formulari d'afegir pregunta
}

// Funció per afegir preguntes
function afegirPregunta() {
    const novaPregunta = document.getElementById('novaPregunta').value.trim();
    const novesRespostes = [];

    for (let i = 0; i < 4; i++) {
        novesRespostes.push(document.getElementById(`novaResposta${i}`).value.trim());
    }
    const respostaCorrecta = parseInt(document.getElementById('respostaCorrecta').value, 10);

    // Comprova que les dades són vàlides
    if (!novaPregunta || novesRespostes.some(resposta => resposta === '') || isNaN(respostaCorrecta)) {
        alert('Totes les dades són requerides.');
        return; // Sortir de la funció si falten dades
    }



    const novaPreguntaData = {
        pregunta: novaPregunta,
        respostes: novesRespostes,
        resposta_correcta: respostaCorrecta
    };

    // Enviar petició per afegir la pregunta
    fetch('../back/crearPregunta.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(novaPreguntaData) // Convertir l'objecte a JSON
    })
        .then(response => response.json())
        .then(result => {
            console.log(result);
            if (result.success) {
                alert('Pregunta afegida amb èxit!');
                carregarPreguntes(); // Tornar a carregar les preguntes
                document.getElementById('afegirBtn').onclick = mostrarFormulariAfegir; // Assignar l'acció al botó
            } else {
                alert('Error en afegir la pregunta: ' + result.message);
            }
        })
        .catch(error => {
            console.error('Error:', error); // Gestionar errors
            alert('Error en afegir la pregunta');
        });
}


// Funció per carregar i mostrar preguntes al iniciar
function carregarPreguntes() {
    fetch('../back/getPreguntes.php')
        .then(response => response.json())
        .then(dades => {
            data = dades; // Asegúrate de acceder a la estructura correcta del JSON
            mostrarPreguntes(); // Mostrar la lista de preguntes
        })
}

// Cargar preguntas al iniciar
document.addEventListener('DOMContentLoaded', () => {
    carregarPreguntes(); // Carregar preguntes al iniciar
    document.getElementById('afegirBtn').onclick = mostrarFormulariAfegir; // Assignar l'acció al botó
});