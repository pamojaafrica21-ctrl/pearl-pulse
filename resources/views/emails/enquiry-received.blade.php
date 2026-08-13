<x-mail::message>
# New enquiry

@if($enquiry->destination)
**Destination:** {{ $enquiry->destination->name }}
@endif

**Name:** {{ $enquiry->name }}  
**Email:** {{ $enquiry->email }}  
@if($enquiry->phone)
**Phone:** {{ $enquiry->phone }}  
@endif

**Message:**

{{ $enquiry->message }}

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
