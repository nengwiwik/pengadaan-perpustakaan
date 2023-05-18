<x-mail::message>
# Introduction

The body of your message.

Nomor Pengadaan: **{{ $procurement->code }}**

@component('mail::table')
| Nama Kampus | Tanggal | Jumlah Buku | Total Buku | Total Harga |
| :----------------- | :-------- | :---------: | :---------: | ---------: |
| {{ $procurement->campus->name }} | {{ $procurement->invoice_date->format('d M Y')}} | {{ number_format($procurement->total_books, 0, ',', '.') }} | {{ number_format($procurement->total_items, 0, ',', '.') }} | IDR {{ number_format($procurement->total_price, 0, ',', '.') }} |
@endcomponent

<x-mail::button :url="route('penerbit.invoices.books.verified', $procurement->getKey())">
Lihat Procurement
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
