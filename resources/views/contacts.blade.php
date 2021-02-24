<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
    <script src="{{asset('js/jquery.min.js')}}"></script>
    <script src="{{asset('js/bootstrap.bundle.min.js')}}"></script>
</head>

<body>
    <div class="container">
        <p class="h3 text-center py-2">Контакты рассеянного друга</p>
        <table class="table table-sm table-bordered table-hover">
            <thead class="thead-dark">
                <tr>
                    <th scope="col" class="text-center">#</th>
                    <th scope="col" class="text-center">Имя</th>
                    <th scope="col" class="text-center">Телефоны</th>
                    <th scope="col" class="text-center">Email-ы</th>
                    <th scope="col" class="text-center" colspan="2">Действия</th>
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
</body>

</html>