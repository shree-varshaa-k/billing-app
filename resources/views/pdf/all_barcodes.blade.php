<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: sans-serif;
        }
        .barcode-box {
            width: 30%;
            display: inline-block;
            text-align: center;
            margin: 10px;
            padding: 10px;
            border: 1px solid #ddd;
        }
        img {
            width: 180px;
            height: auto;
        }
        .name {
            font-size: 14px;
            margin-top: 6px;
            font-weight: bold;
        }
    </style>
</head>
<body>

<h2 style="text-align:center; margin-bottom:20px;">All Product Barcodes</h2>

@foreach($products as $product)
    <div class="barcode-box">
        @if($product->barcode)
            <img src="{{ public_path('storage/'.$product->barcode) }}">
            <div class="name">{{ $product->name }}</div>
        @else
            <p>No Barcode</p>
        @endif
    </div>
@endforeach

</body>
</html>
