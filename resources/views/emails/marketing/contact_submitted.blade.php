@component('mail::message')
# New Contact Submission

**Name**: {{ $data['name'] }}  
**Email**: {{ $data['email'] }}  
**Subject**: {{ $data['subject'] }}

---
{{ $data['message'] }}

@endcomponent


