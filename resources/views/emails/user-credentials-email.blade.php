<x-mail::message>
# Your Account Is Ready

Hello {{ $recipientName }},

An account has been created for you on **{{ config('app.name') }}**.

- Email: {{ $recipientEmail }}
- Account type: {{ ucfirst($accountType) }}
- Password: {{ $plainTextPassword }}
- Created by: {{ $triggeredByName }}

<x-mail::button :url="$loginUrl">
Log in
</x-mail::button>

For security, please sign in and change your password as soon as possible.
</x-mail::message>
