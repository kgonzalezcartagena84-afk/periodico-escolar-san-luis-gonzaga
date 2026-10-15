/* =========================================================
   PRINCIPAL.JS
   PERIÓDICO ESCOLAR "EXPRESIONES"
   JavaScript principal de la página
   ========================================================= */

"use strict";


/* =========================================================
   INICIALIZACIÓN
   ========================================================= */

document.addEventListener("DOMContentLoaded", () => {

    inicializarMenuMovil();

    inicializarCarrusel();

    inicializarNavegacion();

    inicializarBuscador();

    inicializarAnimaciones();

    inicializarBotones();

});


/* =========================================================
   MENÚ MÓVIL
   ========================================================= */

function inicializarMenuMovil() {

    const botonMenu =
        document.querySelector(".mobile-menu-btn");

    const navLinks =
        document.querySelector(".nav-links");

    if (!botonMenu || !navLinks) {
        return;
    }


    botonMenu.addEventListener("click", () => {

        const abierto =
            navLinks.classList.toggle("mobile-open");

        botonMenu.setAttribute(
            "aria-expanded",
            abierto ? "true" : "false"
        );

    });


    /* Cerrar menú al seleccionar una opción */

    navLinks
        .querySelectorAll("a")
        .forEach((enlace) => {

            enlace.addEventListener(
                "click",
                () => {

                    navLinks.classList.remove(
                        "mobile-open"
                    );

                    botonMenu.setAttribute(
                        "aria-expanded",
                        "false"
                    );

                }
            );

        });


    /* Cerrar si se hace clic fuera */

    document.addEventListener(
        "click",
        (evento) => {

            if (
                !navLinks.contains(evento.target) &&
                !botonMenu.contains(evento.target)
            ) {

                navLinks.classList.remove(
                    "mobile-open"
                );

                botonMenu.setAttribute(
                    "aria-expanded",
                    "false"
                );
            }

        }
    );

}


/* =========================================================
   CARRUSEL
   ========================================================= */

