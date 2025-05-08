@extends('layouts.app')

@section('title', 'Solicitações')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title">Lista de Solicitações</h5>
        <a href="/requests/create" class="btn btn-primary">Nova Solicitação</a>
    </div>
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-3">
                <select class="form-control" id="statusFilter">
                    <option value="">Todos os Status</option>
                    <option value="pendente">Pendente</option>
                    <option value="em_andamento">Em Andamento</option>
                    <option value="concluido">Concluído</option>
                    <option value="cancelado">Cancelado</option>
                </select>
            </div>
            <div class="col-md-3">
                <input type="date" class="form-control" id="dateFilter" placeholder="Filtrar por data">
            </div>
            <div class="col-md-4">
                <input type="text" class="form-control" id="searchFilter" placeholder="Buscar por título ou cliente">
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary w-100" onclick="applyFilters()">Filtrar</button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Título</th>
                        <th>Status</th>
                        <th>Data</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody id="requestsTable">
                    <!-- Dados serão carregados via JavaScript -->
                </tbody>
            </table>
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

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
let deleteModal;
let requestToDelete = null;
let currentFilters = {
    status: '',
    date: '',
    search: ''
};

document.addEventListener('DOMContentLoaded', async () => {
    deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    await loadRequests();
});

async function loadRequests() {
    try {
        const requests = await api.getRequests(currentFilters);
        const tbody = document.getElementById('requestsTable');
        tbody.innerHTML = '';

        requests.forEach(request => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${request.id}</td>
                <td>${request.client.name}</td>
                <td>${request.title}</td>
                <td>
                    <span class="badge bg-${getStatusColor(request.status)}">
                        ${request.status}
                    </span>
                </td>
                <td>${new Date(request.created_at).toLocaleDateString()}</td>
                <td>
                    <a href="/requests/${request.id}" class="btn btn-sm btn-primary">Ver</a>
                    <a href="/requests/${request.id}/edit" class="btn btn-sm btn-warning">Editar</a>
                    <button class="btn btn-sm btn-danger" onclick="confirmDelete(${request.id})">Excluir</button>
                </td>
            `;
            tbody.appendChild(tr);
        });
    } catch (error) {
        console.error('Erro ao carregar solicitações:', error);
        alert('Erro ao carregar lista de solicitações');
    }
}

function getStatusColor(status) {
    const colors = {
        'pendente': 'warning',
        'em_andamento': 'info',
        'concluido': 'success',
        'cancelado': 'danger'
    };
    return colors[status] || 'secondary';
}

function applyFilters() {
    currentFilters = {
        status: document.getElementById('statusFilter').value,
        date: document.getElementById('dateFilter').value,
        search: document.getElementById('searchFilter').value
    };
    loadRequests();
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
        await loadRequests();
        alert('Solicitação excluída com sucesso!');
    } catch (error) {
        console.error('Erro ao excluir solicitação:', error);
        alert('Erro ao excluir solicitação');
    } finally {
        requestToDelete = null;
    }
});
</script>
@endsection 