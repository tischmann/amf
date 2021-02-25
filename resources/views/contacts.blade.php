@extends('layout')

@section('content')
<div class="container">
    <!-- Title -->
    <p class="h3 text-center py-2">Контакты рассеянного друга</p>
    <!-- Table -->
    <table class="table table-sm table-bordered table-hover">
        <thead class="thead-dark">
            <tr>
                <th scope="col" class="text-center align-middle">
                    <!-- Add contact button -->
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addContactModal">
                        Add
                    </button>
                </th>
                <th scope="col" class="text-center align-middle">Name</th>
                <th scope="col" class="text-center align-middle">Phones</th>
                <th scope="col" class="text-center align-middle">Emails</th>
                <th scope="col" class="text-center align-middle" colspan="2"></th>
            </tr>
        </thead>
        <tbody>
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
                    <a href="/contact/{{$contact->id}}/edit" title="Изменить">
                        <svg class="bi" width="16" height="16" fill="currentColor">
                            <use xlink:href="assets/images/bootstrap-icons.svg#pencil-fill" />
                        </svg>
                    </a>
                </td>
                <td class="text-center align-middle">
                    <a href="/contact/{{$contact->id}}/delete" title="Удалить">
                        <svg class="bi" width="16" height="16" fill="currentColor">
                            <use xlink:href="assets/images/bootstrap-icons.svg#trash-fill" />
                        </svg>
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Add contact form -->
<div class="modal fade" id="addContactModal" tabindex="-1" aria-labelledby="addContactModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addContactModalLabel">Add new contact</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addContactForm" class="needs-validation" novalidate>
                    <div class="form-group">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="contact-name">Name</span>
                            </div>
                            <input id="contactName" type="text" class="form-control" aria-label="Contact name" aria-describedby="contact-name" required>
                            <div class="valid-feedback">
                                Looks good!
                            </div>
                            <div class="invalid-feedback">
                                Enter contact name
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Phones</span>
                            </div>
                            <textarea id="contactPhones" class="form-control" aria-label="Phones" aria-describedby="phonesHelp" required></textarea>
                            <div class="invalid-feedback">
                                Enter valid phone numbers
                            </div>
                        </div>
                        <small id="phonesHelp" class="form-text text-muted">
                            Each phone number on a new line
                        </small>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Emails</span>
                            </div>
                            <textarea id="contactEmails" class="form-control" aria-label="Emails" aria-describedby="emailsHelp" required></textarea>
                            <div class="invalid-feedback">
                                Enter valid emails
                            </div>
                        </div>
                        <small id="emailsHelp" class="form-text text-muted">
                            Each email on a new line
                        </small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="addContactButton">Add</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(function() {
        function validateTextarea(field, regex) {
            const values = field.val().trim().split('\n');

            field[0].setCustomValidity("");

            for (const value of values) {
                if (!regex.test(value)) {
                    field[0].setCustomValidity("Invalid field.");
                    return false;
                }
            }

            return true;
        }

        function validatePhones() {
            return validateTextarea(
                $("#contactPhones"),
                /^((?:\+\d{1,4})?\d{6,14})$/
            );
        }

        function validateEmails() {
            return validateTextarea(
                $("#contactEmails"),
                /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/
            );
        }

        $('#addContactForm').submit(function(event) {
            $(this).removeClass('was-validated');

            if (this.checkValidity() === false && validatePhones() && validateEmails()) {
                //
            } else {
                event.preventDefault();
                event.stopPropagation();
            }

            $(this).addClass('was-validated');
        });

        $('#addContactModal').on('hidden.bs.modal', function(event) {
            $('#addContactForm').removeClass('was-validated')[0].reset();
        })

        $('#addContactButton').click(function() {
            $('#addContactForm').submit();
        });
    });
</script>
@endsection