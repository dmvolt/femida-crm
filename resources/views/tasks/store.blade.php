@extends('layouts.default')

@section('content')
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
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
                        {!! $store !!}
                    </div>
                </div>

                <script type="text/javascript">
                    $(function ()
                    {
                        updateVisibleField = function () {
                            var fieldCost = $('#fg_cost');
                            if ($('#type').val() == 'approved_payment')
                            {
                                fieldCost.show();
                            }
                            else
                            {
                                fieldCost.hide();
                            }
                        };

                        $('#type').chosen().change(function ()
                        {
                            updateVisibleField();
                        });

                        updateVisibleField();
                    })
                </script>
            </div>
        </div>
    </div>
@endsection