@include('contact.notfound')

@section('contact')

@if (count($contacts) > 0)

@foreach ($contacts as $contact)
<tr>
    <th scope="row" class="text-center align-middle">{{$contact->id}}</th>
    <td class="text-center align-middle">{{$contact->name}}</td>
    <td>
        @foreach ($contact->phones as $phone)
        <form class="needs-validation table-phone" novalidate>
            <div class="input-group input-group-sm mb-1">
                <input type="tel" class="form-control" pattern="^(\+?\d){7,13}$" value="{{ $phone->phone }}" required>
                <div class="input-group-append">
                    <button class="btn btn-primary phone-update" type="button" data-id="{{ $phone->id }}">Update</button>
                    <button class="btn btn-danger phone-delete" type="button" data-id="{{ $phone->id }}">Remove</button>
                </div>
                <div class="invalid-feedback" data-default="Invalid phone number">
                    Invalid phone number
                </div>
            </div>
        </form>
        @endforeach
        <button type="button" class="btn btn-success btn-sm w-100 phone-add" data-id="{{ $contact->id }}">Add</button>
    </td>
    <td>
        @foreach ($contact->emails as $email)
        <form class="needs-validation table-email" novalidate>
            <div class="input-group input-group-sm mb-1">
                <input type="email" class="form-control" value="{{ $email->email }}" required>
                <div class="input-group-append">
                    <button class="btn btn-primary email-update" type="button" data-id="{{ $email->id }}">Update</button>
                    <button class="btn btn-danger email-delete" type="button" data-id="{{ $email->id }}">Remove</button>
                </div>
                <div class="invalid-feedback" data-default="Invalid email address">
                    Invalid email address
                </div>
            </div>
        </form>
        @endforeach
        <button type="button" class="btn btn-success btn-sm w-100 email-add" data-id="{{ $contact->id }}">Add</button>
    </td>
    <td class="text-center align-middle">
        <a href="javascript:void(0)" title="Update" class="contact-update" data-id="{{ $contact->id }}">
            <svg class="bi" width="16" height="16" fill="currentColor">
                <use xlink:href="/assets/images/bootstrap-icons.svg#pencil-fill" />
            </svg>
        </a>
    </td>
    <td class="text-center align-middle">
        <a href="javascript:void(0)" title="Delete" class="contact-delete" data-id="{{ $contact->id }}">
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