<x-mail::message>
    # Hello

    A new artist has registered on your platform. Below are the details of the artist:

        Name: {{ $artist->name }}

        Email: {{ $artist->email }}

        Phone: {{ $artist->phone }}

    Please review and approve this new artist.

    <x-mail::button :url="$url">
    View Artist
    </x-mail::button>

    Thanks,<br>
    {{ config('app.name') }}
</x-mail::message>
