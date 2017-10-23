@extends('layouts.default')

@section('content')
        <div class="row">
            <div class="col-md-12">
                {!! $filter or null !!}
                <form method="post" action="" id="contacts-form">
                    {!! csrf_field() !!}
                {!! $content !!}
                </form>
            </div>
        </div>

    <script type="text/javascript">
        $(function ()
        {
            $('#added').on('click', function ()
            {
                $('#contacts-form').submit();
            });

            $('#addedAll').on('click', function ()
            {
                var input = $("<input>")
                    .attr("type", "hidden")
                    .attr("name", "addedAll").val("added");
                $('#contacts-form').append($(input)).submit();
            });

            $('tr').on('click', function (e) {
                if ( ! $(e.target).is('input[type=checkbox]') && ! $(e.target).is('label'))
                {
                    var checkbox = $(this).find('input[type=checkbox]');
                    checkbox.prop('checked', ! checkbox.prop('checked'));
                }
            });
        });
    </script>
@endsection
