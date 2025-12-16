<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\Product;

class DashboardController extends Controller
{
    public function index()
    {
        // ====== 🧾 Invoice Summary ======
        $totalInvoices = Invoice::count();

        $unpaidInvoices = Invoice::whereIn('status', ['Pending', 'Unpaid', 'pending', 'unpaid'])->sum('total');
        $partialInvoices = Invoice::whereIn('status', ['Partially Paid', 'partially paid'])->sum('paidamount');
        $paidInvoices = Invoice::whereIn('status', ['Paid', 'paid'])->sum('total');

        $clients = Client::latest()->take(5)->get();

        // ====== 📦 Product Stock Chart ======
        $lowStock = Product::where('stock', '<', 5)->count();
        $mediumStock = Product::whereBetween('stock', [5, 20])->count();
        $inStock = Product::where('stock', '>', 20)->count();
        $totalStock = Product::count();

        // ====== 👥 Clients Chart (New Clients per Month) ======
        $clientsPerMonth = Client::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $monthNames = [];
        $clientCounts = [];

        foreach ($clientsPerMonth as $month => $count) {
            $monthNames[] = date("F", mktime(0, 0, 0, $month, 1));
            $clientCounts[] = $count;
        }

        // ====== 📊 Return Data to Dashboard ======
        return view('dashboard', compact(
            'totalInvoices',
            'unpaidInvoices',
            'partialInvoices',
            'paidInvoices',
            'clients',
            'lowStock',
            'mediumStock',
            'inStock',
            'totalStock',
            'monthNames',
            'clientCounts'
        ));
    }
}
