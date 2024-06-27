<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // tampilkan view
        return view('report.index');
    }

    /**
     * filter
     */
    public function filter(Request $request): View
    {
        // validasi form
        $request->validate([
            'start_date' => 'required',
            'end_date'   => 'required|date|after_or_equal:start_date'
        ]);

        // data filter
        $startDate = $request->start_date;
        $endDate   = $request->end_date;

        // menampilkan data berdasarkan filter
        $transactions = Transaction::with('customer:id,name', 'product:id,name,price')
            ->whereBetween('date', [$startDate, $endDate])
            ->oldest()
            ->get();

        // tampilkan data ke view
        return view('report.index', compact('transactions'));
    }

    /**
     * print (PDF)
     */
    public function print($startDate, $endDate)
    {   
        // menampilkan data berdasarkan filter
        $transactions = Transaction::with('customer:id,name', 'product:id,name,price')
            ->whereBetween('date', [$startDate, $endDate])
            ->oldest()
            ->get();

        // load view PDF
        $pdf = Pdf::loadview('report.print', compact('transactions'))->setPaper('a4', 'landscape');
        // tampilkan ke browser
        return $pdf->stream('Transactions.pdf');
    }
}