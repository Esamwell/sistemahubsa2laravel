@extends('layouts.app')

@section('title', 'Detalhes do Cliente')

@section('content')
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title">Informações do Cliente</h5>
        <div>
            <a href="/clients/{{ $client->id }}/edit" class="btn btn-warning">Editar</a>
            <button class="btn btn-danger" onclick="confirmDelete({{ $client->id }})">Excluir</button>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <p><strong>Nome:</strong> {{ $client->name }}</p>
                <p><strong>E-mail:</strong> {{ $client->email }}</p>
                <p><strong>Telefone:</strong> {{ $client->phone }}</p>
            </div>
            <div class="col-md-6">
                <p><strong>Endereço:</strong> {{ $client->address }}</p>
                <p><strong>Observações:</strong> {{ $client->notes }}</p>
                <p><strong>Data de Cadastro:</strong> {{ $client->created_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title">Solicitações do Cliente</h5>
        <a href="/requests/create?client_id={{ $client->id }}" class="btn btn-primary">Nova Solicitação</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
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
                Tem certeza que deseja excluir este cliente?
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
let clientToDelete = null;

document.addEventListener('DOMContentLoaded', async () => {
    deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    await loadClientRequests();
});

async function loadClientRequests() {
    try {
        const clientId = window.location.pathname.split('/')[2];
        const requests = await api.getRequests({ client_id: clientId });
        const tbody = document.getElementById('requestsTable');
        tbody.innerHTML = '';

        requests.forEach(request => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${request.id}</td>
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
                </td>
            `;
            tbody.appendChild(tr);
        });
    } catch (error) {
        console.error('Erro ao carregar solicitações:', error);
        alert('Erro ao carregar solicitações do cliente');
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

function confirmDelete(clientId) {
    clientToDelete = clientId;
    deleteModal.show();
}

document.getElementById('confirmDelete').addEventListener('click', async () => {
    if (!clientToDelete) return;

    try {
        await api.deleteClient(clientToDelete);
        deleteModal.hide();
        window.location.href = '/clients';
    } catch (error) {
        console.error('Erro ao excluir cliente:', error);
        alert('Erro ao excluir cliente');
    } finally {
        clientToDelete = null;
    }
});
</script>
@endsection 