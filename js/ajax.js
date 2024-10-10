document.getElementById('palabrasBuscadas').addEventListener('input', function(event) { 
    hacerPeticionAjax();
});

function hacerPeticionAjax() {
    var palabrasBuscadas = document.getElementById('palabrasBuscadas').value;
    
    $.ajax({
        data: { palabrasBuscadas },
        url: 'buscarActividad.php',
        type: 'get',
        beforeSend: function () {
            $("#mensaje").show();
        },
        success: function(respuesta) {
            $("#mensaje").hide();
            procesaRespuestaAjax(respuesta);
        },
        error: function(xhr, status, error) {
            console.error('Error en la petición AJAX:', status, error);
            $("#mensaje").hide();
        }
    });
}

function procesaRespuestaAjax(respuesta) {
    let actividadesBuscadas = respuesta; 

    let contenedor = document.getElementById('actividades');
    contenedor.innerHTML = ''; // Vaciamos el contenedor cada vez que se hace una petición Ajax
    
    
    for (let actividad of actividadesBuscadas) {
        let figure = document.createElement('figure');
        figure.className = 'actividad';

        let enlaceImagen = document.createElement('a');
        enlaceImagen.href = `actividad.php?actividad=${actividad.id}`;
        let img = document.createElement('img');
        img.src = `img/${actividad.imagen_portada}`;
        enlaceImagen.appendChild(img);

        let enlaceTitulo = document.createElement('a');
        enlaceTitulo.className = 'titulo_act';
        enlaceTitulo.href = `actividad.php?actividad=${actividad.id}`;
        enlaceTitulo.textContent = actividad.nombre;    

        figure.appendChild(enlaceImagen);
        figure.appendChild(enlaceTitulo);


        contenedor.appendChild(figure);
    }
}
