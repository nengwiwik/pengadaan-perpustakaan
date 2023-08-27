<x-mail::message>
# Penawaran Buku

Berikut adalah ringkasan dari penawaran buku oleh Penerbit {{ $procurement->publisher->name }}.

@component('mail::table')
    | No Pengadaan | Tanggal | Total Buku |
    | :--------- | :--------- | ---------: |
    | {{ $procurement->code }} | {{ $procurement->invoice_date->format('d M Y') }} | {{ $procurement->total_books }} |
@endcomponent

<x-mail::button :url="route('procurements.new')">
    View Books
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
