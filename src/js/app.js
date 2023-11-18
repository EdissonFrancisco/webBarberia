let paso = 1; 
const PASOINICIAL = 1;
const PASOFINAL = 3;

const cita = {
  nombre: '',
  fecha: '',
  hora: '',
  servicios: []
}

document.addEventListener('DOMContentLoaded', function () {
  iniciarApp();
});

function iniciarApp() {
  mostrarSeccion();//muestra y oculta las secciones
  tabs(); //cambiar la seccion cuando se presionen los tabs
  botonesPaginador()//agrega o quita los botones de paginacion
  paginaSiguiente();
  paginaAnterior();

  //consulta de API
  consultarAPI();//consulta la api en el backend de PHP
  nombreCliente();//agrega el nombre del cliente al objeto cita
  seleccionarFecha()//agrega la fecha de la cita al objeto cita
  seleccionarHora()//agrega la Hora de la cita al objeto cita
}

function mostrarSeccion() {
  //ocultamos la seccion anterior
  const seccionAnterior = document.querySelector('.mostrar');
  if(seccionAnterior) { 
    seccionAnterior.classList.remove('mostrar');
  }

  //seleccionar seccion con el paso con - id asignado -
  const seccion = document.querySelector(`#paso-${paso}`);
  seccion.classList.add('mostrar');

  //quita la clase que resaltar el tab anterior
  const tabAnterio = document.querySelector('.actual');
  if(tabAnterio) {
    tabAnterio.classList.remove('actual');
  }

  //resalta boton activo o tab actual con selector de atributo [atributo]
  const tab = document.querySelector(`[data-paso="${paso}"]`);
  tab.classList.add('actual');

}

function tabs() {
  const botones = document.querySelectorAll('.tabs button');

  botones.forEach( boton => {
    boton.addEventListener('click', function (e) {
      paso = parseInt(e.target.dataset.paso);
      mostrarSeccion();
      botonesPaginador();
    });
  });
}

function botonesPaginador() {
  //selecciono los botones con su id - # -
  const pagAnterior = document.querySelector('#anterior');
  const pagSiguiente = document.querySelector('#siguiente');

  if(paso === 1) {
    pagAnterior.classList.add('ocultar');
    pagSiguiente.classList.remove('ocultar'); 
  } else if(paso === 3) {
    pagAnterior.classList.remove('ocultar');
    pagSiguiente.classList.add('ocultar');
    mostrarResumen() //mstrar resumen de la cita
  } else {
    pagAnterior.classList.remove('ocultar');
    pagSiguiente.classList.remove('ocultar');
  }
}

function paginaAnterior() {
  const pagSiguiente = document.querySelector('#anterior');

  pagSiguiente.addEventListener('click', () => {
    if(paso <= PASOINICIAL) return;
    paso--;
    botonesPaginador();
    mostrarSeccion();
  })
}

function paginaSiguiente() {
  const pagSiguiente = document.querySelector('#siguiente');

  pagSiguiente.addEventListener('click', () => {
    if(paso >= PASOFINAL) return;
    paso++;
    botonesPaginador();
    mostrarSeccion();
  })
}

async function consultarAPI() {
  try {
    const URL = 'http://localhost:3000/api/servicios';//tenemos la url del api
    const resultado = await fetch(URL);//obtengo los datos del api
    const servicios = await resultado.json();//paso los datos a json

    mostrarServicios(servicios);//muestra los servicios en la interface 
    
  } catch (error) {
    console.log(error);
  }
}

