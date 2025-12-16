@extends('layouts.layout')
@section('title', 'Invoice History')

@section('content')
<style>
    .table th,
    .table td {
        vertical-align: middle;
        white-space: nowrap;
    }

    .table-responsive {
        overflow-x: auto;
    }

    h3.fw-bold {
        font-size: 1.8rem;
    }

    /* ----------- Large Screens (≥1288px) ----------- */
    @media (min-width: 1288px) {

        .table th,
        .table td {
            font-size: 1rem;
            padding: 0.75rem;
        }

        h3.fw-bold {
            font-size: 2rem;
        }
    }

    /* ----------- Mid Screens (768px–1287px) ----------- */
    @media (min-width: 768px) and (max-width: 1287px) {

        .table th,
        .table td {
            font-size: 0.85rem;
            padding: 0.4rem 0.6rem;
            white-space: nowrap;
        }

        .table-responsive {
            overflow-x: auto;
            max-width: 100%;
        }

        .container-fluid {
            padding: 0 10px !important;
        }

        h3.fw-bold {
            font-size: 1.4rem;
        }

        .btn-sm {
            padding: 3px 6px;
            font-size: 0.85rem;
        }

        /* Prevent sidebar push */
        .flex-fill {
            flex: 1 1 auto;
            overflow-x: hidden;
        }
    }

    /* ----------- Small Screens (max-width: 767px) ----------- */
    @media (max-width: 767px) {
        .d-flex.justify-content-between {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }

        h3.fw-bold {
            font-size: 1.3rem;
            text-align: center;
            width: 100%;
        }

        .btn-sm {
            width: 100%;
            text-align: center;
            font-size: 0.9rem;
        }

        /* Responsive table style */
        .table thead {
            display: none;
        }

        .table tbody tr {
            display: block;
            margin-bottom: 15px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            padding: 10px;
        }

        .table tbody td {
            display: flex;
            justify-content: space-between;
            padding: 8px 10px;
            font-size: 0.9rem;
            border: none;
        }

        .table tbody td::before {
            content: attr(data-label);
            font-weight: 600;
            color: #333;
        }

        .d-flex.justify-content-center.gap-2 {
            flex-wrap: wrap;
        }

        .btn {
            flex: 1 1 45%;
            margin: 4px 0;
        }

        /* Sidebar & main content adjustments */
        .flex-fill {
            width: 100%;
            margin-left: 0;
        }
    }
</style>

