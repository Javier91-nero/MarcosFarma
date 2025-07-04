@extends('dashboard.layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Bienvenido, {{ $nombre }}</h2>

    {{-- 📈 Gráfico de pedidos por mes --}}
    <div class="card mb-4">
        <div class="card-header">📈 Pedidos por mes</div>
        <div class="card-body">
            <canvas id="chartPedidos"></canvas>
        </div>
    </div>

    {{-- 🗂️ Tabla de últimos pedidos --}}
    <div class="card mb-4">
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

    {{-- 🚨 Productos críticos --}}
    <div class="card mb-4">
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
    const ctx = document.getElementById('chartPedidos');

    const pedidosPorMes = @json(array_values($pedidosPorMes));
    const mesesNumeros = @json(array_keys($pedidosPorMes));
    const meses = mesesNumeros.map(m => 'Mes ' + m);

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: meses,
            datasets: [{
                label: 'Pedidos',
                data: pedidosPorMes,
                backgroundColor: '#4e73df',
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            }
        }
    });

    function exportarPDF() {
        const element = document.querySelector('.container');
        html2pdf().from(element).save('dashboard.pdf');
    }
</script>
@endsection