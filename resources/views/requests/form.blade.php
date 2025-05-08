@extends('layouts.app')

@section('title', isset($request) ? 'Editar Solicitação' : 'Nova Solicitação')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="card-title">{{ isset($request) ? 'Editar Solicitação' : 'Nova Solicitação' }}</h5>
    </div>
    <div class="card-body">
        <form id="requestForm">
            <div class="form-group">
                <label class="form-label" for="client_id">Cliente</label>
                <select class="form-control" id="client_id" name="client_id" required>
                    <option value="">Selecione um cliente</option>
                    <!-- Opções serão carregadas via JavaScript -->
                </select>
            </div>

            <div class="form-group">
                <label class="form-label" for="title">Título</label>
                <input type="text" class="form-control" id="title" name="title" required
                    value="{{ isset($request) ? $request->title : '' }}">
            </div>

            <div class="form-group">
                <label class="form-label" for="description">Descrição</label>
                <textarea class="form-control" id="description" name="description" rows="4" required>{{ isset($request) ? $request->description : '' }}</textarea>
            </div>

            <div class="form-group">
                <label class="form-label" for="status">Status</label>
                <select class="form-control" id="status" name="status" required>
                    <option value="pendente" {{ isset($request) && $request->status == 'pendente' ? 'selected' : '' }}>Pendente</option>
                    <option value="em_andamento" {{ isset($request) && $request->status == 'em_andamento' ? 'selected' : '' }}>Em Andamento</option>
                    <option value="concluido" {{ isset($request) && $request->status == 'concluido' ? 'selected' : '' }}>Concluído</option>
                    <option value="cancelado" {{ isset($request) && $request->status == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label" for="deadline">Prazo</label>
                <input type="date" class="form-control" id="deadline" name="deadline"
                    value="{{ isset($request) ? $request->deadline->format('Y-m-d') : '' }}">
            </div>

            <div class="form-group">
                <label class="form-label" for="priority">Prioridade</label>
                <select class="form-control" id="priority" name="priority" required>
                    <option value="baixa" {{ isset($request) && $request->priority == 'baixa' ? 'selected' : '' }}>Baixa</option>
                    <option value="media" {{ isset($request) && $request->priority == 'media' ? 'selected' : '' }}>Média</option>
                    <option value="alta" {{ isset($request) && $request->priority == 'alta' ? 'selected' : '' }}>Alta</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label" for="notes">Observações</label>
                <textarea class="form-control" id="notes" name="notes" rows="3">{{ isset($request) ? $request->notes : '' }}</textarea>
            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Salvar</button>
                <a href="/requests" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', async () => {
    const form = document.getElementById('requestForm');
    const isEdit = window.location.pathname.includes('/edit');
    const requestId = isEdit ? window.location.pathname.split('/')[2] : null;
    const clientId = new URLSearchParams(window.location.search).get('client_id');

    // Carrega lista de clientes
    try {
        const clients = await api.getClients();
        const select = document.getElementById('client_id');
        
        clients.forEach(client => {
            const option = document.createElement('option');
            option.value = client.id;
            option.textContent = client.name;
            if (clientId && client.id == clientId) {
                option.selected = true;
            }
            select.appendChild(option);
        });
    } catch (error) {
        console.error('Erro ao carregar clientes:', error);
        alert('Erro ao carregar lista de clientes');
    }

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const formData = {
            client_id: document.getElementById('client_id').value,
            title: document.getElementById('title').value,
            description: document.getElementById('description').value,
            status: document.getElementById('status').value,
            deadline: document.getElementById('deadline').value,
            priority: document.getElementById('priority').value,
            notes: document.getElementById('notes').value
        };

        try {
            if (isEdit) {
                await api.updateRequest(requestId, formData);
            } else {
                await api.createRequest(formData);
            }
            window.location.href = '/requests';
        } catch (error) {
            console.error('Erro ao salvar solicitação:', error);
            alert('Erro ao salvar solicitação');
        }
    });
});
</script>
@endsection 