function mostrarServicios(servicios) {
  servicios.forEach( servicio => {
    //destructurin para obtener los adtos del json extrae el valor y crea la variable
    const { id, nombre, precio } = servicio;

    //scripting es mas tardado pero en performans es mas rapido y seguro
    const nombreServicio = document.createElement('P');//crear etiqueta
    nombreServicio.classList.add('nombre-servicio');//agregar clase css
    nombreServicio.textContent = nombre;//agregar contenido de etiqueta

    const precioServicio = document.createElement('P');
    precioServicio.classList.add('precio-servicio');
    precioServicio.textContent = precio;

    const servicioDiv = document.createElement('DIV');
    servicioDiv.classList.add('servicio');
    servicioDiv.dataset.idServicio = id;//atributo personalizado - dataset.idServicio -
    //asociacion de evento al div
    servicioDiv.onclick = function() {
      seleccionarServicio(servicio);
    }

    //agregar contenido del div appendChild(contenido)
    servicioDiv.appendChild(nombreServicio);
    servicioDiv.appendChild(precioServicio);

    //colocar el contenido para que el usuario lo vea
    document.querySelector('#servicios').appendChild(servicioDiv);
  });
}

function seleccionarServicio(servicio) {
  //destructurin del objeto cita para trar arreglo y id del servicio
  const { id } = servicio;
  const { servicios } = cita;//es el objeto en memoria

  //identificar elemento al que se le da click
  const divServicio = document.querySelector(`[data-id-servicio="${id}"]`);

  //conprobar si un servicio ya fue agregado
  //.some itera en el arreglo y retorna tru o false si el elemento ya esta o no en el arreglo
  if(servicios.some( agregado => agregado.id === id )) {
    //eliminar servicio seleccionado con el metodo filter
    cita.servicios = servicios.filter( agregado => agregado.id !== id );
    //agrego clase -seleccionado- al servicio
    divServicio.classList.remove('seleccionado');
  } else {
    //agregar servicio seleccionado

    //tomo una copia del arreglo de servicios (...servicios) y luego se agrego el servicio
    cita.servicios = [...servicios, servicio];
    //agrego clase -seleccionado- al servicio
    divServicio.classList.add('seleccionado');
  }

  console.log(cita);
}

function nombreCliente() {
  cita.nombre = document.querySelector('#nombre').value;
}

function seleccionarFecha() {
  const inputFecha = document.querySelector('#fecha');

  //almaceno el identificador de tiempo
  let timeoutId;

  inputFecha.addEventListener('input', e => {
    //lipio el temporizador si existe evento
    clearTimeout(timeoutId);
    //creo el evento para el temporizador
    timeoutId = setTimeout(() => {
      validarFecha(e.target);
    }, 100);
  });
}

function validarFecha(input) {
  // Obtener la fecha actual
  const fechaActual = new Date();
  const diaActual = fechaActual.getDate();
  const mesActual = fechaActual.getMonth();
  //obtener dia seleccionado
  const dia = new Date(input.value);
  const diaFecha = dia.getDate()+1;
  const mesFecha = dia.getMonth();
  const diaSemana = dia.getUTCDay();


  if (diaFecha < diaActual || mesFecha < mesActual) {
    //ALERTA ERROR
    input.value = '';
    mostrarAlerta('dia invalido seleccione dia actual o posterior', 'error', '.formulario');
    //ocultar alerta
    setTimeout(ocultarAlerta, 5000);

  } else if ([6, 0].includes(diaSemana)) {
    input.value = '';
    mostrarAlerta('fines de semana no permitidos', 'error', '.formulario');
    //ocultar alerta
    setTimeout(ocultarAlerta, 5000);
  } else {
    cita.fecha = input.value;
  }
}

function seleccionarHora() {
  const inputHora = document.querySelector('#hora');
  inputHora.addEventListener('input', e => {
    const horaCita = e.target.value;
    //separa los valores
    const hora = horaCita.split(":")[0];

    if (hora < 8 || hora > 18){
      e.target.value = '';
      mostrarAlerta('hora no valida ', 'error', '.formulario');
      setTimeout(ocultarAlerta, 5000);
    } else {
      cita.hora = e.target.value;
    }
  })
}

