@extends('layouts.layout')

@section('content')
<div class="container">

    <h3 class="fw-bold mb-4">Profit & Loss Report</h3>

    {{-- Filter Form --}}
    <form method="GET" action="{{ route('reports.profitloss') }}" class="card p-3 mb-3 shadow-sm">
        <div class="row g-3">
            <div class="col-md-4">
                <label>From Date</label>
                <input type="date" name="from_date" value="{{ $from_date }}" class="form-control">
            </div>

            <div class="col-md-4">
                <label>To Date</label>
                <input type="date" name="to_date" value="{{ $to_date }}" class="form-control">
            </div>

            <div class="col-md-4 d-flex align-items-end">
                <button class="btn btn-primary w-100">Filter</button>
            </div>
        </div>
    </form>

    {{-- Report Table --}}
    <div class="card shadow-sm p-4">
        <h5 class="fw-bold mb-3">Summary</h5>

        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th width="60%">Description</th>
                    <th width="40%" class="text-end">Amount (₹)</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td>Total Sales</td>
                    <td class="text-end">{{ number_format($total_sales, 2) }}</td>
                </tr>

                <tr>
                    <td>Total Purchase</td>
                    <td class="text-end">{{ number_format($total_purchase, 2) }}</td>
                </tr>

                <tr class="{{ $profit >= 0 ? 'table-success' : 'table-danger' }}">
                    <td class="fw-bold">{{ $profit >= 0 ? 'Net Profit' : 'Net Loss' }}</td>
                    <td class="fw-bold text-end">
                        {{ number_format($profit, 2) }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

</div>
@endsection
