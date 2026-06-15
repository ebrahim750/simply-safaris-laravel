@component('mail::message')
# New Tour Enquiry

A new enquiry has been submitted for **{{ $tour->name }}**.

## Enquiry Details

- **Name:** {{ $enquiry->name }}
- **Email:** {{ $enquiry->email }}
- **Phone:** {{ $enquiry->phone }}
- **Preferred Date:** {{ $enquiry->booking_date->format('F j, Y') }}
- **Adults:** {{ $enquiry->adults }}
- **Children:** {{ $enquiry->children ?? '0' }}
- **Message:** {{ $enquiry->message ?: 'No message provided.' }}

@component('mail::button', ['url' => route('tours.show', $tour)])
View Tour
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