<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-primary">Invoice History</h3>
        <a href="{{ route('invoices.create') }}" class="btn btn-outline-primary btn-sm">Create Invoice +</a>
    </div>

    <div class="table-responsive shadow-sm rounded">
        <table class="table table-striped align-middle mb-0">
            <thead class="table-primary text-center">
                <tr>
                    <th>SI.NO</th>
                    <th>Invoice</th>
                    <th>Customer</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Tax</th>
                    <th>Paid Amount (₹)</th>
                    <th>Remaining (₹)</th>
                    <th>Total (₹)</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody class="text-center">
                @forelse($invoices as $invoice)
                <tr>
                    <!-- 🔹 Serial number -->
                    <td>{{ $loop->iteration }}</td>

                    <!-- 🔹 Invoice number (from DB) -->
                    <td>{{ $invoice->invoice_number }}</td>

                    <!-- 🔹 Customer -->
                    <td>{{ $invoice->client->name ?? '-' }}</td>

                    <!-- 🔹 Date -->
                    <td>{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d-m-Y') }}</td>


                    <!-- 🔹 Status -->
                    <td>
                        <span class="badge bg-{{ 
                            $invoice->status === 'Paid' ? 'success' : 
                            ($invoice->status === 'Unpaid' ? 'warning' : 'info') 
                        }}">
                            {{ $invoice->status }}
                        </span>
                    </td>

                    <!-- 🔹 Tax -->
                    <td>{{ $invoice->tax }}%</td>

                    <!-- 🔹 Paid, Remaining, Total -->
                    <td>₹{{ number_format($invoice->paidamount, 2) }}</td>
                    <td>₹{{ number_format($invoice->remainingamount, 2) }}</td>
                    <td>₹{{ number_format($invoice->total, 2) }}</td>

                    <!-- 🔹 Actions -->
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-2">

                            <!-- PRINT Invoice -->
                            <a href="{{ route('sales.print', $invoice->id) }}"
                                target="_blank"
                                class="btn btn-sm btn-info">
                                <i class="bi bi-receipt"></i>
                            </a>

                            <!-- POS Print -->
                            <button class="btn btn-sm btn-primary"
                                onclick="generatePOS('{{ $invoice->id }}')">
                                <i class="bi bi-printer"></i>
                            </button>

                            <!-- DELETE -->
                            <form action="{{ route('invoices.destroy', $invoice->id) }}"
                                method="POST"
                                class="d-inline-block"
                                onsubmit="return confirmDelete(this);">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="bi bi-trash3-fill"></i>
                                </button>
                            </form>

                        </div>
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="10" class="text-muted text-center py-4">No invoices found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
async function generatePOS(id) {
    try {
        let w = window.open("", "_blank", "width=420,height=700");
        if (!w) { alert("Please allow popups."); return; }

        const res = await fetch("{{ url('/sales') }}/" + id + "/pos");
        if (!res.ok) throw new Error("Failed to fetch POS data: " + res.status);

        const data = await res.json();

        let invoice_no = data.invoice_no || data.id;
        let client_name = data.client_name || '-';
        let date = data.date || '';

        let subtotal = 0;
        let total_qty = 0;
        let total_item_discount = 0;

        let items = data.items || [];

        let html = '';
        html += '<html><head><meta charset="utf-8"/>';
        html += '<title>POS Bill - ' + invoice_no + '</title>';
        html += '<style>';
        html += 'body{font-family:monospace;padding:10px;width:260px;}';
        html += '.line{border-top:1px dashed #000;margin:6px 0;}';
        html += '.small{font-size:12px;}';
        html += '</style></head><body>';

        html += '<h3 style="text-align:center;">Feather Softwares</h3>';
        html += '<p class="small" style="text-align:center;">S.M Arcade, Karungal</p>';
        html += '<p class="small" style="text-align:center;">Ph: 07550110016</p>';
        html += '<div class="line"></div>';

        html += `<p class="small">Invoice: ${invoice_no}<br/>Customer: ${client_name}<br/>Date: ${date}</p>`;
        html += '<div class="line"></div>';

        // ITEMS
        items.forEach(it => {
            let name = it.product_name || '-';
            let rate = Number(it.rate || 0);
            let qty = Number(it.quantity || 0);
            let discount_percent = Number(it.discount || 0);
            let amount = rate * qty;
            let discount_amt = (amount * discount_percent) / 100;

            subtotal += amount;
            total_qty += qty;
            total_item_discount += discount_amt;

            html += `<div style="font-weight:bold;">${name}</div>`;

            html += `<div style="width:100%; font-size:13px;">
            <span style="display:inline-block; width:60px;">${rate.toFixed(2)}</span>
            <span style="display:inline-block; width:40px; text-align:center;">${qty}</span>
            <span style="display:inline-block; width:70px; text-align:right;">${amount.toFixed(2)}</span>
         </div>`;

                     `<div style="clear:both;"></div>`;

// 🔥 Correct discount display (fixed ₹ amount)
if (it.discount > 0) {
    html += `<div class="small">DISCOUNT : ₹${Number(it.discount).toFixed(2)}</div>`;
    html += `<div style="float:right;" class="small">-₹${Number(it.discount).toFixed(2)}</div>`;
} else {
    html += `<div class="small">DISCOUNT : 0%</div>`;
    html += `<div style="float:right;" class="small">-₹0.00</div>`;
}
html += '<div style="clear:both;"></div>';

            html += '<div class="line"></div>';
        });

        let net_total = subtotal - total_item_discount;

        // SUMMARY (like sample)
        html += `<div><div style="float:left;">TOTAL QTY :</div><div style="float:right;">${total_qty}</div><div style="clear:both;"></div></div>`;
        html += `<div><div style="float:left;">SUBTOTAL :</div><div style="float:right;">₹${subtotal.toFixed(2)}</div><div style="clear:both;"></div></div>`;
        html += `<div><div style="float:left;">ITEM DISCOUNT :</div><div style="float:right;">-₹${total_item_discount.toFixed(2)}</div><div style="clear:both;"></div></div>`;

        html += '<div class="line"></div>';

        html += `<div style="font-weight:bold;font-size:16px;">
                    <div style="float:left;">NET AMOUNT :</div>
                    <div style="float:right;">₹${net_total.toFixed(2)}</div>
                 <div style="clear:both;"></div></div>`;

        html += '<div class="line"></div>';
        html += '<p class="small" style="text-align:center;">Thank you!</p>';

        html += '<script>window.print()';
        html += '</body></html>';

        w.document.write(html);
        w.document.close();

    } catch (err) {
        alert('POS Error: ' + err.message);
    }
}

//🔹 Delete confirmation

    function confirmDelete(form) {
        return confirm('Are you sure you want to delete this invoice? This action cannot be undone.');
    }
</script>
@endsection