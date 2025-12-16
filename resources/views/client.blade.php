@extends('layouts.layout')
@section('title', 'Clients')

@section('content')
<style>
/*  Mobile devices (up to 576px) */
@media (max-width: 576px) {
    .container-fluid {
        padding: 10px;
    }

    h3 {
        font-size: 1.2rem;
        text-align: center;
        margin-bottom: 1rem;
    }

    /* Add Client button below heading */
    .d-flex.justify-content-between {
        flex-direction: column;
        align-items: stretch !important;
        gap: 10px;
    }

    .btn.btn-sm.btn-primary {
        width: 100%;
        font-size: 0.9rem;
    }

    /* Table scroll & font adjustments */
    .table {
        font-size: 0.8rem;
        white-space: nowrap;
    }

    .table th,
    .table td {
        padding: 6px;
    }

    .btn-sm {
        padding: 4px 6px;
        font-size: 0.8rem;
    }
}

/*  Tablet devices (577px to 992px) */
@media (min-width: 577px) and (max-width: 992px) {
    .container-fluid {
        padding: 15px;
    }

    h3 {
        font-size: 1.4rem;
    }

    .btn-sm {
        font-size: 0.85rem;
        padding: 5px 8px;
    }

    .table {
        font-size: 0.9rem;
    }

    .table th,
    .table td {
        padding: 8px;
    }
}

/* Desktop devices (above 992px) */
@media (min-width: 993px) {
    h3 {
        font-size: 1.6rem;
    }

    .table {
        font-size: 1rem;
    }

    .table th,
    .table td {
        padding: 10px;
    }
}
</style>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="text-primary fw-bold">Clients</h3>
        <a href="{{ route('clients.create') }}" class="btn btn-sm btn-primary">+ Add Client</a>
    </div>

    <table class="table table-bordered table-hover align-middle">
    <thead class="table-primary">
        <tr>
            <th>SI.NO</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th width="160">Actions</th>
        </tr>
    </thead>
   <tbody>
    @forelse($clients as $index => $client)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $client->name }}</td>
            <td>{{ $client->email }}</td>
            <td>{{ $client->phone }}</td>
            <td>
                <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-sm btn-secondary"><i class="bi bi-pencil-square"></i></a>
                <form action="{{ route('clients.destroy', $client->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                </form>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="5" class="text-center text-muted">No clients found.</td>
        </tr>
    @endforelse
</tbody>

</table>

</div>
@endsection
