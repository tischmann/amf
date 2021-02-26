@extends('layout')

@include('contact.search')
@include('contact.contact')
@include('contact.form')

@section('content')
<div class="container">
    <!-- Button pane -->
    <div class="row">
        <div class="col-md-2 py-3">
            <!-- Add contact button -->
            <button type="button" class="btn btn-success w-100" id="contactModalAdd">
                Add
            </button>
        </div>
        <div class="col-md-10 py-3">
            @yield('contact-search')
        </div>
    </div>
    <!-- Table -->
    <table class="table table-sm table-bordered table-hover">
        <thead class="thead-dark">
            <tr>
                <th scope="col" class="text-center align-middle">#</th>
                <th scope="col" class="text-center align-middle">Name</th>
                <th scope="col" class="text-center align-middle">Phones</th>
                <th scope="col" class="text-center align-middle">Emails</th>
                <th scope="col" class="text-center align-middle" colspan="2"></th>
            </tr>
        </thead>
        <tbody>
            @yield('contact')
        </tbody>
    </table>
    {{ $contacts->appends(Request::except('page'))->onEachSide(5)->links() }}
</div>
@yield('contact-form')
@endsection