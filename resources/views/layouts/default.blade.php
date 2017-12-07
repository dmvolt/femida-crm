<!DOCTYPE html>
<html lang="ru">

<head>
    <?php
        $user = \Auth::user();
    ?>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{Config::get('crm.title')}}</title>

    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="/js/plugins/toastr/toastr.min.css" rel="stylesheet">
    <link href="/js/plugins/gritter/jquery.gritter.css" rel="stylesheet">
    <link href="/css/animate.css" rel="stylesheet">
    <link href="/css/style.css" rel="stylesheet">

    <link href="/css/plugins/chosen/chosen.css" rel="stylesheet">
    <link href="/css/plugins/morris/morris-0.4.3.min.css" rel="stylesheet">
    <link href="/css/plugins/datapicker/datepicker3.css" rel="stylesheet">

    <script src="/js/jquery-2.1.1.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/plugins/jquery-mousewheel/jquery.mousewheel.min.js"></script>
    <script src="/js/plugins/morris/raphael-2.1.0.min.js"></script>
    <script src="/js/plugins/morris/morris.js"></script>


    <link media="all" type="text/css" rel="stylesheet" href="/packages/zofe/rapyd/assets/redactor/css/redactor.css">
    <link media="all" type="text/css" rel="stylesheet" href="/packages/zofe/rapyd/assets/datetimepicker/datetimepicker3.css">
    <link media="all" type="text/css" rel="stylesheet" href="/packages/zofe/rapyd/assets/rapyd.css">

    <link href="/css/plugins/ladda/ladda-themeless.min.css" rel="stylesheet">
    <link href="/css/plugins/bootstrap-markdown/bootstrap-markdown.min.css" rel="stylesheet">
    <link href="/css/plugins/jsTree/style.min.css" rel="stylesheet">
    <script>
        window.Laravel = <?php echo json_encode([
                'csrfToken' => csrf_token(),
        ]); ?>
    </script>
    <style>
        div.chzn-container div.chzn-drop {
            z-index: 999;
        }
    </style>
</head>

<body>
<div id="wrapper">

    @include('layouts.menu.left')

    <div id="page-wrapper" class="gray-bg">
        <div class="row border-bottom">
            <nav class="navbar navbar-static-top white-bg" role="navigation" style="margin-bottom: 0">
                <div class="navbar-header">
                    <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
                    <form role="search" class="navbar-form-custom" method="get" action="">
                        <div class="form-group">
                            <input type="text"  value="{{Input::get('topSearch', null)}}" placeholder="Поиск..." class="form-control" name="topSearch" id="top-search">
                            <input type="hidden" value="1" name="search">
                            <input type="hidden" value="" name="updated_at[from]">
                            <input type="hidden" value="" name="updated_at[to]">
                            <input type="hidden" name="deadline[from]" value="">
                            <input type="hidden" name="deadline[to]" value="">
                        </div>
                    </form>
                </div>
                <ul class="nav navbar-top-links navbar-right">
                    <li>
                        <span class="m-r-sm text-muted welcome-message"><img src="/logo_mini.png" alt="Мем Фемиды" class="" /> {{Config::get('crm.title')}}</span>
                    </li>

                    <li>
                        <a href="{{ url('/logout') }}"
                           onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                            <i class="fa fa-sign-out"></i> Выход
                        </a>

                        <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </li>
                </ul>

            </nav>
        </div>
        {{--@include('layouts.menu.breadcrumbs')--}}
        @yield('content')

    </div>
</div>

<!-- Chosen -->
<script src="/js/plugins/chosen/chosen.jquery.js"></script>

<!-- Mainly scripts -->
<script src="/js/plugins/metisMenu/jquery.metisMenu.js"></script>
<script src="/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

<!-- Flot -->
<script src="/js/plugins/flot/jquery.flot.js"></script>
<script src="/js/plugins/flot/jquery.flot.tooltip.min.js"></script>
<script src="/js/plugins/flot/jquery.flot.spline.js"></script>
<script src="/js/plugins/flot/jquery.flot.resize.js"></script>
<script src="/js/plugins/flot/jquery.flot.pie.js"></script>

<!-- Custom and plugin javascript -->
<script src="/js/inspinia.js"></script>
<script src="/js/plugins/pace/pace.min.js"></script>


