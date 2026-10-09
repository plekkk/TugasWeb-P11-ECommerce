<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminReportController extends Controller
{
    public function index(Request $request): View
    {
        $query = Order::with('user');

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('start_date')) {
            $query->whereDate('ordered_at', '>=', $request->input('start_date'));
        }

        if ($request->filled('end_date')) {
            $query->whereDate('ordered_at', '<=', $request->input('end_date'));
        }

        $orders = $query->latest('ordered_at')->paginate(15)->withQueryString();

        $totalOrders = Order::count();

        $completedOrders = Order::where('status', 'selesai')->count();

        $revenue = Order::where('status', 'selesai')->sum('total_amount');

        $pendingOrders = Order::whereIn('status', ['pending', 'diproses'])->count();

        return view('admin.reports.index', compact(
            'orders',
            'totalOrders',
            'completedOrders',
            'revenue',
            'pendingOrders'
        ));
    }

    public function export(Request $request)
    {
        $query = Order::with('user');

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('start_date')) {
            $query->whereDate('ordered_at', '>=', $request->input('start_date'));
        }

        if ($request->filled('end_date')) {
            $query->whereDate('ordered_at', '<=', $request->input('end_date'));
        }

        $orders = $query->latest('ordered_at')->get();

        $filename = 'laporan-transaksi-' . now()->format('Y-m-d_H-i-s') . '.csv';

        return response()->streamDownload(function () use ($orders) {
            $output = fopen('php://output', 'w');

            // BOM agar karakter Indonesia terbaca baik di Excel.
            fwrite($output, "\xEF\xBB\xBF");

            fputcsv($output, [
                'Nomor Pesanan',
                'Nama Pelanggan',
                'Tanggal Pesanan',
                'Metode Pembayaran',
                'Total Transaksi',
                'Status'
            ], ';');

            foreach ($orders as $order) {
                fputcsv($output, [
                    $order->order_number,
                    $order->user?->name ?? 'Pelanggan',
                    $order->ordered_at,
                    $order->payment_method,
                    $order->total_amount,
                    $order->status
                ], ';');
            }

            fclose($output);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }}