@extends('layouts.admin')

@section('content')
    <h1 class="h3 mb-4 text-gray-800">{{ $title }}</h1>

    <input type="hidden" id="updateRoute" value="{{ route('procurements.update-buku') }}">
    <input type="hidden" id="deleteRoute" value="{{ route('procurements.delete-buku') }}">

    <div class="row mb-3">
        <div class="col-md-6">
            <a class="btn btn-dark" href="{{ route('procurements.active') }}" role="button">
                <i class="fa fa-arrow-circle-left" aria-hidden="true"></i> Back
            </a>
        </div>
        <div class="col-md-6 d-flex flex-row-reverse">
            {{ $books->links() }}
        </div>
    </div>

    <div class="row mb-3 text-center">
        <div class="col-md-3">
            <p>Total Buku: <span id="total-buku">{{ $procurement->total_books }} buku</span></p>
        </div>
        <div class="col-md-3">
            <p>Total Barang: <span id="total-barang">{{ $procurement->total_items }} eksemplar</span></p>
        </div>
        <div class="col-md-3">
            <p>Total Harga: <span id="total-harga">Rp {{ number_format($procurement->total_price, 0, ',', '.') }}</span></p>
        </div>
        <div class="col-md-3">
            <p>Anggaran Biaya: <span id="total-harga">Rp {{ number_format($procurement->budget, 0, ',', '.') }}</span></p>
        </div>
    </div>

    <table class="table table-hover">
        <thead>
            <tr>
                <th>ISBN</th>
                <th>Judul Buku</th>
                <th>Penulis</th>
                <th class="text-center">Tahun Terbit</th>
                <th class="text-right">Harga</th>
                <th class="text-center" style="width:10%">Jumlah</th>
                <th class="text-center">Hapus</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($books as $book)
                <tr id="{{ $book->getKey() }}">
                    <td scope="row">{{ $book->isbn }}</td>
                    <td>{{ $book->title }}</td>
                    <td>{{ $book->author_name }}</td>
                    <td class="text-center">{{ $book->published_year }}</td>
                    <td class="text-right">Rp {{ number_format($book->price, 0, ',', '.') }}</td>
                    <td class="text-center">
                        <label class="sr-only" for="eksemplar">Eksemplar</label>
                        <input type="number" class="form-control mb-2 mr-sm-2 form-control-sm"
                            id="eksemplar[{{ $book->getKey() }}]"
                            value="{{ old('eksemplar.' . $book->getKey(), $book->eksemplar) }}"
                            onchange="update({{ $book->getKey() }})" name="eksemplar[{{ $book->getKey() }}]"
                            placeholder="Jumlah" min="0" autocomplete="off">
                    </td>
                    {{-- <td class="text-center">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" value="{{ $book->getKey() }}"
                                id="book-{{ $book->getKey() }}" @checked($book->is_chosen)>
                            <label class="custom-control-label" for="book-{{ $book->getKey() }}">Pilih</label>
                        </div>
                    </td> --}}
                    <td class="text-center mx-auto">
                        <button type="button" class="btn btn-danger btn-sm mb-2"
                            onclick="hapus({{ $book->getKey() }})">Hapus</button>
                        <form class="form-inline" method="POST" action="">
                            @csrf @method('DELETE')
                        </form>
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
    @method('DELETE')
@endpush

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function update(index) {
            const updateRoute = $('#updateRoute').val();
            const csrfToken = $('meta[name="csrf-token"]').attr('content');
            const inputElement = document.getElementById('eksemplar[' + index + ']');

            // Check if the input element exists
            if (inputElement) {
                // Get the value of the input element
                var eksemplar = inputElement.value;

                // Create a data object with the value
                var data = {
                    book: index,
                    eksemplar: eksemplar
                };
                // Make an AJAX POST request using the fetch API
                fetch(updateRoute, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify(data)
                    })
                    .then(response => {
                        if (response.ok) {
                            return response.json();
                        } else {
                            // Handle errors here
                            console.error('Request failed with status:', response.status);
                        }
                    })
                    .then((data) => {
                        $('#total-buku').html(data.total_books + ' buku')
                        $('#total-barang').html(data.total_items + ' eksemplar')
                        $('#total-harga').html('Rp ' + data.total_price)
                    });
            }
        }

        function hapus(index) {
            const deleteRoute = $('#deleteRoute').val();
            const csrfToken = $('meta[name="csrf-token"]').attr('content');
            var row = document.getElementById(index);
            // Check if the row exists
            if (row) {
                // Get the table to which the row belongs
                var table = row.parentNode;

                var data = {
                    book: index,
                    _method: 'DELETE'
                };
                // Make an AJAX POST request using the fetch API
                fetch(deleteRoute, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify(data)
                    })
                    .then(response => {
                        if (response.ok) {
                            // Request was successful
                            // You can handle the response here if needed
                            console.log(response);
                            table.removeChild(row);
                            return response.json();
                        } else {
                            // Handle errors here
                            console.error('Request failed with status:', response.status);
                        }
                    })
                    .then((data) => {
                        $('#total-buku').html(data.total_books + ' buku')
                        $('#total-barang').html(data.total_items + ' eksemplar')
                        $('#total-harga').html('Rp ' + data.total_price)
                    });

                // Remove the row from the table
            }
        }

        $(document).ready(function() {
            // const savingRoute = $('#savingRoute').val();
            // const csrfToken = $('meta[name="csrf-token"]').attr('content');


            // $('tr').on('click', function(event) {
            //     // Find the checkbox within the clicked row and trigger its click event
            //     var checkbox = $(this).find('.custom-control-input');
            //     var checkboxValue = checkbox.val();
            //     // toggling
            //     checkbox.prop('checked', !checkbox.prop('checked'));
            //     // Make an AJAX request
            //     $.ajax({
            //         url: savingRoute,
            //         method: 'POST',
            //         headers: {
            //             'X-CSRF-TOKEN': csrfToken
            //         },
            //         data: {
            //             id: checkboxValue, // Pass the checkbox value
            //             checked: checkbox.prop('checked') ? 1 : 0
            //         },
            //         success: function(response) {
            //             // AJAX request succeeded
            //             // Show a success toast using SweetAlert
            //             Swal.fire({
            //                 position: 'top-end',
            //                 toast: true,
            //                 showConfirmButton: false,
            //                 icon: 'success',
            //                 title: 'Success',
            //                 timer: 3000,
            //                 timerProgressBar: true,
            //                 didOpen: (toast) => {
            //                     toast.addEventListener('mouseenter', Swal
            //                         .stopTimer)
            //                     toast.addEventListener('mouseleave', Swal
            //                         .resumeTimer)
            //                 }
            //             });
            //         },
            //         error: function(xhr) {
            //             console.log(xhr);
            //             // Handle AJAX request error if needed
            //             Swal.fire({
            //                 position: 'top-end',
            //                 toast: true,
            //                 showConfirmButton: false,
            //                 icon: 'error',
            //                 title: 'Error',
            //                 timer: 3000,
            //                 timerProgressBar: true,
            //                 didOpen: (toast) => {
            //                     toast.addEventListener('mouseenter', Swal
            //                         .stopTimer)
            //                     toast.addEventListener('mouseleave', Swal
            //                         .resumeTimer)
            //                 }
            //             });
            //         }
            //     });
            // });
        });
    </script>
@endpush
