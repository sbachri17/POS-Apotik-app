<x-app-layout>
    {{-- Page Title --}}
    <x-page-title>Dashboard</x-page-title>

    {{-- Heroes --}}
    <div class="bg-white rounded-2 shadow-sm p-4 mb-5">
        <div class="row align-items-center g-5">
            <div class="col-lg-3">
                <img src="{{ asset('images/img-dashboard.svg') }}" class="img-fluid opacity-85" alt="images" loading="lazy">
            </div>
            <div class="col-lg-9">
                <h4 class="text-primary mb-2">
                    Selamat datang di <span class="fw-semibold">Programmer Muda</span>!
                </h4>
                <p class="lead-dashboard mb-4">Programmer Muda merupakan platform belajar koding online yang akan membantu mengembangkan skill koding Kamu dengan belajar melalui beragam studi kasus pembuatan aplikasi yang dapat dipraktekan secara langsung.</p>
                <div class="d-grid gap-3 d-md-flex justify-content-md-start">
                    {{-- <a href="https://programmermuda.com/" target="_blank" class="btn btn-primary py-2 px-4">
                        Show Projects <i class="ti ti-chevron-right align-middle ms-2"></i>
                    </a> --}}
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        {{-- menampilkan informasi jumlah data Category --}}
        <div class="col-lg-6 col-xl-3">
            <div class="bg-white rounded-2 shadow-sm p-4 mb-4">
                <div class="d-flex align-items-center justify-content-start">
                    <div class="me-4">
                        <i class="ti ti-category fs-1 bg-primary-2 text-white rounded-2 p-2"></i>
                    </div>
                    <div>
                        <p class="text-muted mb-1">Kategori</p>
                        {{-- tampilkan data --}}
                        <h5 class="fw-bold mb-0">{{ $totalCategory }}</h5>
                    </div>
                </div>
            </div>
        </div>
        {{-- menampilkan informasi jumlah data Product --}}
        <div class="col-lg-6 col-xl-3">
            <div class="bg-white rounded-2 shadow-sm p-4 p-lg-4-2 mb-4">
                <div class="d-flex align-items-center justify-content-start">
                    <div class="me-4">
                        <i class="ti ti-copy fs-1 bg-success text-white rounded-2 p-2"></i>
                    </div>
                    <div>
                        <p class="text-muted mb-1">Produk</p>
                        {{-- tampilkan data --}}
                        <h5 class="fw-bold mb-0">{{ $totalProduct }}</h5>
                    </div>
                </div>
            </div>
        </div>
        {{-- menampilkan informasi jumlah data Customer --}}
        <div class="col-lg-6 col-xl-3">
            <div class="bg-white rounded-2 shadow-sm p-4 p-lg-4-2 mb-4">
                <div class="d-flex align-items-center justify-content-start">
                    <div class="text-muted me-4">
                        <i class="ti ti-users fs-1 bg-warning text-white rounded-2 p-2"></i>
                    </div>
                    <div>
                        <p class="text-muted mb-1">Pelanggan</p>
                        {{-- tampilkan data --}}
                        <h5 class="fw-bold mb-0">{{ $totalCustomer }}</h5>
                    </div>
                </div>
            </div>
        </div>
        {{-- menampilkan informasi jumlah data Transaction --}}
        <div class="col-lg-6 col-xl-3">
            <div class="bg-white rounded-2 shadow-sm p-4 p-lg-4-2 mb-4">
                <div class="d-flex align-items-center justify-content-start">
                    <div class="text-muted me-4">
                        <i class="ti ti-folders fs-1 bg-info text-white rounded-2 p-2"></i>
                    </div>
                    <div>
                        <p class="text-muted mb-1">Transaksi</p>
                        {{-- tampilkan data --}}
                        <h5 class="fw-bold mb-0">{{ $totalTransaction }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- menampilkan informasi product terlaris --}}
    <div class="bg-white rounded-2 shadow-sm pt-4 px-4 pb-3 mb-5">
        {{-- judul --}}
        <h6 class="mb-0">
            <i class="ti ti-folder-star fs-5 align-text-top me-1"></i> 
            5 Produk Terbaik Terjual.
        </h6>

        <hr class="mb-4">

        {{-- tabel tampil data --}}
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover" style="width:100%">
                <thead>
                    <th class="text-center">Gambar</th>
                    <th class="text-center">Nama</th>
                    <th class="text-center">Harga</th>
                    <th class="text-center">Terjual</th>
                </thead>
                <tbody>
                @forelse ($transactions as $transaction)
                    {{-- jika data ada, tampilkan data --}}
                    <tr>
                        <td width="50" class="text-center">
                            <img src="{{ asset('/storage/products/'.$transaction->product->image) }}" class="img-thumbnail rounded-4" width="80" alt="Images">
                        </td>
                        <td width="200">{{ $transaction->product->name }}</td>
                        <td width="100" class="text-end">{{ 'Rp ' . number_format($transaction->product->price, 0, '', '.') }}</td>
                        <td width="80" class="text-center">{{ $transaction->transactions_sum_qty }}</td>
                    </tr>
                @empty
                    {{-- jika data tidak ada, tampilkan pesan data tidak tersedia --}}
                    <tr>
                        <td colspan="6">
                            <div class="d-flex justify-content-center align-items-center">
                                <i class="ti ti-info-circle fs-5 me-2"></i>
                                <div>Tidak ada data tersedia.</div>
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>