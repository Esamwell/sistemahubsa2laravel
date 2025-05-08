@extends('layouts.app')

@section('title', 'Dashboard')

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/chart.js@3.7.0/dist/chart.min.css">
<style>
    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        text-align: center;
    }

    .stat-card .number {
        font-size: 2rem;
        font-weight: bold;
        color: var(--primary-color);
    }

    .stat-card .label {
        color: var(--gray-color);
        margin-top: 0.5rem;
    }

    .chart-container {
        margin-bottom: 2rem;
    }
</style>
@endsection

@section('content')
<div class="dashboard-grid">
    <div class="card stat-card">
        <div class="number" id="totalClients">0</div>
        <div class="label">Total de Clientes</div>
    </div>
    <div class="card stat-card">
        <div class="number" id="totalRequests">0</div>
        <div class="label">Total de Solicitações</div>
    </div>
    <div class="card stat-card">
        <div class="number" id="pendingRequests">0</div>
        <div class="label">Solicitações Pendentes</div>
    </div>
    <div class="card stat-card">
        <div class="number" id="completedRequests">0</div>
        <div class="label">Solicitações Concluídas</div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Solicitações por Status</h5>
            </div>
            <div class="card-body">
                <canvas id="requestsByStatus"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Solicitações por Mês</h5>
            </div>
            <div class="card-body">
                <canvas id="requestsByMonth"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.0/dist/chart.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', async () => {
    try {
        const data = await api.getDashboard();
        
        // Atualiza os cards de estatísticas
        document.getElementById('totalClients').textContent = data.totalClients;
        document.getElementById('totalRequests').textContent = data.totalRequests;
        document.getElementById('pendingRequests').textContent = data.pendingRequests;
        document.getElementById('completedRequests').textContent = data.completedRequests;

        // Gráfico de solicitações por status
        new Chart(document.getElementById('requestsByStatus'), {
            type: 'pie',
            data: {
                labels: data.requestsByStatus.map(item => item.status),
                datasets: [{
                    data: data.requestsByStatus.map(item => item.count),
                    backgroundColor: [
                        '#4a90e2',
                        '#2ecc71',
                        '#e74c3c',
                        '#f1c40f'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Gráfico de solicitações por mês
        new Chart(document.getElementById('requestsByMonth'), {
            type: 'line',
            data: {
                labels: data.requestsByMonth.map(item => item.month),
                datasets: [{
                    label: 'Solicitações',
                    data: data.requestsByMonth.map(item => item.count),
                    borderColor: '#4a90e2',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    } catch (error) {
        console.error('Erro ao carregar dados do dashboard:', error);
        alert('Erro ao carregar dados do dashboard');
    }
});
</script>
@endsection 