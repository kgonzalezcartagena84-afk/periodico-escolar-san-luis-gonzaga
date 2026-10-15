/* =========================================================
   CHATBOT.JS
   ASISTENTE VIRTUAL "CIRO"
   PERIÓDICO ESCOLAR EXPRESIONES
   ========================================================= */

"use strict";


/* =========================================================
   CONFIGURACIÓN
   ========================================================= */

const CHATBOT_CONFIG = {

    /* Archivo PHP que procesa las preguntas */
    endpoint: "php/chatbot.php",

    /* Tiempo máximo de espera */
    timeout: 15000,

    /* Mostrar saludo automáticamente */
    mostrarBienvenida: true,

    /* Tiempo para mostrar el saludo */
    tiempoBienvenida: 1800,

    /* Guardar conversación durante la sesión */
    guardarConversacion: true,

    /* Nombre del asistente */
    nombre: "Ciro"

};


/* =========================================================
   VARIABLES
   ========================================================= */

let chatbotAbierto = false;

let chatbotInicializado = false;

let solicitudEnCurso = false;

let temporizadorBienvenida = null;


/* =========================================================
   INICIALIZACIÓN
   ========================================================= */

document.addEventListener(
    "DOMContentLoaded",
    () => {

        inicializarChatbot();

    }
);


/* =========================================================
   INICIALIZAR CHATBOT
   ========================================================= */

function inicializarChatbot() {

    if (chatbotInicializado) {
        return;
    }


    const botonToggle =
        document.querySelector(
            "#chatbot-toggle"
        );

    const chatbot =
        document.querySelector(
            "#chatbot"
        );

    const botonCerrar =
        document.querySelector(
            "#cerrarChat"
        );

    const botonEnviar =
        document.querySelector(
            "#enviar"
        );

    const campoMensaje =
        document.querySelector(
            "#mensaje"
        );

    const cuerpoChat =
        document.querySelector(
            "#chat-body"
        );


    /*
     * Si no existe la estructura del chatbot,
     * no producimos errores en la página.
     */

    if (!chatbot) {

        console.warn(
            "Chatbot: no se encontró #chatbot."
        );

        return;

    }


    chatbotInicializado = true;


    /* -----------------------------------------------------
       BOTÓN FLOTANTE
       ----------------------------------------------------- */

    if (botonToggle) {

        botonToggle.addEventListener(
            "click",
            () => {

                alternarChatbot();

            }
        );

    }


    /* -----------------------------------------------------
       BOTÓN CERRAR
       ----------------------------------------------------- */

    if (botonCerrar) {

        botonCerrar.addEventListener(
            "click",
            () => {

                cerrarChatbot();

            }
        );

    }


    /* -----------------------------------------------------
       BOTÓN ENVIAR
       ----------------------------------------------------- */

    if (botonEnviar) {

        botonEnviar.addEventListener(
            "click",
            () => {

                enviarMensaje();

            }
        );

    }


    /* -----------------------------------------------------
       ENTER EN EL CAMPO
       ----------------------------------------------------- */

    if (campoMensaje) {

        campoMensaje.addEventListener(
            "keydown",
            (evento) => {

                /*
                 * Enter envía.
                 * Shift + Enter permite salto de línea.
                 */

                if (
                    evento.key === "Enter" &&
                    !evento.shiftKey
                ) {

                    evento.preventDefault();

                    enviarMensaje();

                }

            }
        );


        /* -------------------------------------------------
           Limpiar espacios innecesarios
        ------------------------------------------------- */

        campoMensaje.addEventListener(
            "input",
            () => {

                campoMensaje.value =
                    campoMensaje.value
                        .replace(/\r/g, "");

            }
        );

    }


    /* -----------------------------------------------------
       BOTONES RÁPIDOS
       ----------------------------------------------------- */

    inicializarBotonesRapidos();


    /* -----------------------------------------------------
       CLIC FUERA DEL CHAT
       ----------------------------------------------------- */

    document.addEventListener(
        "click",
        (evento) => {

            if (!chatbotAbierto) {
                return;
            }


            const boton =
                document.querySelector(
                    "#chatbot-toggle"
                );


            if (
                chatbot.contains(
                    evento.target
                )
            ) {

                return;

            }


            if (
                boton &&
                boton.contains(
                    evento.target
                )
            ) {

                return;

            }


            /*
             * No cerramos automáticamente.
             * El usuario puede dejar el chat abierto.
             */

        }
    );


    /* -----------------------------------------------------
       TECLA ESC
       ----------------------------------------------------- */

    document.addEventListener(
        "keydown",
        (evento) => {

            if (
                evento.key === "Escape" &&
                chatbotAbierto
            ) {

                cerrarChatbot();

            }

        }
    );


    /* -----------------------------------------------------
       RECUPERAR CONVERSACIÓN
       ----------------------------------------------------- */

    if (
        CHATBOT_CONFIG.guardarConversacion
    ) {

        restaurarConversacion();

    }


    /* -----------------------------------------------------
       BIENVENIDA
       ----------------------------------------------------- */

    if (
        CHATBOT_CONFIG.mostrarBienvenida
    ) {

        mostrarBienvenida();

    }


    /*
     * Indicamos que el chatbot está listo.
     */

    document.dispatchEvent(
        new CustomEvent(
            "chatbotReady"
        )
    );

}


