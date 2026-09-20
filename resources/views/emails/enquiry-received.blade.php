<x-mail::message>
# New enquiry

@if($enquiry->source === 'journey_finder')
**Source:** Journey Finder quiz  
@endif
@if($enquiry->journey)
**Journey:** {{ $enquiry->journey->name }}
@endif
@if($enquiry->destination)
**Destination:** {{ $enquiry->destination->name }}
@endif

**Name:** {{ $enquiry->name }}  
**Email:** {{ $enquiry->email }}  
@if($enquiry->phone)
**Phone:** {{ $enquiry->phone }}  
@endif
@if($enquiry->whatsapp)
**WhatsApp:** {{ $enquiry->whatsapp }}  
@endif
@if($enquiry->preferred_destinations)
**Countries:** {{ implode(', ', $enquiry->preferred_destinations) }}  
@endif
@if($enquiry->days)
**Days:** {{ $enquiry->days }}  
@endif
@if($enquiry->travellers)
**Travellers:** {{ $enquiry->travellers }}  
@endif
@if($enquiry->preferred_experiences)
**Experiences:** {{ implode(', ', $enquiry->preferred_experiences) }}  
@endif
@if($enquiry->accommodation)
**Accommodation:** {{ $enquiry->accommodation }}  
@endif
@if($enquiry->investment)
**Investment:** {{ $enquiry->investment }}  
@endif
@if($enquiry->travel_dates)
**Dates:** {{ $enquiry->travel_dates }}  
@endif
@if($enquiry->preferences)
**Quiz summary:** {{ trim(preg_replace('/\s+/', ' ', strip_tags(str_replace(['</p>', '<br>', '<br/>', '<br />', "\n"], ' / ', $enquiry->preferences)))) }}  
@endif

**Message:**

{{ trim(preg_replace('/\s+/', ' ', strip_tags(str_replace(['</p>', '<br>', '<br/>', '<br />'], "\n", $enquiry->message)))) }}

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
