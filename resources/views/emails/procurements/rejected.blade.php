<x-mail::message>
# Introduction

The body of your message.

Alasan penolakan: {!! $procurement->campus_note !!}

<x-mail::button :url="route('homepage')">
Button Text
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