function inicializarCarrusel() {

    const track =
        document.querySelector(".carousel-track");

    const slides =
        document.querySelectorAll(".carousel-slide");

    const botonAnterior =
        document.querySelector(".carousel-prev");

    const botonSiguiente =
        document.querySelector(".carousel-next");

    const indicadores =
        document.querySelector(".carousel-dots");


    if (
        !track ||
        slides.length === 0
    ) {
        return;
    }


    let indiceActual = 0;

    let intervaloCarrusel = null;

    let pausaTemporal = false;


    /* -----------------------------------------------------
       CREAR INDICADORES
       ----------------------------------------------------- */

    if (
        indicadores &&
        indicadores.children.length === 0
    ) {

        slides.forEach(
            (_, indice) => {

                const boton =
                    document.createElement("button");

                boton.type = "button";

                boton.className =
                    "carousel-dot";

                boton.setAttribute(
                    "aria-label",
                    `Ir a la diapositiva ${indice + 1}`
                );

                boton.addEventListener(
                    "click",
                    () => {

                        irASlide(indice);

                        reiniciarIntervalo();

                    }
                );

                indicadores.appendChild(
                    boton
                );

            }
        );

    }


    const puntos =
        indicadores
            ? indicadores.querySelectorAll(
                ".carousel-dot"
            )
            : [];


    /* -----------------------------------------------------
       ACTUALIZAR CARRUSEL
       ----------------------------------------------------- */

    function actualizarCarrusel() {

        track.style.transform =
            `translateX(-${indiceActual * 100}%)`;


        puntos.forEach(
            (punto, indice) => {

                punto.classList.toggle(
                    "active",
                    indice === indiceActual
                );

                punto.setAttribute(
                    "aria-current",
                    indice === indiceActual
                        ? "true"
                        : "false"
                );

            }
        );

    }


    /* -----------------------------------------------------
       IR A UNA DIAPOSITIVA
       ----------------------------------------------------- */

    function irASlide(indice) {

        if (indice < 0) {

            indice =
                slides.length - 1;

        }

        if (
            indice >= slides.length
        ) {

            indice = 0;

        }

        indiceActual = indice;

        actualizarCarrusel();

    }


    /* -----------------------------------------------------
       SIGUIENTE
       ----------------------------------------------------- */

    function siguiente() {

        irASlide(
            indiceActual + 1
        );

    }


    /* -----------------------------------------------------
       ANTERIOR
       ----------------------------------------------------- */

    function anterior() {

        irASlide(
            indiceActual - 1
        );

    }


    /* -----------------------------------------------------
       EVENTOS DE BOTONES
       ----------------------------------------------------- */

    if (botonSiguiente) {

        botonSiguiente.addEventListener(
            "click",
            () => {

                siguiente();

                reiniciarIntervalo();

            }
        );

    }


    if (botonAnterior) {

        botonAnterior.addEventListener(
            "click",
            () => {

                anterior();

                reiniciarIntervalo();

            }
        );

    }


    /* -----------------------------------------------------
       CAMBIO AUTOMÁTICO
       ----------------------------------------------------- */

    function iniciarIntervalo() {

        if (intervaloCarrusel) {
            clearInterval(
                intervaloCarrusel
            );
        }

        intervaloCarrusel =
            setInterval(
                () => {

                    if (!pausaTemporal) {

                        siguiente();

                    }

                },
                5000
            );

    }


    function detenerIntervalo() {

        if (intervaloCarrusel) {

            clearInterval(
                intervaloCarrusel
            );

            intervaloCarrusel = null;

        }

    }


    function reiniciarIntervalo() {

        detenerIntervalo();

        iniciarIntervalo();

    }


    /* -----------------------------------------------------
       PAUSAR AL PASAR EL MOUSE
       ----------------------------------------------------- */

    const contenedor =
        document.querySelector(
            ".carousel-card"
        );


    if (contenedor) {

        contenedor.addEventListener(
            "mouseenter",
            () => {

                pausaTemporal = true;

            }
        );


        contenedor.addEventListener(
            "mouseleave",
            () => {

                pausaTemporal = false;

            }
        );


        /* Para dispositivos táctiles */

        contenedor.addEventListener(
            "touchstart",
            () => {

                pausaTemporal = true;

            },
            {
                passive: true
            }
        );


        contenedor.addEventListener(
            "touchend",
            () => {

                setTimeout(
                    () => {

                        pausaTemporal = false;

                    },
                    1500
                );

            },
            {
                passive: true
            }
        );

    }


    /* -----------------------------------------------------
       SOPORTE PARA TECLADO
       ----------------------------------------------------- */

    document.addEventListener(
        "keydown",
        (evento) => {

            if (
                evento.key === "ArrowRight"
            ) {

                siguiente();

                reiniciarIntervalo();

            }

            if (
                evento.key === "ArrowLeft"
            ) {

                anterior();

                reiniciarIntervalo();

            }

        }
    );


    /* -----------------------------------------------------
       SOPORTE TOUCH / SWIPE
       ----------------------------------------------------- */

    let inicioTouchX = 0;

    let finalTouchX = 0;


    if (contenedor) {

        contenedor.addEventListener(
            "touchstart",
            (evento) => {

                inicioTouchX =
                    evento.changedTouches[0].screenX;

            },
            {
                passive: true
            }
        );


        contenedor.addEventListener(
            "touchend",
            (evento) => {

                finalTouchX =
                    evento.changedTouches[0].screenX;

                procesarSwipe();

            },
            {
                passive: true
            }
        );

    }


    function procesarSwipe() {

        const diferencia =
            finalTouchX - inicioTouchX;


        if (
            Math.abs(diferencia) < 50
        ) {

            return;

        }


        if (diferencia < 0) {

            siguiente();

        } else {

            anterior();

        }

        reiniciarIntervalo();

    }


    /* -----------------------------------------------------
       INICIALIZAR
       ----------------------------------------------------- */

    actualizarCarrusel();

    iniciarIntervalo();

}


/* =========================================================
   NAVEGACIÓN PRINCIPAL
   ========================================================= */

function inicializarNavegacion() {

    const enlaces =
        document.querySelectorAll(
            '.nav-link[href^="#"]'
        );


    if (enlaces.length === 0) {
        return;
    }


    enlaces.forEach(
        (enlace) => {

            enlace.addEventListener(
                "click",
                (evento) => {

                    const destino =
                        enlace.getAttribute(
                            "href"
                        );


                    if (
                        !destino ||
                        destino === "#"
                    ) {

                        return;

                    }


                    const elemento =
                        document.querySelector(
                            destino
                        );


                    if (!elemento) {
                        return;
                    }


                    evento.preventDefault();


                    const alturaHeader =
                        obtenerAlturaCabecera();


                    const posicion =
                        elemento.getBoundingClientRect()
                            .top
                        +
                        window.scrollY
                        -
                        alturaHeader
                        -
                        15;


                    window.scrollTo({

                        top:
                            Math.max(
                                posicion,
                                0
                            ),

                        behavior:
                            "smooth"

                    });


                    activarEnlace(
                        enlace
                    );

                }
            );

        }
    );


    /* -----------------------------------------------------
       ACTUALIZAR NAVEGACIÓN SEGÚN SCROLL
       ----------------------------------------------------- */

    const secciones =
        document.querySelectorAll(
            "section[id]"
        );


    if (secciones.length === 0) {
        return;
    }


    let ultimaSeccion = "";


    function actualizarSeccionActiva() {

        const scrollActual =
            window.scrollY +
            obtenerAlturaCabecera() +
            80;


        let seccionEncontrada = "";


        secciones.forEach(
            (seccion) => {

                if (
                    scrollActual >=
                    seccion.offsetTop
                ) {

                    seccionEncontrada =
                        seccion.id;

                }

            }
        );


        if (
            seccionEncontrada &&
            seccionEncontrada !==
                ultimaSeccion
        ) {

            ultimaSeccion =
                seccionEncontrada;


            enlaces.forEach(
                (enlace) => {

                    const href =
                        enlace.getAttribute(
                            "href"
                        );

                    enlace.classList.toggle(
                        "active",
                        href ===
                            `#${seccionEncontrada}`
                    );

                }
            );

        }

    }


    window.addEventListener(
        "scroll",
        actualizarSeccionActiva,
        {
            passive: true
        }
    );


    actualizarSeccionActiva();

}


