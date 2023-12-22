<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@lang('Softic')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet" />
    <!-- Bootstrap 5 CSS -->
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    <!-- Styles -->
</head>
<body class="antialiased">
    <div class="container">
    <div class="shadow-sm p-3 mb-5 bg-white rounded my-5">
        <h5 class="text-secondary text-center mb-5">@lang('Transaction Details')</h5>
        <div class="text-end">
            <a href="{{route('home')}}" class="btn btn-success text-light mb-3">@lang('Home')</a>
            <a href="{{route('transaction.list')}}" class="btn btn-info text-light mb-3">@lang('Back')</a>
        </div>
        <table id="example" class="table table-hover" style="width:100%">
            <thead class="text-secondary">
            <tr>
                <th>@lang('#SL.')</th>
                <th>@lang('User Name')</th>
                <th>@lang('Amount')</th>
                <th>@lang('Trx no.')</th>
                <th>@lang('Balance')</th>
                <th>@lang('Created_at')</th>

            </tr>
            </thead>
            <tbody id="search">
            @foreach($transactions as $key=>$data)
                <tr>
                    <td>{{++$key}}</td>
                    <td>{{__(@$data->user->name)}}</td>
                    <td>{{__($data->amount)}}</td>
                    <td>{{__($data->trx)}}</td>
                    <td>{{$data->user->balance}}</td>
                    <td>
                        {{$data->created_at->format('d M, Y')}}
                    </td>
                </tr>
            @endforeach
            </tbody>

        </table>
    </div>


</div>
</body>

<script src="{{asset('js/bootstrap.min.js')}}" crossorigin="anonymous"></script>
<!-- jQuery -->
<script src='https://code.jquery.com/jquery-3.7.0.js'></script>
</html>
