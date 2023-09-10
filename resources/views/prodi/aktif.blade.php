@extends('layouts.admin')

@section('content')
    <h1 class="h3 mb-4 text-gray-800">{{ $title }}</h1>

    <input type="hidden" id="savingRoute" value="{{ route('prodi.procurements.procurement-books.save') }}">

    <div class="row mb-3">
        <div class="col-md-12">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Penerbit</th>
                        <th>Anggaran Biaya</th>
                        <th>Total Buku</th>
                        <th>Total Biaya Sementara</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td scope="row">{{ $procurement->publisher->name }}</td>
                        <td>Rp {{ number_format($procurement->budget, 0, ',', '.') }}</td>
                        <td><span id="total-books">{{ $procurement->total_books }} buku</span></td>
                        <td><span id="total-price">{{ 'Rp ' . number_format($procurement->total_price, 0, ',', '.') }}</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-md-6">
            <a class="btn btn-dark" href="{{ route('prodi.procurements.active') }}" role="button">
                <i class="fa fa-arrow-circle-left" aria-hidden="true"></i> Back
            </a>
        </div>
        <div class="col-md-6 d-flex flex-row-reverse">
            {{ $books->links() }}
        </div>
    </div>

    <table class="table table-hover table-bordered">
        <thead>
            <tr class="text-center">
                <th>ISBN</th>
                <th>Judul</th>
                <th>Penulis</th>
                <th>Tahun</th>
                <th>Harga</th>
                <th>
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" value="all" id="selectAll">
                        <label class="custom-control-label" for="selectAll">Semua</label>
                    </div>
                </th>
            </tr>
        </thead>
        <tbody>
            @forelse ($books as $book)
                <tr class="pilih">
                    <td scope="row">{{ $book->isbn }}</td>
                    <td>{!! str($book->title)->wordWrap(30, "<br />\n") !!}</td>
                    <td>{!! str($book->author_name)->wordWrap(30, "<br />\n") !!}</td>
                    <td class="text-center">{{ $book->published_year }}</td>
                    <td class="text-right">{{ number_format($book->price, 0, ',', '.') }}</td>
                    <td class="text-center">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input item" value="{{ $book->getKey() }}"
                                id="book-{{ $book->getKey() }}" @checked($book->is_chosen)>
                            <label class="custom-control-label" for="book-{{ $book->getKey() }}">Pilih</label>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td scope="row" colspan="5">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5">
                    <div class="d-flex justify-content-center">
                        {{ $books->links() }}
                </td>
                </div>
            </tr>
        </tfoot>
    </table>
@endsection

@push('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            const savingRoute = $('#savingRoute').val();
            const csrfToken = $('meta[name="csrf-token"]').attr('content');

            var rows = document.querySelectorAll('tbody tr.pilih');
            recalculate();

            rows.forEach(function(row) {
                row.addEventListener('click', function(event) {
                    var checkbox = this.querySelector('.custom-control-input');
                    checkbox.checked = !checkbox.checked;
                    checkMe(checkbox);
                });
                recalculate();
            });

            const selectAllCheckbox = document.getElementById('selectAll');
            const itemCheckboxes = document.querySelectorAll('.item');

            // Tambahkan event listener ke checkbox "Pilih Semua"
            selectAllCheckbox.addEventListener('change', function() {
                // Setel semua checkbox item sesuai dengan status checkbox "Pilih Semua"
                for (var i = 0; i < itemCheckboxes.length; i++) {
                    itemCheckboxes[i].checked = selectAllCheckbox.checked;
                    var checkboxValue = itemCheckboxes[i].value;
                    checkMe(itemCheckboxes[i]);
                }
                for (var i = 0; i < itemCheckboxes.length; i++) {
                    itemCheckboxes[i].checked = selectAllCheckbox.checked;
                }
            });

            function recalculate() {
                var selectAllCheckbox = document.getElementById('selectAll');
                var cekbox = document.querySelectorAll('.item');
                // Tambahkan event listener ke checkbox item
                for (var i = 0; i < cekbox.length; i++) {
                    cekbox[i].addEventListener('change', function() {
                        // Periksa apakah semua checkbox item telah dicentang
                        var allChecked = true;
                        for (var j = 0; j < cekbox.length; j++) {
                            if (!cekbox[j].checked) {
                                allChecked = false;
                                break;
                            }
                        }
                        // Setel status checkbox "Pilih Semua" sesuai dengan hasil pemeriksaan di atas
                        selectAllCheckbox.checked = allChecked;
                    });
                }
            }

            // function checkMe(checkboxValue, status) {
            function checkMe(checkbox) {
                var checkboxValue = checkbox.value;
                var status = checkbox.checked;
                $.ajax({
                    url: savingRoute,
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    data: {
                        id: checkboxValue, // Pass the checkbox value
                        checked: status ? 1 : 0 // checked: checkbox.prop('checked') ? 1 : 0
                    },
                    success: function(response) {
                        // AJAX request succeeded
                        $('#total-books').html(response.total_books)
                        $('#total-items').html(response.total_items)
                        $('#total-price').html(response.total_price)
                        // Show a success toast using SweetAlert
                        Swal.fire({
                            position: 'top-end',
                            toast: true,
                            showConfirmButton: false,
                            icon: 'success',
                            title: 'Success',
                            timer: 3000,
                            timerProgressBar: true,
                            didOpen: (toast) => {
                                toast.addEventListener('mouseenter', Swal
                                    .stopTimer)
                                toast.addEventListener('mouseleave', Swal
                                    .resumeTimer)
                            }
                        });
                        recalculate();
                    },
                    error: function(xhr) {
                        // kembalikan lagi posisi checkbox-nya
                        checkbox.checked = false;
                        recalculate();
                        // Handle AJAX request error if needed
                        Swal.fire({
                            position: 'top-end',
                            toast: true,
                            showConfirmButton: false,
                            icon: 'error',
                            title: 'Error! ' + xhr.responseText,
                            timer: 3000,
                            timerProgressBar: true,
                            didOpen: (toast) => {
                                toast.addEventListener('mouseenter', Swal
                                    .stopTimer)
                                toast.addEventListener('mouseleave', Swal
                                    .resumeTimer)
                            }
                        });
                    }
                });
            }
        });
    </script>
@endpush
