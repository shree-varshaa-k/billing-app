@extends('layouts.layout')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4 text-center">Sales</h2>

    <div class="table-responsive">
        <table class="table table-bordered table-hover text-center">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Sale Id</th>
                    <th>Customer</th>
                    <th>Item</th>
                    <th>Sub Total (₹)</th>
                    <th>Discount (₹)</th>
                    <th>Total (₹)</th>
                    <th>Paid (₹)</th>
                    <th>Due (₹)</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                @foreach($sales as $sale)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $sale->invoice_number }}</td>
                    <td>{{ $sale->client->name ?? '-' }}</td>
                    <td>{{ $sale->items_count }}</td>
                    <td>{{ number_format($sale->subtotal, 2) }}</td>
                    <td>{{ number_format($sale->discount, 2) }}</td>
                    <td>{{ number_format($sale->total, 2) }}</td>
                    <td>{{ number_format($sale->paidamount, 2) }}</td>
                    <td>{{ number_format($sale->remainingamount, 2) }}</td>
                    <td>
                        <span class="badge {{ $sale->remainingamount > 0 ? 'bg-danger' : 'bg-success' }}">
                            {{ $sale->remainingamount > 0 ? 'Due' : 'Paid' }}
                        </span>
                    </td>

                    <td>
                        <!-- PRINT -->
                        <a href="{{ route('sales.print', $sale->id) }}" 
                           target="_blank" 
                           class="btn btn-sm btn-info">
                            <i class="bi bi-receipt"></i>
                        </a>

                        <!-- POS -->
                        <button class="btn btn-sm btn-primary" 
                            onclick="generatePOS('{{ $sale->id }}')">
                            <i class="bi bi-printer"></i>
                        </button>

                        <!-- DELETE -->
                        <form action="{{ route('sales.destroy', $sale->id) }}" 
                              method="POST" 
                              style="display:inline-block;"
                              onsubmit="return confirm('Delete this invoice?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>

        </table>
    </div>
</div>

<script>
async function generatePOS(id) {
    try {
        const res = await fetch("{{ url('/sales') }}/" + id + "/pos");
        if (!res.ok) throw new Error("POS route error");

        const data = await res.json();

        let html = `
            <html>
            <head><title>POS Bill</title></head>
            <body>
                <h3>POS BILL</h3>
                <p>Invoice: ${data.id}<br>
                   Customer: ${data.client_name}<br>
                   Date: ${data.date}</p>

                <table border="1" width="100%" cellspacing="0" cellpadding="5">
                    <tr><th>Item</th><th>Qty</th><th>Total</th></tr>
        `;

        data.items.forEach(x => {
            html += `<tr>
                        <td>${x.product_name}</td>
                        <td>${x.quantity}</td>
                        <td>${x.total}</td>
                     </tr>`;
        });

        html += `
                </table>
                <h4>Total: ₹${data.total}</h4>
            </body>
            </html>
        `;

        let w = window.open("", "_blank", "width=400,height=600");
        w.document.write(html);
        w.document.close();
    }
    catch (e) {
        alert("POS Error: " + e.message);
        console.error(e);
    }
}
</script>
@endsection
