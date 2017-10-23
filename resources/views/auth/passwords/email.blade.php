@extends('layouts.auth')

<!-- Main Content -->
@section('content')
    <div class="passwordBox animated fadeInDown">
        <div class="row">

            <div class="col-md-12">
                <div class="ibox-content">

                    <h2 class="font-bold">Востановить пароль</h2>

                    <p>
                        Введите вашу почту, на которую зарегестрирован аккаунт
                    </p>

                    <div class="row">
                        <div class="col-lg-12">
                            @if (session('status'))
                                <div class="alert alert-success">
                                    {{ session('status') }}
                                </div>
                            @endif

                            <form class="m-t" role="form" method="POST" action="{{ url('/password/email') }}">
                                {{ csrf_field() }}
                                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                    <input type="email" class="form-control" placeholder="Email" name="email" value="{{ old('email') }}" required>

                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                    @endif
                                </div>

                                <button type="submit" class="btn btn-primary block full-width m-b">Получить пароль</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr>

        <div class="row">
            <div class="col-md-6">
                Sky Development
            </div>
            <div class="col-md-6 text-right">
                <small>© 2016</small>
            </div>
        </div>
    </div>
@endsection