/* =========================================================
   ABRIR / CERRAR CHATBOT
   ========================================================= */

function alternarChatbot() {

    if (chatbotAbierto) {

        cerrarChatbot();

    } else {

        abrirChatbot();

    }

}


/* =========================================================
   ABRIR CHATBOT
   ========================================================= */

function abrirChatbot() {

    const chatbot =
        document.querySelector(
            "#chatbot"
        );

    const botonToggle =
        document.querySelector(
            "#chatbot-toggle"
        );

    const bienvenida =
        document.querySelector(
            "#chatbot-bienvenida"
        );


    if (!chatbot) {
        return;
    }


    chatbotAbierto = true;


    chatbot.classList.add(
        "chatbot-visible"
    );


    chatbot.classList.remove(
        "chatbot-hidden"
    );


    if (bienvenida) {

        bienvenida.style.display =
            "none";

        bienvenida.classList.remove(
            "welcome-visible"
        );

    }


    if (botonToggle) {

        botonToggle.setAttribute(
            "aria-expanded",
            "true"
        );

        botonToggle.setAttribute(
            "aria-label",
            "Cerrar asistente virtual"
        );

    }


    /*
     * Llevar el scroll del chat al final.
     */

    desplazarChatAlFinal();


    /*
     * Enfocar el campo de mensaje.
     */

    const campoMensaje =
        document.querySelector(
            "#mensaje"
        );


    if (campoMensaje) {

        setTimeout(
            () => {

                campoMensaje.focus();

            },
            250
        );

    }

}


/* =========================================================
   CERRAR CHATBOT
   ========================================================= */

function cerrarChatbot() {

    const chatbot =
        document.querySelector(
            "#chatbot"
        );

    const botonToggle =
        document.querySelector(
            "#chatbot-toggle"
        );


    if (!chatbot) {
        return;
    }


    chatbotAbierto = false;


    chatbot.classList.remove(
        "chatbot-visible"
    );


    chatbot.classList.add(
        "chatbot-hidden"
    );


    if (botonToggle) {

        botonToggle.setAttribute(
            "aria-expanded",
            "false"
        );

        botonToggle.setAttribute(
            "aria-label",
            "Abrir asistente virtual"
        );

    }

}


/* =========================================================
   BIENVENIDA
   ========================================================= */

function mostrarBienvenida() {

    const bienvenida =
        document.querySelector(
            "#chatbot-bienvenida"
        );


    if (!bienvenida) {
        return;
    }


    /*
     * No mostrar si ya existe una conversación.
     */

    if (
        CHATBOT_CONFIG.guardarConversacion &&
        obtenerConversacion().length > 0
    ) {

        return;

    }


    clearTimeout(
        temporizadorBienvenida
    );


    temporizadorBienvenida =
        setTimeout(
            () => {

                if (chatbotAbierto) {
                    return;
                }


                bienvenida.style.display =
                    "block";


                bienvenida.classList.add(
                    "welcome-visible"
                );


            },
            CHATBOT_CONFIG.tiempoBienvenida
        );

}


