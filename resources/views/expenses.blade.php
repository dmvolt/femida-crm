@extends('layouts.default')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>{{$title or 'Укажите заголовок'}}</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    {!! $filter !!}
                    {!! $grid !!}
                    <table class="table">
                        </thead>
                        <tbody>
                        <tr>
                            <td>Итоговая сумма</td>
                            <td>{{$resultSum}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
