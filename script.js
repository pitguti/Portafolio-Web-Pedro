
/*Esta funcionalidad sirve para el menu de navegacion */
let menuIcon = document.querySelector('#menu-icon');
let navbar = document.querySelector('.navbar');
let sections = document.querySelectorAll('section');
let navLinks = document.querySelectorAll('header nav a');

window.onscroll = () =>{
    sections.forEach(sec => {
        let top = window.scrollY ;
        let offset = sec.offsetTop - 150;
        let height = sec.offsetHeight;
        let id = sec.getAttribute('id');
        
        if(top >= offset && top < offset + height){
            navLinks.forEach(links => {
                links.classList.remove('active');
                document.querySelector('header nav a[href="#' + id + ' ]').classList.add('active')
            })
        }
    })
}
 
menuIcon.onclick = () =>{
    menuIcon.classList.toggle('bx-x');
    navbar.classList.toggle('active');
}


/*bloque de codigo para el modal que se va a mostrar una vez enviado el mensaje al correo */

document.getElementById('miFormulario').addEventListener('submit', function (event) {
    event.preventDefault();

    let formData = new FormData(this);

    fetch('enviar_formulario.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            Swal.fire({
                position: "center",
                icon: "success",
                title: data.message,
                showConfirmButton: true,
            }).then(() => {
                // Redirigir a la página de inicio después de que se cierre el modal
                window.location.href = "index.html";
            });
        } else {
            Swal.fire({
                position: "center",
                icon: "error",
                title: "Error",
                text: data.message,
                showConfirmButton: true,
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            position: "center",
            icon: "error",
            title: "Error",
            text: "Ocurrió un error al enviar el formulario.",
            showConfirmButton: true,
        });
    });
});