/* =========================================================
   OCULTAR BIENVENIDA
   ========================================================= */

function ocultarBienvenida() {

    const bienvenida =
        document.querySelector(
            "#chatbot-bienvenida"
        );


    if (!bienvenida) {
        return;
    }


    clearTimeout(
        temporizadorBienvenida
    );


    bienvenida.classList.remove(
        "welcome-visible"
    );


    bienvenida.style.display =
        "none";

}


/* =========================================================
   BOTONES RÁPIDOS
   ========================================================= */

function inicializarBotonesRapidos() {

    document.addEventListener(
        "click",
        (evento) => {

            const boton =
                evento.target.closest(
                    ".quick-button"
                );


            if (!boton) {
                return;
            }


            evento.preventDefault();


            const texto =
                obtenerTextoBotonRapido(
                    boton
                );


            if (!texto) {
                return;
            }


            abrirChatbot();


            enviarTexto(
                texto
            );

        }
    );

}


/* =========================================================
   OBTENER TEXTO DEL BOTÓN
   ========================================================= */

function obtenerTextoBotonRapido(
    boton
) {

    /*
     * Primero buscamos atributos personalizados.
     */

    const atributo =
        boton.dataset.question ||
        boton.dataset.pregunta ||
        boton.dataset.message;


    if (atributo) {

        return atributo.trim();

    }


    /*
     * Si no existe, usamos el texto visible.
     */

    return boton.textContent
        .trim()
        .replace(/\s+/g, " ");

}


/* =========================================================
   ENVIAR MENSAJE
   ========================================================= */

function enviarMensaje() {

    const campoMensaje =
        document.querySelector(
            "#mensaje"
        );


    if (!campoMensaje) {
        return;
    }


    const texto =
        campoMensaje.value.trim();


    if (!texto) {

        campoMensaje.focus();

        return;

    }


    enviarTexto(
        texto
    );

}


/* =========================================================
   ENVIAR TEXTO
   ========================================================= */

async function enviarTexto(
    texto
) {

    if (solicitudEnCurso) {

        /*
         * Evita enviar múltiples consultas
         * simultáneamente.
         */

        return;

    }


    const mensaje =
        limpiarTexto(
            texto
        );


    if (!mensaje) {
        return;
    }


    abrirChatbot();

    ocultarBienvenida();


    const campoMensaje =
        document.querySelector(
            "#mensaje"
        );


    if (campoMensaje) {

        campoMensaje.value = "";

    }


    /*
     * Mostrar mensaje del usuario.
     */

    agregarMensaje(
        mensaje,
        "user"
    );


    guardarMensaje(
        mensaje,
        "user"
    );


    /*
     * Mostrar indicador.
     */

    const indicador =
        mostrarEscribiendo();


    solicitudEnCurso = true;


    actualizarEstadoEnvio(
        true
    );


    try {

        const respuesta =
            await consultarServidor(
                mensaje
            );


        ocultarEscribiendo(
            indicador
        );


        const textoRespuesta =
            obtenerTextoRespuesta(
                respuesta
            );


        agregarMensaje(
            textoRespuesta,
            "bot"
        );


        guardarMensaje(
            textoRespuesta,
            "bot"
        );


    } catch (error) {

        console.error(
            "Error del chatbot:",
            error
        );


        ocultarEscribiendo(
            indicador
        );


        const mensajeError =
            obtenerMensajeError(
                error
            );


        agregarMensaje(
            mensajeError,
            "bot"
        );


        guardarMensaje(
            mensajeError,
            "bot"
        );


    } finally {

        solicitudEnCurso = false;

        actualizarEstadoEnvio(
            false
        );


        if (campoMensaje) {

            campoMensaje.focus();

        }

    }

}


/* =========================================================
   CONSULTAR CHATBOT.PHP
   ========================================================= */

