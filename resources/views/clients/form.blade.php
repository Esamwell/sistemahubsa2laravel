@extends('layouts.app')

@section('title', isset($client) ? 'Editar Cliente' : 'Novo Cliente')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="card-title">{{ isset($client) ? 'Editar Cliente' : 'Novo Cliente' }}</h5>
    </div>
    <div class="card-body">
        <form id="clientForm">
            <div class="form-group">
                <label class="form-label" for="name">Nome</label>
                <input type="text" class="form-control" id="name" name="name" required
                    value="{{ isset($client) ? $client->name : '' }}">
            </div>

            <div class="form-group">
                <label class="form-label" for="email">E-mail</label>
                <input type="email" class="form-control" id="email" name="email" required
                    value="{{ isset($client) ? $client->email : '' }}">
            </div>

            <div class="form-group">
                <label class="form-label" for="phone">Telefone</label>
                <input type="tel" class="form-control" id="phone" name="phone" required
                    value="{{ isset($client) ? $client->phone : '' }}">
            </div>

            <div class="form-group">
                <label class="form-label" for="address">Endereço</label>
                <textarea class="form-control" id="address" name="address" rows="3">{{ isset($client) ? $client->address : '' }}</textarea>
            </div>

            <div class="form-group">
                <label class="form-label" for="notes">Observações</label>
                <textarea class="form-control" id="notes" name="notes" rows="3">{{ isset($client) ? $client->notes : '' }}</textarea>
            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Salvar</button>
                <a href="/clients" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('clientForm');
    const isEdit = window.location.pathname.includes('/edit');
    const clientId = isEdit ? window.location.pathname.split('/')[2] : null;

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const formData = {
            name: document.getElementById('name').value,
            email: document.getElementById('email').value,
            phone: document.getElementById('phone').value,
            address: document.getElementById('address').value,
            notes: document.getElementById('notes').value
        };

        try {
            if (isEdit) {
                await api.updateClient(clientId, formData);
            } else {
                await api.createClient(formData);
            }
            window.location.href = '/clients';
        } catch (error) {
            console.error('Erro ao salvar cliente:', error);
            alert('Erro ao salvar cliente');
        }
    });
});
</script>
@endsection 