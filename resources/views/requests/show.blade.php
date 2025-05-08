@extends('layouts.app')

@section('title', 'Detalhes da Solicitação')

@section('content')
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title">Informações da Solicitação</h5>
        <div>
            <a href="/requests/{{ $request->id }}/edit" class="btn btn-warning">Editar</a>
            <button class="btn btn-danger" onclick="confirmDelete({{ $request->id }})">Excluir</button>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <p><strong>Cliente:</strong> {{ $request->client->name }}</p>
                <p><strong>Título:</strong> {{ $request->title }}</p>
                <p><strong>Status:</strong> 
                    <span class="badge bg-{{ getStatusColor($request->status) }}">
                        {{ $request->status }}
                    </span>
                </p>
                <p><strong>Prioridade:</strong> 
                    <span class="badge bg-{{ getPriorityColor($request->priority) }}">
                        {{ $request->priority }}
                    </span>
                </p>
            </div>
            <div class="col-md-6">
                <p><strong>Data de Criação:</strong> {{ $request->created_at->format('d/m/Y H:i') }}</p>
                <p><strong>Última Atualização:</strong> {{ $request->updated_at->format('d/m/Y H:i') }}</p>
                <p><strong>Prazo:</strong> {{ $request->deadline ? $request->deadline->format('d/m/Y') : 'Não definido' }}</p>
            </div>
        </div>

        <div class="mt-4">
            <h6>Descrição</h6>
            <p>{{ $request->description }}</p>
        </div>

        @if($request->notes)
        <div class="mt-4">
            <h6>Observações</h6>
            <p>{{ $request->notes }}</p>
        </div>
        @endif
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="card-title">Histórico de Status</h5>
    </div>
    <div class="card-body">
        <div class="timeline">
            @foreach($request->statusHistory as $history)
            <div class="timeline-item">
                <div class="timeline-marker"></div>
                <div class="timeline-content">
                    <h6 class="mb-0">{{ $history->status }}</h6>
                    <small class="text-muted">{{ $history->created_at->format('d/m/Y H:i') }}</small>
                    @if($history->comment)
                    <p class="mt-1">{{ $history->comment }}</p>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Modal de Atualização de Status -->
<div class="modal fade" id="statusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Atualizar Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="statusForm">
                    <div class="form-group">
                        <label class="form-label" for="newStatus">Novo Status</label>
                        <select class="form-control" id="newStatus" name="status" required>
                            <option value="pendente">Pendente</option>
                            <option value="em_andamento">Em Andamento</option>
                            <option value="concluido">Concluído</option>
                            <option value="cancelado">Cancelado</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="statusComment">Comentário</label>
                        <textarea class="form-control" id="statusComment" name="comment" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="confirmStatus">Atualizar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmação de Exclusão -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Exclusão</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Tem certeza que deseja excluir esta solicitação?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Excluir</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
.timeline {
    position: relative;
    padding: 1rem 0;
}

.timeline-item {
    position: relative;
    padding-left: 2rem;
    padding-bottom: 1.5rem;
}

.timeline-item:last-child {
    padding-bottom: 0;
}

.timeline-marker {
    position: absolute;
    left: 0;
    top: 0;
    width: 1rem;
    height: 1rem;
    border-radius: 50%;
    background-color: var(--primary-color);
}

.timeline-item:not(:last-child)::before {
    content: '';
    position: absolute;
    left: 0.5rem;
    top: 1rem;
    bottom: 0;
    width: 2px;
    background-color: #ddd;
}
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
let deleteModal;
let statusModal;
let requestToDelete = null;

document.addEventListener('DOMContentLoaded', () => {
    deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    statusModal = new bootstrap.Modal(document.getElementById('statusModal'));
});

function getStatusColor(status) {
    const colors = {
        'pendente': 'warning',
        'em_andamento': 'info',
        'concluido': 'success',
        'cancelado': 'danger'
    };
    return colors[status] || 'secondary';
}

function getPriorityColor(priority) {
    const colors = {
        'baixa': 'success',
        'media': 'warning',
        'alta': 'danger'
    };
    return colors[priority] || 'secondary';
}

function confirmDelete(requestId) {
    requestToDelete = requestId;
    deleteModal.show();
}

document.getElementById('confirmDelete').addEventListener('click', async () => {
    if (!requestToDelete) return;

    try {
        await api.deleteRequest(requestToDelete);
        deleteModal.hide();
        window.location.href = '/requests';
    } catch (error) {
        console.error('Erro ao excluir solicitação:', error);
        alert('Erro ao excluir solicitação');
    } finally {
        requestToDelete = null;
    }
});

document.getElementById('confirmStatus').addEventListener('click', async () => {
    const status = document.getElementById('newStatus').value;
    const comment = document.getElementById('statusComment').value;

    try {
        await api.updateRequestStatus({{ $request->id }}, status, comment);
        statusModal.hide();
        window.location.reload();
    } catch (error) {
        console.error('Erro ao atualizar status:', error);
        alert('Erro ao atualizar status da solicitação');
    }
});
</script>
@endsection 