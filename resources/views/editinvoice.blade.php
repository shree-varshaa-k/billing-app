@extends('layouts.layout')

@section('content')
<style>
/* =========================
   📱 Responsive Media Queries
   ========================= */

/* Small devices (max-width: 576px) */
@media (max-width: 576px) {
    .invoice-container {
        padding: 15px;
        border-radius: 0;
    }
    h3 {
        font-size: 1.25rem;
    }
    .table thead {
        font-size: 0.8rem;
    }
    .table tbody, .table td {
        font-size: 0.75rem;
    }
    .d-flex {
        flex-direction: column !important;
        align-items: stretch !important;
    }
    .d-flex .btn {
        width: 100%;
        margin-bottom: 8px;
    }
    .col-md-3, .col-md-4 {
        text-align: left !important;
        width: 100%;
    }
    .text-end {
        text-align: left !important;
    }
    .modal-dialog {
        max-width: 95%;
        margin: 10px auto;
    }
}

/* Medium devices (min-width: 577px) and (max-width: 768px) */
@media (min-width: 577px) and (max-width: 768px) {
    .invoice-container {
        padding: 20px;
    }
    h3 {
        font-size: 1.5rem;
    }
    .table th, .table td {
        font-size: 0.85rem;
    }
    .btn {
        font-size: 0.9rem;
        padding: 6px 10px;
    }
    .modal-dialog {
        max-width: 90%;
    }
}

/* Large devices (min-width: 769px) and (max-width: 1024px) */
@media (min-width: 769px) and (max-width: 1024px) {
    .invoice-container {
        padding: 25px;
    }
    .table th, .table td {
        font-size: 0.9rem;
    }
    h3 {
        font-size: 1.75rem;
    }
    .btn {
        padding: 8px 14px;
    }
}

/* Extra large screens (min-width: 1200px) */
@media (min-width: 1200px) {
    .invoice-container {
        max-width: 1100px;
        margin: auto;
    }
    .table th, .table td {
        font-size: 1rem;
    }
}
</style>

