@extends('dashboard.layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Bienvenido, {{ $nombre }}</h2>

    {{-- 🔢 Cuadros resumen --}}
    <div class="row text-white mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-center">
                <div class="card-body">
                    <h5 class="card-title">Clientes</h5>
                    <h2>{{ $totalClientes }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-center">
                <div class="card-body">
                    <h5 class="card-title">Productos</h5>
                    <h2>{{ $totalProductos }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-center">
                <div class="card-body">
                    <h5 class="card-title">Por Vencer</h5>
                    <h2>{{ $productosPorVencer }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-center">
                <div class="card-body">
                    <h5 class="card-title">Más Vendido</h5>
                    <h6>{{ $productoMasVendido->nombre ?? 'N/A' }}</h6>
                    <h2>{{ $productoMasVendido->total ?? 0 }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        {{-- 📊 Gráfico circular --}}
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">📊 Top 5 Productos Más Vendidos</div>
                <div class="card-body">
                    <canvas id="chartTopProductos"></canvas>
                </div>
            </div>
        </div>

        {{-- 👥 Clientes por mes --}}
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">👥 Clientes registrados por mes</div>
                <div class="card-body">
                    <canvas id="chartClientes"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        {{-- 🗂️ Últimos pedidos --}}
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">🗂️ Últimos pedidos</div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
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
                                <tr>
                                    <td colspan="4">No hay pedidos recientes.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- 🚨 Productos críticos --}}
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">🚨 Productos críticos</div>
                <div class="card-body">
                    <table class="table table-danger table-striped">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Nro Lote</th>
                                <th>Fecha Vencimiento</th>
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
                                <tr>
                                    <td colspan="4">No hay productos vencidos o sin stock.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- 📤 Botones de exportación --}}
    <div class="text-end">
        <a href="{{ route('dashboard.export.csv') }}" class="btn btn-success me-2">📤 Exportar a Excel (CSV)</a>
        <button onclick="exportarPDF()" class="btn btn-danger">📄 Exportar a PDF</button>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>

<script>
    function exportarPDF() {
        const element = document.querySelector('.container');
        html2pdf().from(element).save('dashboard.pdf');
    }

    // Gráfico: Productos más vendidos
    const topLabels = @json($masVendidos->pluck('nombre'));
    const topData = @json($masVendidos->pluck('total'));
    new Chart(document.getElementById('chartTopProductos'), {
        type: 'doughnut',
        data: {
            labels: topLabels,
            datasets: [{
                data: topData,
                backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b']
            }]
        }
    });

    // Gráfico: Clientes registrados por mes
    const clientesMes = @json(array_values($clientesPorMes));
    const clientesMesNumeros = @json(array_keys($clientesPorMes));
    const mesesClientes = clientesMesNumeros.map(m => 'Mes ' + m);

    new Chart(document.getElementById('chartClientes'), {
        type: 'bar',
        data: {
            labels: mesesClientes,
            datasets: [{
                label: 'Clientes',
                data: clientesMes,
                backgroundColor: '#36b9cc'
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } }
        }
    });
</script>
@endsection