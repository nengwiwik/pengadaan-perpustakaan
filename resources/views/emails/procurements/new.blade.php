@component('mail::message')
# Introduction

The body of your message.

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