/* =========================================================
   ACTIVAR ENLACE
   ========================================================= */

function activarEnlace(enlaceActivo) {

    document
        .querySelectorAll(".nav-link")
        .forEach(
            (enlace) => {

                enlace.classList.remove(
                    "active"
                );

            }
        );


    enlaceActivo.classList.add(
        "active"
    );

}


/* =========================================================
   ALTURA DE CABECERA
   ========================================================= */

function obtenerAlturaCabecera() {

    let altura = 0;


    const topbar =
        document.querySelector(
            ".topbar"
        );


    const nav =
        document.querySelector(
            ".main-nav"
        );


    if (topbar) {

        altura +=
            topbar.offsetHeight;

    }


    if (nav) {

        altura +=
            nav.offsetHeight;

    }


    return altura;

}


/* =========================================================
   BUSCADOR
   ========================================================= */

function inicializarBuscador() {

    const buscador =
        document.querySelector(
            ".search-input"
        );


    if (!buscador) {
        return;
    }


    buscador.addEventListener(
        "keydown",
        (evento) => {

            if (
                evento.key === "Enter"
            ) {

                evento.preventDefault();

                realizarBusqueda(
                    buscador.value
                );

            }

        }
    );


    buscador.addEventListener(
        "input",
        () => {

            const texto =
                buscador.value.trim();


            if (
                texto.length === 0
            ) {

                limpiarResultadosBusqueda();

            }

        }
    );

}


/* =========================================================
   REALIZAR BÚSQUEDA
   ========================================================= */

function realizarBusqueda(texto) {

    const termino =
        texto
            .trim()
            .toLowerCase();


    if (!termino) {
        return;
    }


    const elementos =
        document.querySelectorAll(
            "main h1, main h2, main h3, main p"
        );


    if (elementos.length === 0) {
        return;
    }


    let encontrado = null;


    elementos.forEach(
        (elemento) => {

            if (encontrado) {
                return;
            }


            const contenido =
                elemento.textContent
                    .toLowerCase();


            if (
                contenido.includes(
                    termino
                )
            ) {

                encontrado =
                    elemento;

            }

        }
    );


    if (encontrado) {

        limpiarResultadosBusqueda();


        encontrado.classList.add(
            "search-highlight"
        );


        encontrado.scrollIntoView({

            behavior: "smooth",

            block: "center"

        });


        setTimeout(
            () => {

                encontrado.classList.remove(
                    "search-highlight"
                );

            },
            2500
        );


    } else {

        mostrarMensajeBusqueda(
            "No encontramos información relacionada con tu búsqueda."
        );

    }

}


/* =========================================================
   LIMPIAR RESULTADOS
   ========================================================= */

function limpiarResultadosBusqueda() {

    document
        .querySelectorAll(
            ".search-highlight"
        )
        .forEach(
            (elemento) => {

                elemento.classList.remove(
                    "search-highlight"
                );

            }
        );

}


/* =========================================================
   MENSAJE DE BÚSQUEDA
   ========================================================= */

function mostrarMensajeBusqueda(mensaje) {

    let aviso =
        document.querySelector(
            ".search-message"
        );


    if (!aviso) {

        aviso =
            document.createElement(
                "div"
            );

        aviso.className =
            "search-message";

        document.body.appendChild(
            aviso
        );

    }


    aviso.textContent =
        mensaje;


    aviso.classList.add(
        "show"
    );


    setTimeout(
        () => {

            aviso.classList.remove(
                "show"
            );

        },
        2500
    );

}


/* =========================================================
   ANIMACIONES AL HACER SCROLL
   ========================================================= */

