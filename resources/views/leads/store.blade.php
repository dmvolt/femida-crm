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
                <div class="modal inmodal" id="addContactModal" tabindex="-1" role="dialog" aria-hidden="true">
                    <form method="post" action="{{route('contacts.addContact')}}">
                        {!! csrf_field() !!}
                        <div class="modal-dialog">
                            <div class="modal-content animated bounceInRight">
                                <div class="modal-header">
                                    <h4 class="modal-title">Добавить новый контакт</h4>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group"><label>Имя контакта</label> <input type="text" required name="name" placeholder="Имя контакта" class="form-control"></div>
                                    <div class="form-group"><label>Телефон</label> <input type="text" required name="phone" placeholder="Номер телефона" class="form-control"></div>
                                    <div class="form-group"><label>Почта</label> <input type="email" name="email" placeholder="Почта" class="form-control"></div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-white" data-dismiss="modal">Отмена</button>
                                    <button type="button" class="btn btn-primary" id="addContactButton">Сохранить</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <script type="text/javascript">
                    $(function ()
                    {
                        var addContactModal = $('#addContactModal');
                        $('#addContactButton').on('click', function () {

                            var form = addContactModal.find('form');
                            var data   = form.serialize();

                            if ( form[0].checkValidity()  )
                            {
                                $.ajax({
                                    type: 'POST',
                                    url: form.attr('action'),
                                    data: data,
                                    success: function(data)
                                    {
                                        var contact = $('#contact_id');
                                        if ( data.id )
                                        {
                                            contact.append("<option value='"+data.id+"'>"+data.name+"</option>");
                                            contact.val(data.id); // if you want it to be automatically selected
                                            contact.trigger("chosen:updated");
                                        }
                                        $('#addContactModal').modal('hide');
                                    }
                                });
                            }
                        });

                        $('#div_contact_id').append('<button type="button" data-toggle="modal" data-target="#addContactModal" class="btn btn-primary btn-sm" href="" style="margin-bottom: 0px;">Новый контакт</button>');

                        var serviceCosts = <?=$services?>;
                        var serviceSelect = $("#service_id");

                        updateBudget = function () {
                            $('#budget').val(serviceCosts[serviceSelect.val()]);
                        };

                        serviceSelect.chosen().change(function ()
                        {
                            updateBudget();
                        });

                        if (  $('#budget').val() == '' )
                        {
                            updateBudget();
                        }
                    })
                </script>
            </div>
        </div>
    </div>
@endsection