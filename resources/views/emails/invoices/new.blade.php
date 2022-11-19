@component('mail::message')
# Introduction

The body of your message.

@component('mail::table')
| No Invoice | Nama Penerbit      | Tanggal     | Total Buku |
| :--------- | :----------------- | :--------- | ---------: |
| {{ $invoice->code }} | {{ $invoice->publisher->name }} | {{ $invoice->invoice_date->format('d M Y')}} | {{ $invoice->books()->count() }} |
@endcomponent

@component('mail::button', ['url' => ''])
View books
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
