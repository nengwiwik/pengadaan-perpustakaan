<x-mail::message>
# Introduction

The body of your message.

@component('mail::table')
| No Invoice | Nama Penerbit      | Tanggal     | Total Buku |
| :--------- | :----------------- | :--------- | ---------: |
| {{ $invoice->code }} | {{ $invoice->publisher->name }} | {{ $invoice->invoice_date->format('d M Y')}} | {{ $invoice->total_books }} |
@endcomponent

<x-mail::button :url="route('procurements.new')">
View Books
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
