@component('mail::message')
# Penawaran Buku Baru

Berikut adalah detail penawaran buku baru untuk Perpustakaan Universitas Dian Nusantara, kampus {{ $procurement->campus->name }}

@component('mail::table')
| No Pengadaan | Nama Penerbit      | Tanggal     | Total Buku |
| :--------- | :----------------- | :--------- | ---------: |
| {{ $procurement->code }} | {{ $procurement->publisher->name }} | {{ $procurement->invoice_date->format('d M Y')}} | {{ $procurement->total_books }} |
@endcomponent

@component('mail::button', ['url' => route('homepage')])
View books
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
