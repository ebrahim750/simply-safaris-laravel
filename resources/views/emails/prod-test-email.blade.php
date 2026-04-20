<x-mail::message>
# Prod Test Email

This is a queued production test email from **{{ config('app.name') }}**.

- Receiver: {{ $receiverEmail }}
- Triggered by: {{ $triggeredByName }}
- Mailer: {{ config('mail.default') }}
- Queue connection: {{ config('queue.default') }}

If you received this email, the queued prod test mail flow is working.
</x-mail::message>
