<?php

namespace App\Http\Controllers;

use App\Contact;
use App\LeadService;
use App\LeadStatus;
use App\Message;
use App\MessageContact;
use App\Notifications\MessageNotification;
use App\Task;
use App\User;
use DataGrid;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Validation\Rules\In;
use Input;

class MessageController extends Controller
{
    public $currentMenuId = 'messages';

    public function index()
    {
        $this->authorize('showCategory', new Message());

        $grid = DataGrid::source(new Message());
        $grid->attributes(["class"=>"table table-striped table-bordered table-hover dataTables-example"]);

        $grid->add('id','ID', true)->style("width:100px");
        $grid->add('name','ФИО');
        $grid->add('statusName','Статус');
        $grid->add('updated_at','Обновлено');

        $grid->link(route('messages.store'), "Новая рассылка", "TR");

        $grid->orderBy('id','desc');
        $grid->paginate(100);

        $grid->row(function ($row) {
            $url = route('messages.view', ['messageId' => $row->data->id]);
            $name = $row->cell('name')->value;

            $row->cell('name')->value('<a href="'.$url.'">'.$name.'<a/>');
        });


        $title = 'Рассылка';
        $content = view('default.grid', compact('grid', 'title'));

        return view('default.base', compact('content'));
    }

    public function store($id = null)
    {
        $this->authorize('showCategory', new Message());

        $id = Input::get('modify', null) ?: $id;

        $isNew = $id == null;
        $source = $isNew ? new Message() : Message::find($id) ; // @todo: check

        $store = \DataForm::source($source);
        $store->add('name','Имя', 'text')->rule('required|min:2');
        $store->add('text','Текст', 'redactor')->rule('required|min:2');
        $store->add('type','Тип', 'select')->options(['email' => 'E-mail', 'sms' => 'СМС']);

        $usersOptions = [];
        foreach (Contact::all() as $_contact)
        {
            $usersOptions[$_contact->id] = $_contact->name;

            if ( $_contact->phone )
            {
                $usersOptions[$_contact->id] .= ' Тел: '.$_contact->phone;
            }

            if ( $_contact->email )
            {
                $usersOptions[$_contact->id] .= ' Email: '.$_contact->email;
            }
        }

        $store->add('user_ids','Контакты', 'multiselect')->options($usersOptions)->attributes(['class' => 'chosen-select-width', 'placeholder' => 'Выберите адресатов']);


        $store->submit('Сохранить');
        $store->saved(function () use ($store) {
            $message = $store->model;
            $message->contacts()->delete();

            $messageContacts = [];
            foreach (Input::get('user_ids', []) as $_contactId)
            {
                $messageContact = new MessageContact();
                $messageContact->message_id = $message->id;
                $messageContact->contact_id = $_contactId;

                $messageContacts[] = $messageContact;
            }

            $message->contacts()->saveMany($messageContacts);


            return redirect(route('messages.view', ['messageId' => $message->id]));
        });
        $store->build();

        $title = $isNew ? 'Новая рассылка' : 'Редактирование рассылки';
        return $store->view('default.store', compact('store', 'title'));
    }

    public function show($id)
    {
        $this->authorize('showCategory', new Message());

        $message = Message::with(['contacts.contact'])->findOrFail($id);
        return view('messages.view', compact('message', 'title'));
    }

    public function contactDelete($id)
    {
        $message = Message::findOrFail($id);
        $this->authorize('showCategory', $message);

        MessageContact::find(Input::get('contactId', 0))->delete();
        return redirect(route('messages.view', ['messageId' => $message->id]));
    }

    public function contactClean($id)
    {
        $message = Message::findOrFail($id);
        $this->authorize('showCategory', $message);

        $message->contacts()->delete();
        return redirect(route('messages.view', ['messageId' => $message->id]));
    }

