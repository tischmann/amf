@include('contact.notfound')

@section('contact')

@if (count($contacts) > 0)

@foreach ($contacts as $contact)
<tr>
    <th scope="row" class="text-center align-middle">{{$contact->id}}</th>
    <td class="text-center align-middle">{{$contact->name}}</td>
    <td class="text-center align-middle">
        @foreach ($contact->phones as $phone)
        <div>{{$phone}}</div>
        @endforeach
    </td>
    <td class="text-center align-middle">
        @foreach ($contact->emails as $email)
        <div>{{$email}}</div>
        @endforeach
    </td>
    <td class="text-center align-middle">
        <a href="/contact/update/{{$contact->id}}" title="Update">
            <svg class="bi" width="16" height="16" fill="currentColor">
                <use xlink:href="/assets/images/bootstrap-icons.svg#pencil-fill" />
            </svg>
        </a>
    </td>
    <td class="text-center align-middle">
        <a href="/contact/delete/{{$contact->id}}" title="Delete">
            <svg class="bi" width="16" height="16" fill="currentColor">
                <use xlink:href="/assets/images/bootstrap-icons.svg#trash-fill" />
            </svg>
        </a>
    </td>
</tr>
@endforeach

@else

@yield('notfound')

@endif

@endsection