<!-- jQuery UI -->
<script src="/js/plugins/jquery-ui/jquery-ui.min.js"></script>

<!-- GITTER -->
<script src="/js/plugins/gritter/jquery.gritter.min.js"></script>

<!-- Sparkline -->
<script src="/js/plugins/sparkline/jquery.sparkline.min.js"></script>

<!-- ChartJS-->
<script src="/js/plugins/chartJs/Chart.min.js"></script>

<!-- Toastr -->
<script src="/js/plugins/toastr/toastr.min.js"></script>

<script src="/packages/zofe/rapyd/assets/redactor/jquery.browser.min.js"></script>
<script src="/packages/zofe/rapyd/assets/redactor/redactor.min.js"></script>
<script src="/packages/zofe/rapyd/assets/datetimepicker/bootstrap-datetimepicker.js"></script>
<script src="/packages/zofe/rapyd/assets/datetimepicker/locales/bootstrap-datetimepicker.ru.js"></script>

<!-- Bootstrap markdown -->
<script src="/js/plugins/bootstrap-markdown/bootstrap-markdown.js"></script>
<script src="/js/plugins/bootstrap-markdown/markdown.js"></script>

<script src="/js/plugins/ladda/spin.min.js"></script>
<script src="/js/plugins/ladda/ladda.min.js"></script>
<script src="/js/plugins/ladda/ladda.jquery.min.js"></script>
<script src="/js/plugins/datapicker/bootstrap-datepicker.js"></script>
<script src="/js/plugins/jsTree/jstree.min.js"></script>

<script src="/js/plugins/inputmask/jquery.inputmask.bundle.js" type="text/javascript"></script>
<script src="/js/jquery.tablesorter.js"></script>

{!! Rapyd::scripts() !!}

<script type="text/javascript">
    $(document).on('click', '.glyphicon.glyphicon-trash,.confirm', function () {
        return confirm('Вы действительно хотите это сделать?');
    });

    $(".chosen-select").chosen();
    $(".chosen-select-80").chosen({width: "80%"});
    $(".chosen-select-width").chosen({width: "100%"});
    $(".chosen-select-200px").chosen({width: "200px"});


    $('.markdown-text').each(function ()
    {
        var text = $.trim($(this).html());
        $(this).html(markdown.toHTML(text));
    });

    $('.datetime-input').datetimepicker({
        format: 'yyyy-mm-dd hh:ii',
        language: 'ru',
        todayBtn: 'linked',
        autoclose: true,
        orientation: "auto"
    });

    $('.date-input').datepicker({
        format: 'yyyy-mm-dd',
        language: 'ru',
        todayBtn: 'linked',
        autoclose: true
    });
	
	// Маска для ввода суммы
	$('.sum').on('input', function () {
		var fieldText = this.value.replace(/\D/g, '');
		var bufer = '';

		if (fieldText.length > 3) {
			while (fieldText.length > 3) {
				var tail = fieldText.substr(fieldText.length - 3, fieldText.length);
				fieldText = fieldText.substr(0, fieldText.length - 3);
				bufer = ' ' + tail + bufer;
			}
		}

		this.value = fieldText + bufer;
	});

    $(document).ready(function ()
    {
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "progressBar": true,
            "preventDuplicates": false,
            "positionClass": "toast-top-right",
            "onclick": null,
            "showDuration": "400",
            "hideDuration": "1000",
            "timeOut": "7000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut",
            'escapeHtml': true,
        };
		
		// Input masks
		$(".phone").inputmask("+7(999) 999-9999");
		$(".birth-date").inputmask("99-99-9999");
		//$(".sum").inputmask({mask: "(9 999)|(99 999)|(999 999)", greedy: false});
		$(".inn").inputmask("999999999999");
		$(".passport-num").inputmask("9999 999999");
		$(".passport-code").inputmask("999-999");
		
		// Sorts table
		$('#user_table').tablesorter(); 
	
        <?php
            foreach ($user->unreadNotifications as $notification)
            {
                $data = $notification->toArray()['data'];
                echo 'toastr["'.$data['type'].'"]("'.$data["message"].'", "'.$data['title'].'");';

            }
            $user->unreadNotifications()->update(['read_at' => Carbon\Carbon::now()]);
        ?>
    });
</script>
</body>
</html>
