var palabrasProhibidas = [];

function openNav() {
    document.getElementById("hidden_sidebar").style.width = "650px";
    document.getElementById("main").style.marginRight = "620px";
}

function closeNav() {
    document.getElementById("hidden_sidebar").style.width = "0px";
    document.getElementById("main").style.marginRight = "0px";

}

const comentar = document.getElementById("comentar");
comentar.addEventListener('click', () => {
   openNav();
});

const cerrar = document.getElementById("cerrar");
cerrar.addEventListener('click', () => {
    closeNav();
 });


function getPalabrasProhibidas() {
    fetch('./palabrasProhibidas.php')
    .then(response => response.json())
    .then(data => {
        palabrasProhibidas = data;
    });
}

// Función encargada de reemplazar palabras prohibidas con * a medida que se escriben
document.getElementById('comentario').addEventListener('input', function(event) { 
    var comentario = this.value;
    getPalabrasProhibidas();
    
    // Reemplazar palabras prohibidas con *
    palabrasProhibidas.forEach(function(word) {
        var regex = new RegExp(word, 'gi');
        comentario = comentario.replace(regex, '*'.repeat(word.length));
    });

    this.value = comentario;
});