async function consultarServidor(
    mensaje
) {

    const controlador =
        new AbortController();


    const temporizador =
        setTimeout(
            () => {

                controlador.abort();

            },
            CHATBOT_CONFIG.timeout
        );


    try {

        const respuesta =
            await fetch(
                CHATBOT_CONFIG.endpoint,
                {
                    method: "POST",

                    headers: {
                        "Content-Type":
                            "application/json",

                        "Accept":
                            "application/json",

                        "X-Requested-With":
                            "XMLHttpRequest"
                    },

                    body:
                        JSON.stringify({

                            mensaje:
                                mensaje,

                            pregunta:
                                mensaje

                        }),

                    signal:
                        controlador.signal

                }
            );


        clearTimeout(
            temporizador
        );


        if (!respuesta.ok) {

            throw new Error(
                `HTTP ${respuesta.status}`
            );

        }


        const texto =
            await respuesta.text();


        if (!texto) {

            throw new Error(
                "El servidor no devolvió información."
            );

        }


        let datos;


        try {

            datos =
                JSON.parse(
                    texto
                );

        } catch (error) {

            /*
             * Si PHP devuelve texto plano,
             * también lo aceptamos.
             */

            datos = {
                respuesta:
                    texto
            };

        }


        return datos;


    } catch (error) {

        clearTimeout(
            temporizador
        );


        throw error;

    }

}


/* =========================================================
   INTERPRETAR RESPUESTA DEL SERVIDOR
   ========================================================= */

function obtenerTextoRespuesta(
    datos
) {

    if (
        datos === null ||
        datos === undefined
    ) {

        return (
            "Lo siento, no recibí una respuesta del servidor."
        );

    }


    /*
     * Si el servidor devuelve directamente
     * una cadena.
     */

    if (
        typeof datos === "string"
    ) {

        return limpiarRespuesta(
            datos
        );

    }


    /*
     * Campos posibles para mantener
     * compatibilidad con diferentes chatbot.php
     */

    const posiblesCampos = [

        "respuesta",

        "response",

        "mensaje",

        "message",

        "answer",

        "respuesta_bot",

        "text",

        "contenido"

    ];


    for (
        const campo of posiblesCampos
    ) {

        if (
            typeof datos[campo] ===
                "string" &&
            datos[campo].trim()
        ) {

            return limpiarRespuesta(
                datos[campo]
            );

        }

    }


    /*
     * Algunos PHP pueden devolver:
     *
     * { data: { respuesta: "..." } }
     */

    if (
        datos.data &&
        typeof datos.data === "object"
    ) {

        return obtenerTextoRespuesta(
            datos.data
        );

    }


    /*
     * Error enviado desde PHP.
     */

    if (
        typeof datos.error === "string" &&
        datos.error.trim()
    ) {

        return (
            "El asistente encontró un problema: " +
            limpiarRespuesta(
                datos.error
            )
        );

    }


    return (
        "No pude interpretar la respuesta del asistente. " +
        "Por favor, intenta nuevamente."
    );

}


/* =========================================================
   LIMPIAR RESPUESTA
   ========================================================= */

function limpiarRespuesta(
    texto
) {

    if (
        typeof texto !== "string"
    ) {

        return "";

    }


    return texto
        .trim()
        .replace(
            /\r\n/g,
            "\n"
        )
        .replace(
            /\r/g,
            "\n"
        );

}


/* =========================================================
   LIMPIAR TEXTO DE USUARIO
   ========================================================= */

function limpiarTexto(
    texto
) {

    if (
        typeof texto !== "string"
    ) {

        return "";

    }


    return texto
        .replace(
            /[\u0000-\u0008\u000B\u000C\u000E-\u001F]/g,
            ""
        )
        .trim()
        .slice(
            0,
            2000
        );

}


/* =========================================================
   AGREGAR MENSAJE
   ========================================================= */

