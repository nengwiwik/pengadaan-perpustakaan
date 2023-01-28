@extends('layouts.admin')

@section('content')
  <h1 class="h3 mb-4 text-gray-800">{{ $title }}</h1>

  <div class="mb-3">
    <a name="" id="" class="btn btn-dark" href="#back" onclick="history.back()" role="button"><i
        class="fa fa-arrow-circle-left" aria-hidden="true"></i> Back</a>
    <button name="" id="" class="btn btn-primary" href="#" role="button" data-toggle="modal" data-target="#exampleModal"><i
        class="fas fa-file-import"></i> Import Books</button>
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Import Books</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form action="{{ route('penerbit.invoices.books.import', Request::segment(3)) }}" method="post" id="form-upload" enctype="multipart/form-data">
              @csrf
              <div class="form-group">
                <label for="upload">File</label>
                <input type="file" class="form-control-file" name="upload" id="upload" placeholder="File" aria-describedby="uploadId">
              </div>
            </form>

            <hr>
            <p>Download template <a href="{{ asset('template/import-books.xlsx') }}" download>here</a></p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" form="form-upload">Upload</button>
          </div>
        </div>
      </div>
    </div>
  </div>

  {!! $output !!}
@endsection

@push('css')
  <meta name="csrf-token" content="{{ csrf_token() }}">

  @foreach ($css_files as $key => $css_file)
    <link rel="stylesheet" href="{{ $css_file }}">
  @endforeach
@endpush

@push('js')
  @foreach ($js_files as $js_file)
    <script src="{{ $js_file }}"></script>
  @endforeach
  <script>
    if (typeof $ !== 'undefined') {
      $(document).ready(function() {
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
      });
    }
  </script>
@endpush