<style>
    body { color: #000; }
    .invoice-container { padding: 30px; background: #fff; border-radius: 10px; }
    .table thead { background-color: #40a9ff; color: #fff; }
    .table tbody tr:nth-child(even) { background-color: #f1f9ff; }
    .form-control, .form-select { color: #000; border: 1px solid #40a9ff; }
    .btn { border-radius: 8px; }
    .readonly-select {
        pointer-events: none;
        background-color: #e9ecef !important;
        color: #495057 !important;
    }
</style>

<div class="invoice-container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="text-info fw-bold">Edit Invoice</h3>
        <a href="{{ route('invoices.index') }}" class="btn btn-outline-primary btn-sm">Back to History</a>
    </div>
     <!-- Buttons at the Top-Right -->
<div class="d-flex justify-content-end mb-3">
    <button type="button" id="previewBtn" class="btn btn-outline-primary me-2"><i class="bi bi-eye-fill me-1"></i></button>
    <button type="button" id="downloadBtn" class="btn btn-success me-2"><i class="bi bi-download me-1"></i></button>
    <button type="button" id="printBtn" class="btn btn-primary"><i class="bi bi-printer-fill me-1"></i></button>
</div>


    <form method="POST" action="{{ route('invoices.update', $invoice->id) }}" id="editInvoiceForm">
        @csrf
        @method('PUT')

        <!-- Customer -->
        <div class="row mb-3">
            <div class="col-md-3 fw-bold text-end">
                <label>Customer Name</label>
            </div>
            <div class="col-md-4">
                <select name="client_id" class="form-select" required>
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}" {{ $client->id == $invoice->client_id ? 'selected' : '' }}>
                            {{ $client->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Status -->
        <div class="row mb-3">
            <div class="col-md-3 fw-bold text-end"><label>Status</label></div>
            <div class="col-md-4">
                <select id="statusSelect" name="status" class="form-select {{ $invoice->status == 'Partially Paid' ? 'readonly-select' : '' }}" {{ $invoice->status == 'Partially Paid' ? 'disabled' : '' }}>
                    <option value="Paid" {{ $invoice->status == 'Paid' ? 'selected' : '' }}>Paid</option>
                    <option value="Unpaid" {{ $invoice->status == 'Unpaid' ? 'selected' : '' }}>Unpaid</option>
                    <option value="Partially Paid" {{ $invoice->status == 'Partially Paid' ? 'selected' : '' }}>Partially Paid</option>
                </select>
                @if($invoice->status == 'Partially Paid')
                    <!-- Keep a hidden input to ensure value is still submitted -->
                    <input type="hidden" name="status" value="Partially Paid">
                @endif
            </div>
        </div>

        <!-- Partial Payment Section -->
        <div id="partialPaymentSection" @if($invoice->status !== 'Partially Paid') style="display: none;" @endif>
            <div class="row mb-3">
                <div class="col-md-3 fw-bold text-end"><label>Already Paid (₹)</label></div>
                <div class="col-md-4">
                    <input type="number" id="alreadyPaid" name="already_paid" class="form-control" 
                           value="{{ $invoice->paidamount ?? 0 }}" readonly>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-3 fw-bold text-end"><label>Remaining Amount (₹)</label></div>
                <div class="col-md-4">
                    <input type="number" id="remainingAmount" name="remaining_amount" class="form-control" readonly value="{{ $invoice->remainingamount ?? 0 }}">
                </div>
            </div>
        </div>

        <!-- Items -->
        <table class="table table-bordered text-center align-middle">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Available Stock</th>
                    <th>Enter the Stock</th>
                    <th>Price (₹)</th>
                    <th>Total (₹)</th>
                </tr>
            </thead>
            <tbody id="invoice-items">
                @foreach($invoice->items ?? [] as $item)
                    <tr>
                        <td>
                            <select name="product_id[]" class="form-select item">
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" {{ $product->id == $item->product_id ? 'selected' : '' }}>
                                        {{ $product->name }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td><input type="number" class="form-control stock" value="{{ $item->product->stock ?? 0 }}" readonly></td>
                        <td><input type="number" name="quantity[]" class="form-control qty" value="{{ $item->quantity }}" min="1" step="1"></td>
                        <td><input type="number" name="price[]" class="form-control price" value="{{ $item->price }}" readonly></td>
                        <td class="item-total">₹{{ number_format($item->quantity * $item->price, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals -->
        <div class="row mt-4">
            <div class="col-md-8"></div>
            <div class="col-md-4">
                <table class="table border-0">
                    <tr>
                        <td class="fw-bold">GST (%)</td>
                        <td class="text-end">
                            <input type="number" id="gstInput" name="tax" class="form-control text-end d-inline-block"
                                   value="{{ $invoice->tax ?? 18 }}" min="0" max="100" style="width:80px;">
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Subtotal</td>
                        <td class="text-end fw-bold" id="subtotal">₹0</td>
                    </tr>
                    <tr class="table-primary text-dark">
                        <td class="fw-bold">Grand Total</td>
                        <td class="text-end fw-bold" id="grand-total">₹{{ number_format($invoice->total, 2) }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="text-end mt-3">
            <button type="submit" class="btn btn-success">Update Invoice</button>
        </div>
    </form>
     <!-- INVOICE SLIP MODAL -->
<div class="modal fade" id="invoiceSlipModal" tabindex="-1" aria-labelledby="invoiceSlipLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content" id="invoiceSlipContent">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="invoiceSlipLabel">Invoice Slip Preview</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="invoiceSlipBody" style="color:#000; background:#fff;">
      </div>
    </div>
  </div>
</div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
$(document).ready(function () {
    const tableBody = $('#invoice-items');
    const gstInput = $('#gstInput');
    const statusSelect = $('#statusSelect');
    const partialSection = $('#partialPaymentSection');

    function togglePartialSection() {
        if (statusSelect.val() === 'Partially Paid') {
            partialSection.slideDown();
        } else {
            partialSection.slideUp();
        }
    }
    togglePartialSection();

    function calculateTotals() {
        let subtotal = 0;
        tableBody.find('tr').each(function () {
            const qty = parseFloat($(this).find('.qty').val()) || 0;
            const price = parseFloat($(this).find('.price').val()) || 0;
            const total = qty * price;
            $(this).find('.item-total').text('₹' + total.toFixed(2));
            subtotal += total;
        });

        const gstRate = parseFloat(gstInput.val()) || 18;
        const gst = subtotal * gstRate / 100;
        const grand = subtotal + gst;

        $('#subtotal').text('₹' + subtotal.toFixed(2));
        $('#grand-total').text('₹' + grand.toFixed(2));

        // Update Remaining for partial
        if (statusSelect.val() === 'Partially Paid') {
            const already = parseFloat($('#alreadyPaid').val()) || 0;
            const remaining = grand - already;
            $('#remainingAmount').val(remaining.toFixed(2));
        }
    }

    $(document).on('input', '.qty', calculateTotals);
    gstInput.on('input', calculateTotals);

    calculateTotals();
    //preview & print,download 
$('#previewBtn').on('click', function () {
    let customer = $('#customer option:selected').text();
    let status = $('select[name="status"]').val();
    let date = '{{ date("Y-m-d") }}';
    let gstRate = parseFloat($('#gstInput').val()) || 18;

    let items = [];
    let subtotal = 0;

    $('#invoice-items tr').each(function () {
        let item = $(this).find('.item option:selected').text();
        let qty = parseInt($(this).find('.qty').val()) || 0;
        let price = parseFloat($(this).find('.price').val()) || 0;
        let total = qty * price;

        if (item !== "Select Product" && qty > 0) {
            items.push({ item, qty, price, total });
            subtotal += total;
        }
    });

    let gst = subtotal * gstRate / 100;
    let grand = subtotal + gst;

    // --- Build slip HTML ---
    let slipHTML = `
        <div style="text-align:center;">
            <h4 style="color:#007bff; margin-bottom:5px;">Company Name / Billing Software</h4>
            <p style="font-size:13px;">123 Business Street, City - 000000<br>Phone: +91 XXXXX XXXXX</p>
            <hr>
            <h5><strong>INVOICE SLIP</strong></h5>
        </div>

        <div style="margin-top:15px;">
            <p><strong>Customer:</strong> ${customer}</p>
            <p><strong>Status:</strong> ${status}</p>
            <p><strong>Date:</strong> ${date}</p>
        </div>

        <table class="table table-bordered text-center mt-3">
            <thead class="table-light">
                <tr>
                    <th>Item</th>
                    <th>Qty</th>
                    <th>Price (₹)</th>
                    <th>Total (₹)</th>
                </tr>
            </thead>
            <tbody>
                ${items.map(i => `
                    <tr>
                        <td>${i.item}</td>
                        <td>${i.qty}</td>
                        <td>₹${i.price.toFixed(2)}</td>
                        <td>₹${i.total.toFixed(2)}</td>
                    </tr>
                `).join('')}
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3" class="text-end">Subtotal</th>
                    <td>₹${subtotal.toFixed(2)}</td>
                </tr>
                <tr>
                    <th colspan="3" class="text-end">GST (${gstRate}%)</th>
                    <td>₹${gst.toFixed(2)}</td>
                </tr>
                <tr class="table-primary">
                    <th colspan="3" class="text-end">Grand Total</th>
                    <td><strong>₹${grand.toFixed(2)}</strong></td>
                </tr>
            </tfoot>
        </table>

        <p class="text-center mt-3" style="font-size:13px;">Thank you for your business!</p>
    `;

    $('#invoiceSlipBody').html(slipHTML);
    $('#invoiceSlipModal').modal('show');
});

$('#printBtn').on('click', function () {
    $('#previewBtn').click();

    // Wait a moment for the modal to populate
    setTimeout(() => {
        let printContents = document.getElementById('invoiceSlipBody').innerHTML;
        let originalContents = document.body.innerHTML;

        // Print only slip
        document.body.innerHTML = `
            <div style="width:700px; margin:auto; font-family:Arial; color:#000;">
                ${printContents}
            </div>
        `;
        window.print();
        document.body.innerHTML = originalContents;
        location.reload();
    }, 500);
});

document.getElementById("downloadBtn").addEventListener("click", function () {
    $('#previewBtn').click();

    setTimeout(() => {
        const invoiceContent = document.getElementById("invoiceSlipBody"); // from modal preview

        const options = {
            margin: 0.5,
            filename: 'Invoice.pdf',
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { scale: 2 },
            jsPDF: { unit: 'in', format: 'a4', orientation: 'portrait' }
        };

        html2pdf().set(options).from(invoiceContent).save();
    }, 600);
});
});

</script>
@endsection
