@extends('dashboard.layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Bienvenido, {{ $nombre }}</h2>

    <!-- Tarjetas resumen -->
    <div class="row text-white mb-4">
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card bg-primary text-center shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Clientes</h5>
                    <h2>{{ $totalClientes }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card bg-success text-center shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Productos</h5>
                    <h2>{{ $totalProductos }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card bg-warning text-center shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Por Vencer</h5>
                    <h2>{{ $productosPorVencer }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card bg-danger text-center shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Más Vendido</h5>
                    <h2>{{ $productoMasVendido->nombre ?? 'N/A' }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficos -->
    <div class="row mb-4">
        <div class="col-md-6 mb-3">
            <div class="card shadow-sm">
                <div class="card-header fw-bold">📊 Top 5 Productos Más Vendidos</div>
                <div class="card-body">
                    <canvas id="chartTopProductos" height="250"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card shadow-sm">
                <div class="card-header fw-bold">👥 Clientes Registrados por Día</div>
                <div class="card-body">
                    <canvas id="chartClientes" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Últimos pedidos y productos críticos -->
    <div class="row mb-4">
        <div class="col-md-6 mb-3">
            <div class="card shadow-sm">
                <div class="card-header fw-bold">🗂️ Últimos Pedidos</div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped align-middle text-center">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Cliente</th>
                                    <th>Total</th>
                                    <th>Fecha</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($ultimosPedidos as $pedido)
                                    <tr>
                                        <td>{{ $pedido->id_pedido }}</td>
                                        <td>{{ $pedido->nombre }}</td>
                                        <td>S/ {{ number_format($pedido->total, 2) }}</td>
                                        <td>{{ $pedido->fecha }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4">No hay pedidos recientes.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-3">
            <div class="card shadow-sm">
                <div class="card-header fw-bold text-danger">🚨 Productos Críticos</div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover align-middle text-center">
                            <thead class="table-danger">
                                <tr>
                                    <th>Producto</th>
                                    <th>Nro Lote</th>
                                    <th>Vencimiento</th>
                                    <th>Cantidad</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($productosCriticos as $producto)
                                    <tr>
                                        <td>{{ $producto->nombre }}</td>
                                        <td>{{ $producto->nro_lote }}</td>
                                        <td>{{ $producto->fecha_vencimiento }}</td>
                                        <td>{{ $producto->cantidad }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4">No hay productos vencidos o sin stock.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Botones de exportación -->
    <div class="text-end">
        <a href="{{ route('dashboard.export.csv') }}" class="btn btn-success me-2">
            📤 Exportar a Excel (CSV)
        </a>
        <button onclick="exportarPDF()" class="btn btn-danger">
            📄 Exportar a PDF
        </button>
    </div>
</div>
@endsection

@section('scripts')
<!-- Chart.js y html2pdf -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>

<script>
    function exportarPDF() {
        const element = document.querySelector('.container');
        html2pdf().from(element).save('dashboard.pdf');
    }

    // Productos más vendidos (doughnut)
    const topLabels = @json($masVendidos->pluck('nombre'));
    const topData = @json($masVendidos->pluck('total'));

    if (topLabels.length > 0 && topData.length > 0) {
        new Chart(document.getElementById('chartTopProductos'), {
            type: 'doughnut',
            data: {
                labels: topLabels,
                datasets: [{
                    data: topData,
                    backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b'],
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { boxWidth: 12 }
                    }
                }
            }
        });
    }

    // Clientes por día (bar)
    const clientesDias = @json(array_values($clientesPorMes));
    const fechasDias = @json(array_keys($clientesPorMes));

    if (fechasDias.length > 0 && clientesDias.length > 0) {
        new Chart(document.getElementById('chartClientes'), {
            type: 'bar',
            data: {
                labels: fechasDias,
                datasets: [{
                    label: 'Clientes',
                    data: clientesDias,
                    backgroundColor: '#36b9cc',
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: (ctx) => ` ${ctx.parsed.y} cliente(s)`
                        }
                    }
                },
                scales: {
                    x: {
                        title: { display: true, text: 'Fecha de Registro' },
                        ticks: { maxRotation: 45, minRotation: 45 }
                    },
                    y: {
                        beginAtZero: true,
                        title: { display: true, text: 'Cantidad' },
                        ticks: {
                            stepSize: 1,
                            precision: 0
                        }
                    }
                }
            }
        });
    }
</script>
@endsection