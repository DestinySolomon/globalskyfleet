<x-mail::message>
# New Contact Message Received

**Name:** {{ $contact->name }}  
**Email:** {{ $contact->email }}  
**Phone:** {{ $contact->phone }}  
**Subject:** {{ $contact->subject_display }}  

**Message:**  
{{ $contact->message }}

<x-mail::button :url="$adminUrl">
View Message in Admin Panel
</x-mail::button>

<small>
Received: {{ $contact->created_at->format('F j, Y g:i A') }}  
IP: {{ $contact->ip_address }}
</small>
</x-mail::message>