@extends('layout')

@include('contact.contact')
@include('contact.add')

@section('content')
<div class="container">
    <!-- Title -->
    <p class="h3 text-center py-3">Absent-minded friend's contacts</p>
    <!-- Add contact button -->
    <div class="py-3">
        <button type="button" class="btn btn-primary w-100" data-toggle="modal" data-target="#addContactModal">
            Add
        </button>
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
</div>
@yield('add-contact')
@endsection