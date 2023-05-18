<x-mail::message>
# Introduction

The body of your message.

@component('mail::table')
| No Pengadaan | Nama Penerbit      | Tanggal     | Total Buku |
| :--------- | :----------------- | :--------- | ---------: |
| {{ $procurement->code }} | {{ $procurement->publisher->name }} | {{ $procurement->invoice_date->format('d M Y')}} | {{ $procurement->total_books }} |
@endcomponent

<x-mail::button :url="route('procurements.new')">
View Books
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
