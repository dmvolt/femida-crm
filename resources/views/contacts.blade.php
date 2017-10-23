<div class="ibox float-e-margins">
	{!! $deps !!}
    <div class="ibox-title">
        <h5>{{$title or 'Укажите заголовок'}}</h5>
        <div class="ibox-tools">
            <a class="collapse-link">
                <i class="fa fa-chevron-up"></i>
            </a>
        </div>
    </div>
    <div class="ibox-content">
        {!! $grid !!}
    </div>

    @include('contacts.profileModal')
</div>
