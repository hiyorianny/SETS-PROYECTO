document.addEventListener('DOMContentLoaded', function() {
  var calendarEl = document.getElementById('calendar');

  var calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'dayGridMonth', // Vista por defecto
    headerToolbar: {
      left: 'prev,next today', // Botones de navegación
      center: 'title', // Título del mes
      right: 'dayGridMonth,dayGridWeek,dayGridDay' // Opciones de vista
    },
    editable: true, // Permite arrastrar y soltar eventos
    events: [
      // Aquí puedes agregar tus eventos de ejemplo o cargarlos desde un servidor
      {
        title: 'Cita con el Dr. Pérez',
        start: '2024-08-25T10:00:00',
        end: '2024-08-25T11:00:00'
      },
      {
        title: 'Cita con la Dra. Gómez',
        start: '2024-08-26T14:00:00',
        end: '2024-08-26T15:00:00'
      }
    ],
    eventClick: function(info) {
      // Mostrar detalles del evento cuando se hace clic en él
      alert('Event: ' + info.event.title);
      // Puedes agregar aquí el código para mostrar detalles adicionales en un modal o en otra parte de tu página
    }
  });

  calendar.render();
});
