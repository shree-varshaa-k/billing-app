@extends('layouts.layout')

@section('title','Create Purchase Invoice')

@section('content')

<style>
    body {
        background: #f3f6ff;
    }

    .invoice-card {
        background: white;
        border-radius: 18px;
        padding: 25px;
        box-shadow: 0px 8px 25px rgba(0,0,0,0.08);
        animation: fadeIn 0.8s ease-in-out;
    }

    .gradient-header {
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        padding: 20px;
        border-radius: 12px;
        margin-bottom: 25px;
        color: white;
        box-shadow: 0px 4px 15px rgba(0,0,0,0.15);
        animation: slideDown 0.7s ease;
    }

    @keyframes slideDown {
        from { transform: translateY(-20px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: scale(0.97); }
        to { opacity: 1; transform: scale(1); }
    }

    .btn-primary {
        background: #4f46e5;
        border: none;
        transition: 0.3s;
    }
    .btn-primary:hover {
        background: #4338ca;
        transform: scale(1.05);
    }

    #addRow {
        transition: 0.3s;
    }
    #addRow:hover {
        transform: scale(1.05);
        background: #6b7280 !important;
    }

    .table thead {
        background: #eef2ff;
    }

    .removeRow {
        transition: 0.3s;
    }
    .removeRow:hover {
        transform: scale(1.1);
        background: #dc2626 !important;
    }
</style>

{{-- SELECT2 CSS --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>

<div class="gradient-header">
    <h3 class="mb-0"><i class="fas fa-file-invoice"></i> Create Purchase Invoice</h3>
</div>

<div class="invoice-card">

<form action="{{ route('purchase.invoice.store') }}" method="POST">
    @csrf

    <div class="row mt-3">
        <div class="col-md-4">
            <label class="fw-bold">Supplier</label>
            <select name="supplier_id" class="form-control" required>
                <option value="">Select Supplier</option>
                @foreach($suppliers as $sup)
                    <option value="{{ $sup->id }}">{{ $sup->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-4">
            <label class="fw-bold">Date</label>
            <input type="date" name="invoice_date" class="form-control" required>
        </div>
    </div>

    <hr>

    <h5 class="fw-bold text-primary">Items</h5>

    <table class="table table-bordered table-hover" id="itemsTable">
        <thead>
        <tr class="text-center">
            <th>Product</th>
            <th>Qty</th>
            <th>Price</th>
            <th>GST %</th>
            <th>Total</th>
            <th>Action</th>
        </tr>
        </thead>

        <tbody>
        <tr>
            <td>
                <select name="product_id[]" class="form-control productSelect">
                    <option value="">Select</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" data-price="{{ $product->price }}">
                            {{ $product->name }}
                        </option>
                    @endforeach
                </select>
            </td>

            <td><input type="number" name="qty[]" class="form-control qty" value="1"></td>
            <td><input type="number" name="price[]" class="form-control price" value="0"></td>
            <td><input type="number" name="tax_percent[]" class="form-control tax_percent" value="0"></td>
            <td><input type="number" class="form-control item_total" readonly></td>

            <td class="text-center">
                <button type="button" class="btn btn-danger removeRow">X</button>
            </td>
        </tr>
        </tbody>
    </table>

    <button type="button" id="addRow" class="btn btn-dark mb-3">+ Add Item</button>

    <hr>

    <div class="row">
        <div class="col-md-3">
            <label class="fw-bold">Subtotal</label>
            <input type="text" id="subtotal_view" class="form-control" readonly>
        </div>

        <div class="col-md-3">
            <label class="fw-bold">GST Total</label>
            <input type="text" id="total_tax_view" class="form-control" readonly>
        </div>

        <div class="col-md-3">
            <label class="fw-bold">Discount</label>
            <input type="number" id="discount" name="discount" class="form-control" value="0" min="0">
        </div>

        <div class="col-md-3">
            <label class="fw-bold">Grand Total</label>
            <input type="text" id="grand_total_view" class="form-control" readonly>
        </div>
    </div>

    <input type="hidden" name="subtotal" id="subtotal">
    <input type="hidden" name="total_tax" id="total_tax">
    <input type="hidden" name="total" id="grand_total">

    <br>
    <button class="btn btn-primary px-4 py-2">💾 Save Invoice</button>

</form>

</div>

{{-- jQuery (mandatory for select2) --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

{{-- Select2 JS --}}
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>

// Enable Select2 with "tags" for new product entry
function activateSelect2() {
    $('.productSelect').select2({
        placeholder: "Search product...",
        allowClear: true,
        tags: true, // <-- enable typing new product
        width: "100%",
        createTag: function(params) {
            return {
                id: params.term,
                text: params.term,
                newOption: true
            };
        },
        templateResult: function(data) {
            var $result = $("<span></span>");
            $result.text(data.text);
            if (data.newOption) {
                $result.append(" <em>(add new)</em>");
            }
            return $result;
        }
    });
}

// Activate Select2 initially
activateSelect2();

function calc() {
    let subtotal = 0;
    let totalTax = 0;

    document.querySelectorAll('#itemsTable tbody tr').forEach(row => {
        let qty = Number(row.querySelector('.qty').value);
        let price = Number(row.querySelector('.price').value);
        let taxPercent = Number(row.querySelector('.tax_percent').value);

        let itemTotal = qty * price;
        let taxAmount = (itemTotal * taxPercent) / 100;

        row.querySelector('.item_total').value = (itemTotal + taxAmount).toFixed(2);

        subtotal += itemTotal;
        totalTax += taxAmount;
    });

    let discount = Number(document.getElementById('discount').value) || 0;
    let grandTotal = subtotal + totalTax - discount;

    document.getElementById('subtotal_view').value = subtotal.toFixed(2);
    document.getElementById('total_tax_view').value = totalTax.toFixed(2);
    document.getElementById('grand_total_view').value = grandTotal.toFixed(2);

    document.getElementById('subtotal').value = subtotal.toFixed(2);
    document.getElementById('total_tax').value = totalTax.toFixed(2);
    document.getElementById('grand_total').value = grandTotal.toFixed(2);
}

// Auto-fill price if selecting existing product
document.addEventListener('change', function(e){
    if(e.target.classList.contains('productSelect')){
        let row = e.target.closest('tr');
        let price = e.target.selectedOptions[0].getAttribute('data-price') || 0;
        row.querySelector('.price').value = price;
        calc();
    }
});

// Add new row
document.getElementById('addRow').addEventListener('click', function(){
    let tr = document.querySelector('#itemsTable tbody tr').cloneNode(true);
    tr.querySelectorAll('input').forEach(i => i.value = "");
    tr.querySelector('.qty').value = 1;

    document.querySelector('#itemsTable tbody').appendChild(tr);

    // Re-activate Select2 for new row with tags mode
    $(tr).find('.productSelect').select2({
        placeholder: "Search product...",
        allowClear: true,
        tags: true,
        width: "100%",
        createTag: function(params) {
            return {
                id: params.term,
                text: params.term,
                newOption: true
            };
        },
        templateResult: function(data) {
            var $result = $("<span></span>");
            $result.text(data.text);
            if (data.newOption) {
                $result.append(" <em>(add new)</em>");
            }
            return $result;
        }
    });
});

// Remove row
document.addEventListener('click', function(e){
    if(e.target.classList.contains('removeRow')){
        if(document.querySelectorAll('#itemsTable tbody tr').length > 1){
            e.target.closest('tr').remove();
            calc();
        }
    }
});

document.addEventListener('input', calc);

</script>

@endsection
