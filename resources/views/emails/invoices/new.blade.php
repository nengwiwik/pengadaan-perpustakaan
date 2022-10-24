@component('mail::message')
# Introduction

The body of your message.

@component('mail::table')
| No Invoice | Nama Penerbit      | Tanggal     | Total Buku |
| :--------- | :----------------- | :---------: | ---------: |
| 1          | PT. Buku Indonesia | 01 Nov 2022 | 10         |
@endcomponent

@component('mail::button', ['url' => ''])
View books
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
