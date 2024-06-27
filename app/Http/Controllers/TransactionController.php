<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        // jumlah data yang ditampilkan per paginasi halaman
        $pagination = 10;
        // get data search
        $search = $request->search;

        if ($search) {
            // menampilkan pencarian data
            $transactions = Transaction::select('id', 'date', 'customer_id', 'product_id', 'qty', 'total')->with('customer:id,name', 'product:id,name,price')
                ->whereAny(['date', 'qty', 'total'], 'LIKE', '%' . $search . '%')
                ->orWhereHas('customer', function ($query) use ($search) {
                    $query->where('name', 'LIKE', '%' . $search . '%');
                })
                ->orWhereHas('product', function ($query) use ($search) {
                    $query->where('name', 'LIKE', '%' . $search . '%');
                })
                ->paginate($pagination)
                ->withQueryString();
        } else {
            // menampilkan semua data
            $transactions = Transaction::select('id', 'date', 'customer_id', 'product_id', 'qty', 'total')->with('customer:id,name', 'product:id,name,price')
                ->latest()
                ->paginate($pagination);
        }

        // tampilkan data ke view
        return view('transactions.index', compact('transactions'))->with('i', ($request->input('page', 1) - 1) * $pagination);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        // get data customer
        $customers = Customer::get(['id', 'name']);
        // get data product
        $products = Product::get(['id', 'name', 'price']);

        // tampilkan form add data
        return view('transactions.create', compact('customers', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        // validasi form
        $request->validate([
            'date'     => 'required',
            'customer' => 'required|exists:customers,id',
            'product'  => 'required|exists:products,id',
            'qty'      => 'required',
            'total'    => 'required'
        ]);

        // create data
        Transaction::create([
            'date'        => $request->date,
            'customer_id' => $request->customer,
            'product_id'  => $request->product,
            'qty'         => $request->qty,
            'total'       => str_replace('.', '', $request->total)
        ]);

        // redirect ke halaman index dan tampilkan pesan berhasil simpan data
        return redirect()->route('transactions.index')->with(['success' => 'Transaksi baru telah disimpan.']);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): View
    {
        // get data transaction by ID
        $transaction = Transaction::findOrFail($id);
        // get data customer
        $customers = Customer::get(['id', 'name']);
        // get data product
        $products = Product::get(['id', 'name', 'price']);

        // tampilkan form edit data
        return view('transactions.edit', compact('transaction', 'customers', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        // validasi form
        $request->validate([
            'date'     => 'required',
            'customer' => 'required|exists:customers,id',
            'product'  => 'required|exists:products,id',
            'qty'      => 'required',
            'total'    => 'required'
        ]);

        // get data by ID
        $transaction = Transaction::findOrFail($id);

        // update data
        $transaction->update([
            'date'        => $request->date,
            'customer_id' => $request->customer,
            'product_id'  => $request->product,
            'qty'         => $request->qty,
            'total'       => str_replace('.', '', $request->total)
        ]);

        // redirect ke halaman index dan tampilkan pesan berhasil ubah data
        return redirect()->route('transactions.index')->with(['success' => 'Transaksi baru telah di update.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): RedirectResponse
    {
        // get data by ID
        $transaction = Transaction::findOrFail($id);

        // delete data
        $transaction->delete();

        // redirect ke halaman index dan tampilkan pesan berhasil hapus data
        return redirect()->route('transactions.index')->with(['success' => 'Transaksi baru telah dihapus!']);
    }
}