<x-mail::message>
# Introduction

The body of your message.

Nomor Invoice: **{{ $invoice->code }}**

@component('mail::table')
| Nama Kampus | Tanggal | Jumlah Buku | Total Buku | Total Harga |
| :----------------- | :-------- | :---------: | :---------: | ---------: |
| {{ $invoice->campus->name }} | {{ $invoice->invoice_date->format('d M Y')}} | {{ number_format($invoice->total_books, 0, ',', '.') }} | {{ number_format($invoice->total_items, 0, ',', '.') }} | IDR {{ number_format($invoice->total_price, 0, ',', '.') }} |
@endcomponent

<x-mail::button :url="route('penerbit.invoices.books.verified', $invoice->getKey())">
Lihat Invoice
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
