@extends('layouts.auth')

@section('content')
        <div class="passwordBox animated fadeInDown">
        <div class="row">

            <div class="col-md-12">
                <div class="ibox-content">

                    <h2 class="font-bold">Сброс пароля</h2>

                    <div class="row">
                        <div class="col-lg-12">
                            @if (session('status'))
                                <div class="alert alert-success">
                                    {{ session('status') }}
                                </div>
                            @endif

                            <form class="m-t" role="form" method="POST" action="{{ url('/password/reset') }}">
                                {{ csrf_field() }}

                                <input type="hidden" name="token" value="{{ $token }}">

                                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                    <input type="email" class="form-control" placeholder="Email" name="email" value="{{ old('email') }}" required>

                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                    @endif
                                </div>

                                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                    <input type="password" class="form-control" placeholder="Пароль"  name="password" required>
                                    @if ($errors->has('password'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                                    <input type="password" class="form-control" placeholder="Повторите пароль"  name="password_confirmation" required>
                                    @if ($errors->has('password_confirmation'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('password_confirmation') }}</strong>
                                        </span>
                                    @endif
                                </div>


                                <button type="submit" class="btn btn-primary block full-width m-b">Сохранить</button>
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