function inicializarAnimaciones() {

    const elementos =
        document.querySelectorAll(
            ".feature-card, .hero-content, .hero-media, .footer-grid"
        );


    if (
        elementos.length === 0
    ) {

        return;

    }


    elementos.forEach(
        (elemento) => {

            elemento.classList.add(
                "js-animate"
            );

        }
    );


    if (
        !("IntersectionObserver" in window)
    ) {

        elementos.forEach(
            (elemento) => {

                elemento.classList.add(
                    "is-visible"
                );

            }
        );

        return;

    }


    const observer =
        new IntersectionObserver(
            (entradas) => {

                entradas.forEach(
                    (entrada) => {

                        if (
                            entrada.isIntersecting
                        ) {

                            entrada.target.classList.add(
                                "is-visible"
                            );


                            observer.unobserve(
                                entrada.target
                            );

                        }

                    }
                );

            },
            {
                threshold: 0.12
            }
        );


    elementos.forEach(
        (elemento) => {

            observer.observe(
                elemento
            );

        }
    );

}


/* =========================================================
   BOTONES GENERALES
   ========================================================= */

function inicializarBotones() {

    const botones =
        document.querySelectorAll(
            "[data-scroll]"
        );


    botones.forEach(
        (boton) => {

            boton.addEventListener(
                "click",
                () => {

                    const destino =
                        boton.dataset.scroll;


                    if (!destino) {
                        return;
                    }


                    const elemento =
                        document.querySelector(
                            destino
                        );


                    if (!elemento) {
                        return;
                    }


                    const posicion =
                        elemento.getBoundingClientRect()
                            .top
                        +
                        window.scrollY
                        -
                        obtenerAlturaCabecera()
                        -
                        15;


                    window.scrollTo({

                        top:
                            Math.max(
                                posicion,
                                0
                            ),

                        behavior:
                            "smooth"

                    });

                }
            );

        }
    );

}


/* =========================================================
   BOTÓN "CONOCE MÁS"
   ========================================================= */

document.addEventListener(
    "click",
    (evento) => {

        const boton =
            evento.target.closest(
                ".primary-btn"
            );


        if (!boton) {
            return;
        }


        /*
         * Si el botón ya tiene un enlace,
         * no interferimos.
         */

        if (
            boton.tagName === "A" &&
            boton.getAttribute("href")
        ) {

            return;

        }


        const destino =
            document.querySelector(
                "#caracteristicas, #features, #noticias, #contenido"
            );


        if (!destino) {
            return;
        }


        evento.preventDefault();


        const posicion =
            destino.getBoundingClientRect()
                .top
            +
            window.scrollY
            -
            obtenerAlturaCabecera()
            -
            15;


        window.scrollTo({

            top:
                Math.max(
                    posicion,
                    0
                ),

            behavior:
                "smooth"

        });

    }
);


/* =========================================================
   CERRAR MENÚ CON ESC
   ========================================================= */

document.addEventListener(
    "keydown",
    (evento) => {

        if (
            evento.key !== "Escape"
        ) {

            return;

        }


        const navLinks =
            document.querySelector(
                ".nav-links"
            );


        const botonMenu =
            document.querySelector(
                ".mobile-menu-btn"
            );


        if (navLinks) {

            navLinks.classList.remove(
                "mobile-open"
            );

        }


        if (botonMenu) {

            botonMenu.setAttribute(
                "aria-expanded",
                "false"
            );

        }

    }
);


/* =========================================================
   DETECCIÓN DE CAMBIO DE TAMAÑO
   ========================================================= */

let temporizadorResize = null;


window.addEventListener(
    "resize",
    () => {

        clearTimeout(
            temporizadorResize
        );


        temporizadorResize =
            setTimeout(
                () => {

                    /* 
                     * Se fuerza el navegador a
                     * recalcular correctamente
                     * los elementos responsive.
                     */

                    document
                        .documentElement
                        .style
                        .setProperty(
                            "--viewport-height",
                            `${window.innerHeight}px`
                        );

                },
                150
            );

    }
);


/* =========================================================
   ALTURA INICIAL DEL VIEWPORT
   ========================================================= */

document
    .documentElement
    .style
    .setProperty(
        "--viewport-height",
        `${window.innerHeight}px`
    );


/* =========================================================
   DETECCIÓN DE IMÁGENES
   ========================================================= */

document
    .querySelectorAll("img")
    .forEach(
        (imagen) => {

            imagen.addEventListener(
                "error",
                () => {

                    imagen.classList.add(
                        "image-error"
                    );

                }
            );

        }
    );


/* =========================================================
   UTILIDAD GLOBAL
   ========================================================= */

/*
 * Permite que otros scripts puedan
 * desplazar la página sin duplicar código.
 */

window.scrollToSection =
    function (selector) {

        const elemento =
            document.querySelector(
                selector
            );


        if (!elemento) {
            return false;
        }


        const posicion =
            elemento.getBoundingClientRect()
                .top
            +
            window.scrollY
            -
            obtenerAlturaCabecera()
            -
            15;


        window.scrollTo({

            top:
                Math.max(
                    posicion,
                    0
                ),

            behavior:
                "smooth"

        });


        return true;

    };


/* =========================================================
   FIN PRINCIPAL.JS
   ========================================================= */