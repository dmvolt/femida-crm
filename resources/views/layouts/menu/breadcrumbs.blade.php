<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>{{$pageTitle or ''}}</h2>
        <ol class="breadcrumb">
            <li>
                <a href="/">Dashboard</a>
            </li>

            @stack('breadcrumb')
        </ol>
    </div>
    <div class="col-lg-2">

    </div>
</div>
