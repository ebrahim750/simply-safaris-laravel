<x-mail::message>
# Reset Your Password

Hello {{ $recipientName }},

We received a request to reset the password for your **{{ $appName }}** account.

- Email: {{ $recipientEmail }}

<x-mail::button :url="$resetUrl">
Reset Password
</x-mail::button>

This password reset link will expire in {{ $expireMinutes }} minutes.

If you did not request a password reset, no further action is required.
</x-mail::message>
