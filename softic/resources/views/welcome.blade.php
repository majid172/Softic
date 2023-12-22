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
        <div class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center bg-gray-100 dark:bg-dots-lighter dark:bg-gray-900 selection:bg-red-500 selection:text-white">
            @if (Route::has('login'))
                <div class="sm:fixed sm:top-0 sm:right-0 p-6 text-right z-10">
                    @auth
                        <a href="{{ url('/home') }}" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Home</a>
                    @else
                        <a href="{{ route('login') }}" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Log in</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="ml-4 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Register</a>
                        @endif
                    @endauth
                </div>
            @endif
        </div>

        <div class="container">

            <div class="shadow-sm p-3 mb-5 bg-white rounded my-3">
                <h5 class="text-secondary text-center mb-5">@lang('User Details with Payment System')</h5>
                <div class="row mb-3">
                    <div class="col-5">
                        <form action="{{route('search')}}" method="GET">
                            @csrf
                        <div class="d-flex ">
                            <input type="text" name="search" class="form-control mx-2 search" placeholder="@lang('Search username')">
                        </div>
                        </form>
                    </div>
                    <div class="col-7 text-end">
                        <a class="btn btn-sm btn-info text-light" href="{{route('transaction.list')}}">@lang('Transaction lists')</a>
                    </div>
                </div>

                <table id="example" class="table table-hover" style="width:100%">
                    <thead class="text-secondary">
                    <tr>
                        <th>@lang('#SL.')</th>
                        <th>@lang('Name')</th>
                        <th>@lang('Email')</th>
                        <th>@lang('Date_of_birth')</th>
                        <th>@lang('Payment')</th>

                    </tr>
                    </thead>
                    <tbody id="search">
                    @foreach($users as $key=>$user)
                        <tr>
                            <td>{{++$key}}</td>
                            <td>{{__($user->name)}}</td>
                            <td>{{__($user->email)}}</td>
                            <td>{{__($user->date_of_birth)}}</td>
                            <td>
                                <button type="button"  class="btn btn-outline-primary btn-sm pay"  data-bs-toggle="modal" data-name="{{__($user->name)}}"  data-id="{{$user->id}}" data-bs-target="#staticBackdrop">
                                    @lang('Pay now')
                                </button>

                            </td>
                        </tr>
                    @endforeach
                    </tbody>

                </table>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">@lang('Payment')</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{route('payment.store')}}" method="post">
                            @csrf
                        <div class="modal-body">
                            <h6 class="body_text text-secondary"></h6>
                                <input type="hidden" name="user_id" id="id">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="exampleInputPassword1" class="form-label">@lang('Choose Gateway')</label>
                                            <select name="gateway" id="gateway" class="form-control">
                                                <option value="stripe">@lang('Stripe')</option>
                                                <option value="paypal">@lang('Paypal')</option>

                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="exampleInputPassword1" class="form-label">@lang('Amount')</label>
                                            <input type="number" class="form-control" name="amount" value="{{ old('amount') }}" min="5" placeholder="@lang('Enter your amount')">
                                        </div>
                                    </div>
                                </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">@lang('Close')</button>
                            <button type="submit" class="btn btn-primary btn-sm">@lang('Pay')</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </body>

    <script src="{{asset('js/bootstrap.min.js')}}" crossorigin="anonymous"></script>
    <!-- jQuery -->
    <script src='https://code.jquery.com/jquery-3.7.0.js'></script>

<script>
    $('.search').on('input',function (){
        var search = $(this).val();
        $.ajax({
            url: '{{route('search')}}',
            method: 'GET',
            data:{
                search:search
            },
            success: function(data) {
                var userlists = data.user;
                $('#search').html('');
                $.each(userlists, function (index,item){
                    let markup = `<tr>
                            <td>${++index}</td>
                            <td>${item.name}</td>
                            <td>${item.email}</td>
                            <td>${item.date_of_birth??''}</td>
                            <td>
                                <button type="button"  class='btn btn-outline-info btn-sm pay' data-bs-toggle="modal" data-name="${item.name}"  data-id="${item.id}}" data-bs-target="#staticBackdrop">
                                    Pay now
                        </button>

                    </td>
                </tr>`
                    $('#search').append(markup);
                });
            },
            error: function(xhr, status, error) {
                console.error('Error:', status, error);
            }
        });
    })
</script>
    <script>

        $(document).on('click','.pay',function (){
            var modal  = $('#staticBackdrop');
            var text = $(this).data('name')+' '+'get payment';
            modal.find('input[name="user_id"]').val($(this).data('id'));

            $('.body_text').empty();
            $('.body_text').text(text);
        });
    </script>
</html>
