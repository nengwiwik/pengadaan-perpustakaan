@extends('layouts.admin')

@section('content')
    <h1 class="h3 mb-4 text-gray-800">{{ $title }}</h1>

    <input type="hidden" id="savingRoute" value="{{ route('prodi.procurements.procurement-books.save') }}">

    <div class="row mb-3">
        <div class="col-md-6">
            <a class="btn btn-dark" href="{{ route('prodi.procurements.active') }}" role="button">
                <i class="fa fa-arrow-circle-left" aria-hidden="true"></i> Back
            </a>
        </div>
        <div class="col-md-6 d-flex flex-row-reverse">
            {{ $books->links() }}
        </div>
    </div>

    <table class="table table-hover">
        <thead>
            <tr>
                <th>ISBN</th>
                <th>Judul Buku</th>
                <th>Penulis</th>
                <th class="text-center">Tahun Terbit</th>
                <th class="text-center">Pilih</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($books as $book)
                <tr>
                    <td scope="row">{{ $book->isbn }}</td>
                    <td>{{ $book->title }}</td>
                    <td>{{ $book->author_name }}</td>
                    <td class="text-center">{{ $book->published_year }}</td>
                    <td class="text-center">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" value="{{ $book->getKey() }}"
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

            $('tbody tr').on('click', function(event) {
                // Find the checkbox within the clicked row and trigger its click event
                var checkbox = $(this).find('.custom-control-input');
                var checkboxValue = checkbox.val();
                // toggling
                checkbox.prop('checked', !checkbox.prop('checked'));
                // Make an AJAX request
                $.ajax({
                    url: savingRoute,
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    data: {
                        id: checkboxValue, // Pass the checkbox value
                        checked: checkbox.prop('checked') ? 1 : 0
                    },
                    success: function(response) {
                        // AJAX request succeeded
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
                    },
                    error: function(xhr) {
                        console.log(xhr);
                        // Handle AJAX request error if needed
                        Swal.fire({
                            position: 'top-end',
                            toast: true,
                            showConfirmButton: false,
                            icon: 'error',
                            title: 'Error',
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
            });
        });
    </script>
@endpush
