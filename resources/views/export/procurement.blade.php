<!doctype html>
<html lang="en">

<head>
    <title>Title</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <style>
        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white text-center font-weight-bold">
                    PENGADAAN BUKU {{ $procurement->code }}
                </div>
                <div class="card-body">
                    <table class="table table-borderless table-sm">
                        <tbody>
                            <tr>
                                <td scope="row" style="width: 20%">Penerbit</td>
                                <td>: {{ $procurement->publisher->name }}</td>
                            </tr>
                            <tr>
                                <td scope="row" style="width: 20%">Alamat</td>
                                <td>: {{ $procurement->publisher->address }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <hr />
                    <table class="table table-borderless table-sm">
                        <tbody>
                            <tr>
                                <td scope="row" style="width: 20%">Kampus</td>
                                <td>: {{ $procurement->campus->name }}</td>
                            </tr>
                            <tr>
                                <td scope="row" style="width: 20%">Alamat</td>
                                <td>: {{ $procurement->campus->address }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <hr />
                    <table class="table table-borderless table-sm">
                        <tbody>
                            <tr>
                                <td scope="row" style="width: 20%">Jumlah Buku</td>
                                @if ($procurement->status == 'Ditolak')
                                <td>: {{ $procurement->total_books }}</td>
                                @else
                                <td>: {{ $procurement->total_items }}</td>
                                @endif
                            </tr>
                            @if ($procurement->status == 'Selesai')
                            <tr>
                                <td scope="row" style="width: 20%">Harga Final</td>
                                <td>: {{ number_format($procurement->final_price, 0, ',', '.') }}</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                    <hr />
                    <table class="table table-borderless table-sm">
                        <thead>
                            <tr class="text-center">
                                <th style="width: 25%">Tanggal Pengadaan</th>
                                @if ($procurement->status == 'Ditolak')
                                    <th style="width: 25%">Tanggal Ditolak</th>
                                @else
                                    <th style="width: 25%">Tanggal Diterima</th>
                                    <th style="width: 25%">Tanggal Verifikasi</th>
                                @endif
                                @if ($procurement->status == 'Selesai')
                                    <th style="width: 25%">Tanggal Invoice</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="text-center">
                                <td>{{ $procurement->invoice_date->format('d F Y') }}</td>
                                @if ($procurement->status == 'Ditolak')
                                    <td>{{ $procurement->cancelled_date->format('d F Y') }}</td>
                                @else
                                    <td>{{ $procurement->approved_at->format('d F Y') }}</td>
                                    <td>{{ $procurement->verified_date->format('d F Y') }}</td>
                                @endif
                                @if ($procurement->status == 'Selesai')
                                    <td>{{ $procurement->updated_at->format('d F Y') }}</td>
                                @endif
                            </tr>
                        </tbody>
                    </table>
                </div>
                @if ($procurement->status == 'Selesai')
                <div class="card-footer text-muted">
                    <table class="table table-borderless table-sm">
                        <thead>
                            <tr class="text-center">
                                <th style="width: 25%">Disetujui</th>
                                <th style="width: 25%">Disetujui</th>
                                <th style="width: 25%">Diketahui</th>
                                <th style="width: 25%">Dibuat</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="text-center mt-5">
                                <td><p>&nbsp;</p>(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td>
                                <td><p>&nbsp;</p>(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td>
                                <td><p>&nbsp;</p>(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td>
                                <td><p>&nbsp;</p>(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>
    </div>
    <div class="page-break"></div>
    <div class="row">
        <div class="h3 text-primary font-weight-bold">
            Daftar Buku
        </div>
        <table class="table table-sm table-bordered">
            <thead>
                <tr class="table-primary text-center font-weight-bold">
                    <th>Judul</th>
                    <th>Penulis</th>
                    <th>Tahun</th>
                    <th>Eks.</th>
                    <th>Harga</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total = 0;
                @endphp
                @if ($procurement->status == 'Ditolak')
                @foreach ($procurement->procurement_books as $book)
                    @php
                        $total += $book->price * $book->eksemplar;
                    @endphp
                    <tr>
                        <td scope="row">
                            {{ $book->title }} <br />
                            <span class="text-primary">{{ $book->isbn }}</span>
                        </td>
                        <td>{{ $book->author_name }}</td>
                        <td class="text-center">{{ $book->published_year }}</td>
                        <td class="text-center">{{ $book->eksemplar }}</td>
                        <td class="text-right">{{ number_format($book->price, 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($book->price * $book->eksemplar, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
                @else
                @foreach ($procurement->books as $book)
                    @php
                        $total += $book->price * $book->eksemplar;
                    @endphp
                    <tr>
                        <td scope="row">
                            {{ $book->title }} <br />
                            <span class="text-primary">{{ $book->isbn }}</span>
                        </td>
                        <td>{{ $book->author_name }}</td>
                        <td class="text-center">{{ $book->published_year }}</td>
                        <td class="text-center">{{ $book->eksemplar }}</td>
                        <td class="text-right">{{ number_format($book->price, 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($book->price * $book->eksemplar, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
                @endif
                <tr>
                    <td colspan="4"></td>
                    <td class="text-center font-weight-bold">Total</td>
                    <td class="text-right font-weight-bold">{{ number_format($total, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>
</body>

</html>