function agregarMensaje(
    texto,
    tipo = "bot"
) {

    const cuerpoChat =
        document.querySelector(
            "#chat-body"
        );


    if (!cuerpoChat) {
        return null;
    }


    const contenedor =
        document.createElement(
            "div"
        );


    contenedor.className =
        `mensaje ${tipo} message-animation`;


    const burbuja =
        document.createElement(
            "div"
        );


    burbuja.className =
        "burbuja";


    /*
     * Usamos textContent para impedir
     * inyección de HTML desde mensajes.
     */

    burbuja.textContent =
        texto;


    contenedor.appendChild(
        burbuja
    );


    cuerpoChat.appendChild(
        contenedor
    );


    desplazarChatAlFinal();


    return contenedor;

}


/* =========================================================
   INDICADOR ESCRIBIENDO
   ========================================================= */

function mostrarEscribiendo() {

    const cuerpoChat =
        document.querySelector(
            "#chat-body"
        );


    if (!cuerpoChat) {
        return null;
    }


    const contenedor =
        document.createElement(
            "div"
        );


    contenedor.className =
        "mensaje bot message-animation";


    const burbuja =
        document.createElement(
            "div"
        );


    burbuja.className =
        "burbuja";


    const indicador =
        document.createElement(
            "div"
        );


    indicador.className =
        "typing-indicator";


    for (
        let i = 0;
        i < 3;
        i++
    ) {

        const punto =
            document.createElement(
                "span"
            );

        indicador.appendChild(
            punto
        );

    }


    burbuja.appendChild(
        indicador
    );


    contenedor.appendChild(
        burbuja
    );


    cuerpoChat.appendChild(
        contenedor
    );


    desplazarChatAlFinal();


    return contenedor;

}


/* =========================================================
   OCULTAR INDICADOR
   ========================================================= */

function ocultarEscribiendo(
    elemento
) {

    if (
        elemento &&
        elemento.parentNode
    ) {

        elemento.parentNode.removeChild(
            elemento
        );

    }

}


/* =========================================================
   DESPLAZAR CHAT AL FINAL
   ========================================================= */

function desplazarChatAlFinal() {

    const cuerpoChat =
        document.querySelector(
            "#chat-body"
        );


    if (!cuerpoChat) {
        return;
    }


    requestAnimationFrame(
        () => {

            cuerpoChat.scrollTop =
                cuerpoChat.scrollHeight;

        }
    );

}


/* =========================================================
   ESTADO DEL BOTÓN ENVIAR
   ========================================================= */

function actualizarEstadoEnvio(
    cargando
) {

    const botonEnviar =
        document.querySelector(
            "#enviar"
        );


    if (!botonEnviar) {
        return;
    }


    botonEnviar.disabled =
        cargando;


    botonEnviar.setAttribute(
        "aria-busy",
        cargando
            ? "true"
            : "false"
    );


    if (cargando) {

        botonEnviar.style.opacity =
            "0.65";

        botonEnviar.style.cursor =
            "wait";

    } else {

        botonEnviar.style.opacity =
            "";

        botonEnviar.style.cursor =
            "";

    }

}


/* =========================================================
   MENSAJE DE ERROR
   ========================================================= */

function obtenerMensajeError(
    error
) {

    if (
        error &&
        error.name ===
            "AbortError"
    ) {

        return (
            "La respuesta está tardando demasiado. " +
            "Por favor, verifica tu conexión e inténtalo nuevamente."
        );

    }


    /*
     * Error de conexión.
     */

    if (
        !navigator.onLine
    ) {

        return (
            "Parece que no tienes conexión a Internet. " +
            "Revisa tu conexión e inténtalo nuevamente."
        );

    }


    return (
        "Lo siento, en este momento no puedo conectarme con el asistente. " +
        "Por favor, inténtalo nuevamente en unos segundos."
    );

}


/* =========================================================
   LOCAL STORAGE
   ========================================================= */

const CHAT_STORAGE_KEY =
    "periodico_expresiones_chat";


/* =========================================================
   OBTENER CONVERSACIÓN
   ========================================================= */

function obtenerConversacion() {

    if (
        !CHATBOT_CONFIG.guardarConversacion
    ) {

        return [];

    }


    try {

        const datos =
            localStorage.getItem(
                CHAT_STORAGE_KEY
            );


        if (!datos) {
            return [];
        }


        const conversacion =
            JSON.parse(
                datos
            );


        if (
            !Array.isArray(
                conversacion
            )
        ) {

            return [];

        }


        return conversacion;

    } catch (error) {

        console.warn(
            "No fue posible recuperar la conversación.",
            error
        );


        return [];

    }

}


