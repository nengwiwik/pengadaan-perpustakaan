@extends('layouts.admin')

@section('content')
  <h1 class="h3 mb-4 text-gray-800">{{ $title }}</h1>

  <div class="mb-3">

    @php
        if (is_null(auth()->user()->publisher_id) and is_null(auth()->user()->campus_id)) {
            $link = route('procurements.new');
        } else if (is_null(auth()->user()->publisher_id) and !is_null(auth()->user()->campus_id)) {
            $link = route('prodi.procurements.active');
        } else {
            $link = route('penerbit.procurements.verified');
        }
    @endphp

    <a class="btn btn-dark" href="{{$link}}" role="button">
      <i class="fa fa-arrow-circle-left" aria-hidden="true"></i> Back
    </a>
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
