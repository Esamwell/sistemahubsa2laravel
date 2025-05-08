@extends('layouts.app')

@section('title', 'Calendário')

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css">
<style>
.fc-event {
    cursor: pointer;
}

.fc-event-title {
    font-weight: 500;
}

.fc-event.priority-alta {
    border-color: var(--danger-color);
    background-color: var(--danger-color);
}

.fc-event.priority-media {
    border-color: var(--warning-color);
    background-color: var(--warning-color);
}

.fc-event.priority-baixa {
    border-color: var(--success-color);
    background-color: var(--success-color);
}
</style>
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title">Calendário de Solicitações</h5>
        <button class="btn btn-primary" onclick="showEventModal()">Novo Evento</button>
    </div>
    <div class="card-body">
        <div id="calendar"></div>
    </div>
</div>

<!-- Modal de Evento -->
<div class="modal fade" id="eventModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Evento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="eventForm">
                    <input type="hidden" id="eventId">
                    <div class="form-group">
                        <label class="form-label" for="eventTitle">Título</label>
                        <input type="text" class="form-control" id="eventTitle" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="eventStart">Data Inicial</label>
                        <input type="datetime-local" class="form-control" id="eventStart" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="eventEnd">Data Final</label>
                        <input type="datetime-local" class="form-control" id="eventEnd" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="eventDescription">Descrição</label>
                        <textarea class="form-control" id="eventDescription" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="eventColor">Cor</label>
                        <input type="color" class="form-control" id="eventColor" value="#4a90e2">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="deleteEvent" style="display: none;">Excluir</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="saveEvent">Salvar</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
let calendar;
let eventModal;
let currentEvent = null;

document.addEventListener('DOMContentLoaded', async () => {
    eventModal = new bootstrap.Modal(document.getElementById('eventModal'));
    
    const calendarEl = document.getElementById('calendar');
    calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        locale: 'pt-br',
        buttonText: {
            today: 'Hoje',
            month: 'Mês',
            week: 'Semana',
            day: 'Dia'
        },
        events: '/api/calendar',
        eventClick: handleEventClick,
        selectable: true,
        select: handleDateSelect,
        editable: true,
        eventDrop: handleEventDrop,
        eventResize: handleEventResize
    });
    
    calendar.render();
    await loadEvents();
});

async function loadEvents() {
    try {
        const events = await api.getCalendar();
        calendar.removeAllEvents();
        calendar.addEventSource(events);
    } catch (error) {
        console.error('Erro ao carregar eventos:', error);
        alert('Erro ao carregar eventos do calendário');
    }
}

function showEventModal(event = null) {
    currentEvent = event;
    const form = document.getElementById('eventForm');
    const deleteBtn = document.getElementById('deleteEvent');
    
    if (event) {
        document.getElementById('eventId').value = event.id;
        document.getElementById('eventTitle').value = event.title;
        document.getElementById('eventStart').value = event.start.toISOString().slice(0, 16);
        document.getElementById('eventEnd').value = event.end.toISOString().slice(0, 16);
        document.getElementById('eventDescription').value = event.description || '';
        document.getElementById('eventColor').value = event.backgroundColor || '#4a90e2';
        deleteBtn.style.display = 'block';
    } else {
        form.reset();
        document.getElementById('eventId').value = '';
        deleteBtn.style.display = 'none';
    }
    
    eventModal.show();
}

function handleEventClick(info) {
    showEventModal(info.event);
}

function handleDateSelect(info) {
    showEventModal({
        start: info.start,
        end: info.end
    });
}

async function handleEventDrop(info) {
    try {
        await api.updateEvent(info.event.id, {
            start: info.event.start,
            end: info.event.end
        });
    } catch (error) {
        console.error('Erro ao atualizar evento:', error);
        info.revert();
        alert('Erro ao atualizar evento');
    }
}

async function handleEventResize(info) {
    try {
        await api.updateEvent(info.event.id, {
            start: info.event.start,
            end: info.event.end
        });
    } catch (error) {
        console.error('Erro ao atualizar evento:', error);
        info.revert();
        alert('Erro ao atualizar evento');
    }
}

document.getElementById('saveEvent').addEventListener('click', async () => {
    const formData = {
        title: document.getElementById('eventTitle').value,
        start: document.getElementById('eventStart').value,
        end: document.getElementById('eventEnd').value,
        description: document.getElementById('eventDescription').value,
        backgroundColor: document.getElementById('eventColor').value
    };

    try {
        if (currentEvent && currentEvent.id) {
            await api.updateEvent(currentEvent.id, formData);
        } else {
            await api.createEvent(formData);
        }
        eventModal.hide();
        await loadEvents();
    } catch (error) {
        console.error('Erro ao salvar evento:', error);
        alert('Erro ao salvar evento');
    }
});

document.getElementById('deleteEvent').addEventListener('click', async () => {
    if (!currentEvent || !currentEvent.id) return;

    if (confirm('Tem certeza que deseja excluir este evento?')) {
        try {
            await api.deleteEvent(currentEvent.id);
            eventModal.hide();
            await loadEvents();
        } catch (error) {
            console.error('Erro ao excluir evento:', error);
            alert('Erro ao excluir evento');
        }
    }
});
</script>
@endsection 