/* =========================================================
   GUARDAR MENSAJE
   ========================================================= */

function guardarMensaje(
    texto,
    tipo
) {

    if (
        !CHATBOT_CONFIG.guardarConversacion
    ) {

        return;

    }


    try {

        const conversacion =
            obtenerConversacion();


        conversacion.push({

            texto:
                texto,

            tipo:
                tipo,

            fecha:
                new Date().toISOString()

        });


        /*
         * Conservamos solamente los últimos
         * 50 mensajes.
         */

        const ultimosMensajes =
            conversacion.slice(
                -50
            );


        localStorage.setItem(
            CHAT_STORAGE_KEY,
            JSON.stringify(
                ultimosMensajes
            )
        );


    } catch (error) {

        console.warn(
            "No fue posible guardar la conversación.",
            error
        );

    }

}


/* =========================================================
   RESTAURAR CONVERSACIÓN
   ========================================================= */

function restaurarConversacion() {

    const conversacion =
        obtenerConversacion();


    if (
        conversacion.length === 0
    ) {

        return;

    }


    const cuerpoChat =
        document.querySelector(
            "#chat-body"
        );


    if (!cuerpoChat) {
        return;
    }


    /*
     * Limpiar mensajes que hayan
     * sido creados previamente.
     */

    cuerpoChat.innerHTML = "";


    conversacion.forEach(
        (mensaje) => {

            if (
                !mensaje ||
                !mensaje.texto
            ) {

                return;

            }


            agregarMensaje(
                mensaje.texto,
                mensaje.tipo === "user"
                    ? "user"
                    : "bot"
            );

        }
    );


    desplazarChatAlFinal();

}


/* =========================================================
   BORRAR CONVERSACIÓN
   ========================================================= */

function borrarConversacion() {

    try {

        localStorage.removeItem(
            CHAT_STORAGE_KEY
        );

    } catch (error) {

        console.warn(
            "No fue posible borrar la conversación.",
            error
        );

    }


    const cuerpoChat =
        document.querySelector(
            "#chat-body"
        );


    if (cuerpoChat) {

        cuerpoChat.innerHTML =
            "";

    }


    /*
     * Agregar saludo inicial nuevamente.
     */

    agregarMensaje(
        `¡Hola! Soy ${CHATBOT_CONFIG.nombre}, tu asistente virtual. ¿En qué puedo ayudarte?`,
        "bot"
    );


    guardarMensaje(
        `¡Hola! Soy ${CHATBOT_CONFIG.nombre}, tu asistente virtual. ¿En qué puedo ayudarte?`,
        "bot"
    );

}


/* =========================================================
   API PÚBLICA DEL CHATBOT
   ========================================================= */

window.CiroChatbot = {

    abrir: abrirChatbot,

    cerrar: cerrarChatbot,

    alternar: alternarChatbot,

    enviar: enviarTexto,

    borrarConversacion:
        borrarConversacion,

    limpiar: borrarConversacion,

    estaAbierto: function () {

        return chatbotAbierto;

    }

};


/* =========================================================
   EVENTOS PERSONALIZADOS
   ========================================================= */

/*
 * Permite abrir el chatbot desde cualquier botón
 * que tenga:
 *
 * data-chatbot-open
 */

document.addEventListener(
    "click",
    (evento) => {

        const elemento =
            evento.target.closest(
                "[data-chatbot-open]"
            );


        if (!elemento) {
            return;
        }


        evento.preventDefault();


        abrirChatbot();

    }
);


/*
 * Permite cerrar el chatbot desde cualquier
 * elemento con data-chatbot-close.
 */

document.addEventListener(
    "click",
    (evento) => {

        const elemento =
            evento.target.closest(
                "[data-chatbot-close]"
            );


        if (!elemento) {
            return;
        }


        evento.preventDefault();


        cerrarChatbot();

    }
);


/* =========================================================
   FIN CHATBOT.JS
   ========================================================= */