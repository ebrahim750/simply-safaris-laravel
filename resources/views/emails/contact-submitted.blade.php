<x-mail::message>
# New Contact Form Submission

You have received a new contact form submission.

**Name:** {{ $name }}

**Email:** {{ $email }}

**Phone:** {{ $phone }}

**Submitted At:** {{ $submittedAt }}

**Message:**
{{ $message }}

Thanks,
{{ config('app.name') }}
</x-mail::message>
