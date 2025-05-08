@extends('layouts.app')

@section('title', 'Clientes')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title">Lista de Clientes</h5>
        <a href="/clients/create" class="btn btn-primary">Novo Cliente</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>E-mail</th>
                        <th>Telefone</th>
                        <th>Total de Solicitações</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody id="clientsTable">
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
    await loadClients();
});

async function loadClients() {
    try {
        const clients = await api.getClients();
        const tbody = document.getElementById('clientsTable');
        tbody.innerHTML = '';

        clients.forEach(client => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${client.name}</td>
                <td>${client.email}</td>
                <td>${client.phone}</td>
                <td>${client.total_requests}</td>
                <td>
                    <a href="/clients/${client.id}" class="btn btn-sm btn-primary">Ver</a>
                    <a href="/clients/${client.id}/edit" class="btn btn-sm btn-warning">Editar</a>
                    <button class="btn btn-sm btn-danger" onclick="confirmDelete(${client.id})">Excluir</button>
                </td>
            `;
            tbody.appendChild(tr);
        });
    } catch (error) {
        console.error('Erro ao carregar clientes:', error);
        alert('Erro ao carregar lista de clientes');
    }
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
        await loadClients();
        alert('Cliente excluído com sucesso!');
    } catch (error) {
        console.error('Erro ao excluir cliente:', error);
        alert('Erro ao excluir cliente');
    } finally {
        clientToDelete = null;
    }
});
</script>
@endsection 