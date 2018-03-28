<div>
    <div class="feed-activity-list">
        <div class="tabs-container">
            <ul class="nav nav-tabs">
                <li class=""><a data-toggle="tab" href="#tab-1" aria-expanded="true">История изменений</a></li>
                <li class=""><a data-toggle="tab" href="#tab-2" aria-expanded="false">Комментарии</a></li>
                <li class="active"><a data-toggle="tab" href="#tab-3" aria-expanded="true">Сделки @if($contact->leads->count())<span class="badge">{{$contact->leads->count()}}</span>@endif</a></li>
                <li class=""><a data-toggle="tab" href="#tab-4" aria-expanded="false">Файлы @if($contact->allFilesCount())<span class="badge">{{$contact->allFilesCount()}}</span>@endif</a></li>
            </ul>
            <div class="tab-content">

                @include('contacts.view.activity.tabs.history', ['contact' => $contact])
                @include('contacts.view.activity.tabs.activitiy', ['contact' => $contact])
                @include('contacts.view.activity.tabs.leads', ['contact' => $contact])
                @include('contacts.view.activity.tabs.files', ['contact' => $contact])


                <div id="tab-41" class="tab-pane">

                </div>
            </div>
        </div>
    </div>
</div>