    public function contacts(Request $request, $id)
    {
        $message = Message::findOrFail($id);
        $this->authorize('showCategory', $message);

        if ($request->isMethod('post'))
        {
            $messageContacts = [];
            foreach (Input::get('contacts', []) as $_contactId)
            {
                $messageContact = MessageContact::firstOrNew(['message_id' => $message->id, 'contact_id' => $_contactId]);
                $messageContact->message_id = $message->id;
                $messageContact->contact_id = $_contactId;

                $messageContacts[] = $messageContact;

            }
            $message->contacts()->saveMany($messageContacts);
        }

        $filter = \DataFilter::source(Contact::with('user')->whereNotIn('id', $message->contacts->pluck('contact_id')));
        $filter->add('topSearch','Поиск', 'text')->scope('topSearch');

        $userOptions = [null => 'Все менеджеры'] + User::all()->pluck('name', 'id')->toArray();
        $filter->add('user_id','Менеджер', 'select')->options($userOptions)->attributes(['class' => 'chosen-select-200px', 'data-placeholder' => 'Менеджер']);

        $leadStatuses = [null => 'Все статусы(сделки)'] + LeadStatus::all()->pluck('name', 'id')->toArray();
        $filter->add('lead_status','Статус сделок', 'select')->options($leadStatuses)->attributes(['class' => 'chosen-select-200px', 'data-placeholder' => 'Менеджер'])->scope('filterLeadStatuses');
        $filter->add('lead_update','Дата обн-ия сделки','Daterange')->scope('filterLeadUpdate');

        $taskStatuses = [null => 'Все статусы(задачи)'] + Task::$activeTypes;
        $filter->add('task_status','Статус задач', 'select')->options($taskStatuses)->attributes(['class' => 'chosen-select-200px', 'data-placeholder' => 'Менеджер'])->scope('filterTaskStatuses');
        $filter->add('status_update','Дата обн-ия задач','Daterange')->scope('filterStatusUpdate');


        /*
                $serviceOptions = [null => 'Все услуги'] + LeadService::all()->pluck('name', 'id')->toArray();
                $filter->add('service_type','Услуга', 'select')->options($serviceOptions)->attributes(['class' => 'chosen-select-200px', 'data-placeholder' => 'Услуга'])->scope('filterService');

                $leadOptions = [null => 'Все сделки', 'yes' => 'Есть активные сделки', 'no' => 'Нет активных сделок'];
                $filter->add('lead_type','Сделки', 'select')->options($leadOptions)->scope('filterLeads');

                $leadOptions = [null => 'Все оплаты', 'yes' => 'Планируются оплаты', 'no' => 'Есть оплаты'];
                $filter->add('payment_type','Оплаты', 'select')->options($leadOptions)->scope('filterPayments');
        */
        if ($request->isMethod('post'))
        {
            if ( Input::get('addedAll') == 'added' )
            {
                $filtercontacts = clone $filter;
                $filtercontacts->build();

                $contacts = $filtercontacts->query->limit(1000)->pluck('id');

                $messageContacts = [];
                foreach ($contacts as $_contactId)
                {
                    $messageContact = MessageContact::firstOrNew(['message_id' => $message->id, 'contact_id' => $_contactId]);
                    $messageContact->message_id = $message->id;
                    $messageContact->contact_id = $_contactId;

                    $messageContacts[] = $messageContact;

                }
                $message->contacts()->saveMany($messageContacts);
                return redirect(url()->current());
            }
        }

        $filter->submit('Фильтровать');
        $filter->reset('Сбросить');
        $filter->build();

        $grid = DataGrid::source($filter);
        $grid->attributes(["class"=>"table table-striped table-hover"]);

        $grid->add('id','ID', true)->style("width:100px");
        $grid->add('name','ФИО');
        $grid->add('<i class="fa fa-envelope"> </i> {{$email}}','E-mail');
        $grid->add('<i class="fa fa-phone"> </i> {{$phone}}','Телефон');

        $grid->link('#', "Добавить выбранные", "TR", ['id' => 'added', 'class' => 'btn btn-primary']);
        $grid->link('#', "Добавить всех", "TR", ['id' => 'addedAll', 'title' => 'Не более 1000 за раз', 'class' => 'btn btn-warning confirm']);
        $grid->link(route('messages.view', ['messageId' => $message->id]), "Назад", "TR");
        $grid->orderBy('id','desc');
        $grid->paginate(100);

        $grid->row(function ($row) {
            $url = route('contacts.view', ['contactId' => $row->data->id]);
            $name = $row->cell('name')->value;

            $row->cell('id')->value('<div class="checkbox"><label> <input name="contacts[]" type="checkbox" value="'.$row->data->id.'"> выбрать</label>');
            $row->cell('name')->value('<a class="client-link" href="'.$url.'">'.$name.'<a/>');
        });


        $title = 'Контакты';
        $content = view('default.grid', compact('grid', 'title'));

        return view('messages.contacts', compact('content', 'filter'));
    }

    public function send($id)
    {
        $this->authorize('showCategory', new Message());

        $message = Message::with(['contacts.contact'])->findOrFail($id);

        foreach ($message->contacts as $_contact)
        {
            $_contact->contact->notify(new MessageNotification($message->toArray()));
        }

        $message->status = 'completed';
        $message->save();

        return redirect(route('messages.view', ['contactId' => $message->id]));
    }


}