function mostrarAlerta(mensaje, tipo, elemento) {
  const alertaPrevia = document.querySelector('.alerta');
  if (alertaPrevia) alertaPrevia.remove();

  const alerta = document.createElement('DIV');
  alerta.textContent = mensaje;
  alerta.classList.add('alerta');
  alerta.classList.add(tipo);

  const referencia = document.querySelector(elemento);
  referencia.appendChild(alerta);
}

function ocultarAlerta() {
  const alerta = document.querySelector('.alerta');
  alerta ? alerta.remove() : '';
}

//contenido de resumen para agendar cita

function limpiarContenido(resumen) {
  // Limpiar el Contenido de Resumen
  while(resumen.firstChild) {
    resumen.removeChild(resumen.firstChild);
  }

  if(Object.values(cita).includes('') || cita.servicios.length === 0 ) {
    mostrarAlerta('Faltan datos de Servicios, Fecha u Hora', 'error', '.contenido-resumen');
    return;
  }
}

function serviciosResumen(servicios, resumen) {
  // Heading para Servicios en Resumen
  const headingServicios = document.createElement('H3');
  headingServicios.textContent = 'Resumen de Servicios';
  resumen.appendChild(headingServicios);

  const contenedorServicio = document.createElement('DIV');
  contenedorServicio.classList.add('contenedor-servicio');

  // Iterando y mostrando los servicios
  servicios.forEach(servicio => {
    const { id, precio, nombre } = servicio;
    const cardServicio = document.createElement('DIV');
    cardServicio.classList.add('card-servicio');

    const textoServicio = document.createElement('P');
    textoServicio.textContent = nombre;

    const precioServicio = document.createElement('P');
    precioServicio.innerHTML = `<span>Precio:</span> $${precio}`;

    cardServicio.appendChild(textoServicio);
    cardServicio.appendChild(precioServicio);

    contenedorServicio.appendChild(cardServicio);
  });

  resumen.appendChild(contenedorServicio);
}

//la fecha se mostrara con este formato dia | mes en letras | año
function formateoFechaCliente(fecha) {
  // Formatear la fecha en español
  const fechaObj = new Date(fecha);
  const mes = fechaObj.getMonth();
  const dia = fechaObj.getDate() + 2;
  const year = fechaObj.getFullYear();

  const fechaUTC = new Date( Date.UTC(year, mes, dia));

  const opciones = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'}
  const fechaFormateada = fechaUTC.toLocaleDateString('es-MX', opciones);

  return fechaFormateada;
}

//Agrego a servicios los datos del cliente
function serviciosDatosCliente(nombre, fechaCliente, hora, resumen) {
  // Heading para Cita en Resumen
  const headingCita = document.createElement('H3');
  headingCita.textContent = 'Resumen de Cita';
  resumen.appendChild(headingCita);

  const nombreCliente = document.createElement('P');
  nombreCliente.innerHTML = `<span>Nombre:</span> ${nombre}`;

  const fechaCita = document.createElement('P');
  fechaCita.innerHTML = `<span>Fecha:</span> ${fechaCliente}`;

  const horaCita = document.createElement('P');
  horaCita.innerHTML = `<span>Hora:</span> ${hora} Horas`;

  resumen.appendChild(nombreCliente);
  resumen.appendChild(fechaCita);
  resumen.appendChild(horaCita);
}

function mostrarResumen() {
  const resumen = document.querySelector('.contenido-resumen');

  limpiarContenido(resumen);

  // Formatear el div de resumen
  const { nombre, fecha, hora, servicios } = cita;

  serviciosResumen(servicios, resumen);

  const fechaCliente = formateoFechaCliente(fecha);
  serviciosDatosCliente(nombre, fechaCliente, hora, resumen);

  /* Boton para Crear una cita */
  const botonReservar = document.createElement('BUTTON');
  botonReservar.classList.add('boton');
  botonReservar.textContent = 'Reservar Cita';
  botonReservar.onclick = reservarCita;

  //resumen.appendChild(botonReservar);

}

//boton para guardar la cita
function reservarCita() {
  const datos = FormData();
  datos.append('')
}