@extends('layouts.layout')

@section('content')
<style>
    body { color: #000; }
    .invoice-container { padding: 30px; background: #fff; border-radius: 10px; }
    .table thead { background-color: #40a9ff; color: #fff; }
    .table tbody tr:nth-child(even) { background-color: #f1f9ff; }
    .form-control, .form-select { color: #000; border: 1px solid #40a9ff; }
    .btn { border-radius: 8px; }
    .action-btn { display: flex; gap: 5px; justify-content: center; }
    .add-item, .remove-item {
        width: 32px; height: 32px; border: none;
        color: white; border-radius: 5px; font-weight: bold;
    }
    .add-item { background-color: #00c853; }
    .remove-item { background-color: #ff4d4f; }
    .alert-msg {
        display: none; text-align: center;
        background: #ffe6e6; color: #b71c1c;
        padding: 8px; border-radius: 6px; margin-bottom: 10px;
    }


    .action-btn {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 6px;
}

.action-btn-icon {
    width: 32px;
    height: 32px;
    display: flex !important;
    justify-content: center;
    align-items: center;
    padding: 0 !important;
    font-size: 18px;
    line-height: 1 !important; /* baseline fix */
}


   /* ✅ Responsive Media Queries */
/*  Mobile Devices (max-width: 576px) */
@media (max-width: 576px) {
    .invoice-container {
        padding: 15px;
        border-radius: 6px;
    }

    h3.text-info {
        font-size: 1.2rem;
        text-align: center;
    }

    .d-flex.justify-content-between {
        flex-direction: column;
        align-items: center;
        gap: 10px;
    }

    .btn {
        font-size: 0.85rem;
        padding: 6px 10px;
    }

    .table {
        font-size: 0.8rem;
        display: block;
        overflow-x: auto;
        white-space: nowrap;
    }

    .form-control,
    .form-select {
        font-size: 0.85rem;
        padding: 6px;
    }

    .col-md-3,
    .col-md-4 {
        text-align: left !important;
    }

    .text-end {
        text-align: left !important;
    }

    .modal-dialog {
        width: 95% !important;
        margin: auto;
    }

    #previewBtn,
    #downloadBtn,
    #printBtn {
        padding: 4px 8px;
        font-size: 0.8rem;
    }
}

/*  Tablets (min-width: 577px) and (max-width: 992px) */
@media (min-width: 577px) and (max-width: 992px) {
    .invoice-container {
        padding: 25px;
    }

    h3.text-info {
        font-size: 1.4rem;
    }

    .btn {
        font-size: 0.9rem;
        padding: 7px 12px;
    }

    .table {
        font-size: 0.9rem;
    }

    .col-md-3 {
        width: 30%;
    }

    .col-md-4 {
        width: 60%;
    }

    .text-end {
        text-align: right !important;
    }

    #previewBtn,
    #downloadBtn,
    #printBtn {
        font-size: 0.9rem;
    }
}

/* Desktop Devices (min-width: 993px) */
@media (min-width: 993px) {
    .invoice-container {
        padding: 30px;
    }

    h3.text-info {
        font-size: 1.6rem;
    }

    .table {
        font-size: 1rem;
    }

    .btn {
        font-size: 1rem;
        padding: 8px 14px;
    }

    #previewBtn,
    #downloadBtn,
    #printBtn {
        font-size: 1rem;
    }
}

</style>

<div class="invoice-container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="text-info fw-bold">Create Invoice</h3>
        <a href="{{ route('invoices.index') }}" class="btn btn-outline-primary btn-sm">View History</a>
    </div>

    <div id="alertBox" class="alert-msg"></div>

    <!-- Buttons at the Top-Right -->
    <div class="d-flex justify-content-end mb-3">
        <button type="button" id="previewBtn" class="btn btn-outline-primary me-2"><i class="bi bi-eye-fill me-1"></i></button>
        <button type="button" id="downloadBtn" class="btn btn-success me-2"><i class="bi bi-download me-1"></i></button>
        <button type="button" id="printBtn" class="btn btn-primary"><i class="bi bi-printer-fill me-1"></i></button>
    </div>

    <form id="invoiceForm" method="POST" action="{{ route('invoices.store') }}">
        @csrf

        <!-- Customer -->
        <div class="row mb-3">
            <div class="col-md-3 fw-bold text-end">
                <label>Customer Name</label>
            </div>
            <div class="col-md-4">
                <select id="customer" name="client_id" class="form-select" required>
                    <option value="">Select customer</option>
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}">{{ $client->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-5 ">
                <label for="invoice_date" class="form-label">Invoice Date</label>
                <input 
                    type="date" 
                    id="invoice_date" 
                    name="invoice_date" 
                    class="form-control" 
                    value="{{ old('invoice_date', date('Y-m-d')) }}" 
                    required>
            </div>
        </div>

        <!-- Status -->
        <div class="row mb-3">
            <div class="col-md-3 fw-bold text-end">
                <label>Status</label>
            </div>
            <div class="col-md-4">
                <select name="status" class="form-select" required>
                    <option value="Paid">Paid</option>
                    <option value="Unpaid">Unpaid</option>
                    <option value="Partially Paid">Partially Paid</option>
                </select>
            </div>
           

        </div>
        <!-- Partially Paid Fields -->
        <div id="partialPaymentSection" style="display:none;">
            <div class="row mb-3">
                <div class="col-md-3 fw-bold text-end">
                    <label>Paid Amount (₹)</label>
                </div>
                <div class="col-md-4">
                    <input type="number" id="paidAmount" name="paid_amount" class="form-control" min="0" step="0.01" value="0">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3 fw-bold text-end">
                    <label>Remaining Amount (₹)</label>
                </div>
                <div class="col-md-4">
                    <input type="number" id="remainingAmount" name="remaining_amount" class="form-control" readonly value="0">
                </div>
            </div>
        </div>

        <!-- Invoice Items -->
        <table class="table table-bordered text-center align-middle">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Available Stock</th>
                    <th>Enter Stock</th>
                    <th>Price (₹)</th>
                    <th>Discount (%)</th>
                    <th>Total (₹)</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="invoice-items">
                <tr>
                    <td>
                        <select class="form-select item" name="product_id[]">
                            <option value="">Select Product</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td><input type="number" class="form-control stock" value="0" readonly></td>
                    <td><input type="number" class="form-control qty" name="quantity[]" value="1" min="1" step="1"></td>
                    <td><input type="number" class="form-control price" name="price[]" value="0" min="0" step="0.01" readonly></td>
                          <!-- ⭐ New Discount Column -->
                    <td><input type="number" class="form-control discount" name="discount[]" value="5"></td>

                    <td class="item-total">₹0</td>
                    <td class="action-btn">
                    <button type="button" class="add-item action-btn-icon btn btn-success btn-sm">✔</button>
                    <button type="button" class="remove-item action-btn-icon btn btn-danger btn-sm">✕</button>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Totals -->
        <div class="row mt-4">
            <div class="col-md-8"></div>
            <div class="col-md-4">
                <table class="table border-0">
                    <tr>
                        <td class="fw-bold">Subtotal</td>
                        <td class="text-end" id="subtotal">₹0</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">GST (%)</td>
                        <td class="text-end">
                            <input type="number" id="gstInput" name="tax"
                                   class="form-control text-end d-inline-block"
                                   value="18" min="0" max="100" style="width:80px;">
                        </td>
                    </tr>
                    <tr class="table-primary text-dark">
                        <td class="fw-bold">Grand Total</td>
                        <td class="text-end fw-bold" id="grand-total">₹0</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="text-end mt-3">
            <button type="submit" class="btn btn-success">Save Invoice</button>
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
    const invoiceForm = $('#invoiceForm');

    // Calculate totals
    function calculateTotals() {
    let subtotal = 0;

    $('#invoice-items tr').each(function () {
        const qty = parseFloat($(this).find('.qty').val()) || 0;
        const price = parseFloat($(this).find('.price').val()) || 0;
        const discount = parseFloat($(this).find('.discount').val()) || 0;

        // ⭐ Apply discount
        const beforeDiscount = qty * price;
        const discountAmount = (beforeDiscount * discount) / 100;
        const total = beforeDiscount - discountAmount;

        $(this).find('.item-total').text('₹' + total.toFixed(2));

        subtotal += total;
    });

    const gstRate = parseFloat($('#gstInput').val()) || 18;
    const gst = subtotal * gstRate / 100;
    const grand = subtotal + gst;

    $('#subtotal').text('₹' + subtotal.toFixed(2));
    $('#grand-total').text('₹' + grand.toFixed(2));

    // ⭐ Update Remaining for Partially Paid
    if ($('select[name="status"]').val() === 'Partially Paid') {
        const paid = parseFloat($('#paidAmount').val()) || 0;
        const remaining = grand - paid;
        $('#remainingAmount').val(remaining.toFixed(2));
    }
}


    // Handle Partially Paid Logic
    $('select[name="status"]').on('change', function() {
        const status = $(this).val();
        if (status === 'Partially Paid') {
            $('#partialPaymentSection').slideDown();
        } else {
            $('#partialPaymentSection').slideUp();
            $('#paidAmount').val(0);
            $('#remainingAmount').val(0);
        }
        calculateTotals();
    });

    // When user enters Paid Amount
    $(document).on('input', '#paidAmount', function() {
        const grandTotalText = $('#grand-total').text().replace('₹', '') || 0;
        const grandTotal = parseFloat(grandTotalText);
        let paid = parseFloat($(this).val()) || 0;

        if (paid > grandTotal) {
            alert('Paid amount cannot exceed Grand Total.');
            paid = grandTotal;
            $(this).val(grandTotal.toFixed(2));
        }

        const remaining = grandTotal - paid;
        $('#remainingAmount').val(remaining.toFixed(2));
    });

    // ---- rest of your original JS unchanged ----
    $(document).on('change', '.item', function () {
        const row = $(this).closest('tr');
        const productId = $(this).val();

        if (!productId) {
            row.find('.stock').val(0);
            row.find('.price').val(0);
            row.find('.qty').val(1).prop('max', 1);
            row.find('.add-item').prop('disabled', true);
            calculateTotals();
            return;
        }

        $.ajax({
            url: '/get-product-stock/' + productId,
            type: 'GET',
            success: function (data) {
                if (data.out_of_stock) {
                    row.find('.stock').val(0);
                    row.find('.price').val(data.price ?? 0);
                    row.find('.qty').val(0).prop('max', 0).prop('min', 0).prop('disabled', true);
                    row.find('.add-item').prop('disabled', true);
                    alert(row.find('.item option:selected').text() + ' is Out of Stock.');
                } else {
                    row.find('.stock').val(data.stock);
                    row.find('.price').val(parseFloat(data.price).toFixed(2));
                    row.find('.qty').prop('disabled', false).prop('min', 1).prop('max', data.stock);
                    let currentQty = parseInt(row.find('.qty').val()) || 1;
                    if (currentQty > data.stock) {
                        row.find('.qty').val(data.stock);
                    } else if (currentQty <= 0) {
                        row.find('.qty').val(1);
                    }
                    row.find('.add-item').prop('disabled', false);
                }
                calculateTotals();
            }
        });
    });

    $(document).on('input', '.qty', function () {
        const row = $(this).closest('tr');
        const qty = parseInt($(this).val()) || 0;
        const stock = parseInt(row.find('.stock').val()) || 0;
        if (qty > stock) {
            alert('Quantity cannot exceed available stock (' + stock + ').');
            $(this).val(stock);
        } else if (qty < 0) {
            $(this).val(1);
        }
        calculateTotals();
    });

    $(document).on('click', '.add-item', function () {
        const row = $(this).closest('tr');
        const productId = row.find('.item').val();
        const qty = parseInt(row.find('.qty').val()) || 0;
        const stock = parseInt(row.find('.stock').val()) || 0;

        if (!productId) {
            alert('Please select a product first.');
            return;
        }
        if (stock <= 0) {
            alert('Product is out of stock.');
            return;
        }
        if (qty <= 0) {
            alert('Please enter a valid quantity.');
            return;
        }

        const newRow = `
            <tr>
                <td>
                    <select class="form-select item" name="product_id[]">
                        <option value="">Select Product</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                        @endforeach
                    </select>
                </td>
                <td><input type="number" class="form-control stock" value="0" readonly></td>
                <td><input type="number" class="form-control qty" name="quantity[]" value="1" min="1" step="1"></td>
                <td><input type="number" class="form-control price" name="price[]" value="0" min="0" step="0.01" readonly></td>
                <td><input type="number" class="form-control discount" name="discount[]" value="0" min="0" max="100"></td>
                <td class="item-total">₹0</td>
                <td class="action-btn">
                    <button type="button" class="add-item btn btn-success btn-sm">✔</button>
                    <button type="button" class="remove-item btn btn-danger btn-sm">✕</button>
                </td>
            </tr>
        `;
        tableBody.append(newRow);
        calculateTotals();
    });

    $(document).on('click', '.remove-item', function () {
        if ($('#invoice-items tr').length > 1) {
            $(this).closest('tr').remove();
            calculateTotals();
        } else {
            alert('At least one product row is required.');
        }
    });

    gstInput.on('input', calculateTotals);

    invoiceForm.on('submit', function (e) {
        if (!$('#customer').val()) {
            alert('Please select a customer.');
            e.preventDefault();
            return;
        }

        let hasValidProduct = false;
        let invalid = false;

        tableBody.find('tr').each(function () {
            const pid = $(this).find('.item').val();
            const qty = parseInt($(this).find('.qty').val()) || 0;
            const stock = parseInt($(this).find('.stock').val()) || 0;
            if (pid) {
                if (stock <= 0) {
                    alert('One selected product is out of stock.');
                    invalid = true; return false;
                }
                if (qty <= 0) {
                    alert('Quantity must be at least 1.');
                    invalid = true; return false;
                }
                if (qty > stock) {
                    alert('A line item quantity exceeds available stock.');
                    invalid = true; return false;
                }
                hasValidProduct = true;
            }
        });

        if (invalid) { e.preventDefault(); return; }
        if (!hasValidProduct) {
            alert('Please select at least one product.');
            e.preventDefault(); return;
        }

        // Check Partially Paid validity
        if ($('select[name="status"]').val() === 'Partially Paid') {
            const grandTotalText = $('#grand-total').text().replace('₹', '') || 0;
            const grandTotal = parseFloat(grandTotalText);
            const paid = parseFloat($('#paidAmount').val()) || 0;
            if (paid <= 0 || paid >= grandTotal) {
                alert('Please enter a valid paid amount less than total.');
                e.preventDefault();
                return;
            }
        }
    });

    calculateTotals();
});

//preview & print,download
$('#previewBtn').on('click', function () {
    let customer = $('#customer option:selected').text();
    let status = $('select[name="status"]').val();
    let date = '{{ date("Y-m-d") }}';
    let gstRate = parseFloat($('#gstInput').val()) || 18;
    let paidAmount = parseFloat($('#paidAmount').val()) || 0;
    let remainingAmount = parseFloat($('#remainingAmount').val()) || 0;

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

    let partialInfo = "";
    if (status === "Partially Paid") {
        partialInfo = `
            <tr>
                <th colspan="3" class="text-end">Paid Amount</th>
                <td>₹${paidAmount.toFixed(2)}</td>
            </tr>
            <tr>
                <th colspan="3" class="text-end">Remaining Amount</th>
                <td>₹${remainingAmount.toFixed(2)}</td>
            </tr>
        `;
    }

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
                ${partialInfo}
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
    setTimeout(() => {
        let printContents = document.getElementById('invoiceSlipBody').innerHTML;
        let originalContents = document.body.innerHTML;
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
        const invoiceContent = document.getElementById("invoiceSlipBody");
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
</script>

@endsection
