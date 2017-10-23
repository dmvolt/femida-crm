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
        {!! $filter or null !!}
        {!! $grid !!}
    </div>
</div>