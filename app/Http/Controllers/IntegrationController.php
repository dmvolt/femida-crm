<?php

namespace App\Http\Controllers;

use App\Department;
use App\Lead;
use App\LeadStatus;
use App\Contact;
use App\ContactOrigin;
use App\Task;
use Carbon\Carbon;
use Input;
use Response;

class IntegrationController extends Controller
{
    public function __construct()
    {
    }

    public function integration($depId)
    {
        $department = Department::findOrFail($depId);
		// return Input::get();
		
		\Log::info('integration Поступили данные');

        $name = Input::get('name');
        $phone = Input::get('phone', 'не указан');
		$phone = preg_replace('/[^0-9]/', '', $phone);
		
		$task_flag = true;
		$flag = true;
		
		if (strlen($phone) >= 9) {
			if ($phone{0} != '7' && $phone{0} != '8'){
				$phone = "7".$phone;
			} else {
				$phone = "7".substr($phone, 1);
			}
		}
		
		\Log::info('integration Данные: name - '.$name.', phone - '.$phone);
		
		// Проверяем, сколько прошло времени с предидущей заявки
		if (($c = Contact::where('phone', $phone)->first()) !== null) {
			
			if (($t = $c->tasks->where('type', '=', 'request')->sortBy('created_at')->last()) !== null) {
				
				$task_flag = false;
				
				$dt = Carbon::createFromFormat('Y-m-d H:i', $t->created_at);
				
				if ($dt->diffInHours(Carbon::now()) > 1){
					$task_flag = true;
				}
			}
		}
		
		if ($phone == 'не указан' || $phone == '' || $phone == ' '){
			$flag = false;
		}
		
		if ($name == null || $name == '' || $name == ' '){
			$flag = false;
		}
		
		if ($flag) {
			$description = Input::get('description', '');
			$leadName = Input::get('lead_name', 'Заявка с сайта');
			$utm_source = Input::get('utm_source', 'нет');
			
			\Log::info('integration Данные: description - '.$description.', lead_name - '.$leadName);
			\Log::info('integration Данные: utm_source - '.$utm_source);
			
			$contact = Contact::firstOrNew(['phone' => $phone]);
			$contact->name = ($name == null) ? 'не указано' : $name;
			$contact->phone = $phone;
			
			if($utm_source && !empty($utm_source) && $utm_source == 'Yandex-Direct'){
				if($currentOrigin = ContactOrigin::where('name', 'Яндекс')->first()){
					$contact->origin_id = $currentOrigin->id;
				}
			} elseif($utm_source && !empty($utm_source) && $utm_source == 'Google-Adwords'){
				if($currentOrigin = ContactOrigin::where('name', 'Google')->first()){
					$contact->origin_id = $currentOrigin->id;
				}
			} else {
				if($currentOrigin = ContactOrigin::where('name', 'Заявка с сайта')->first()){
					$contact->origin_id = $currentOrigin->id;
				}
			}

			$contact->save();
			
			\Log::info('integration Contact Id - '.$contact->id);

			if($task_flag){
				
				$task = new Task();

				$task->name = $leadName;
				$task->description = $description;
				$task->type = 'request';
				$task->user_id = 0;
				$task->author_id = 0;
				$task->deadline = Carbon::now();
				$task->contact_id = $contact->id;
				$task->department_id = $department->id;
				$task->save();
				
				\Log::info('integration Task Id - '.$task->id);

				Task::updateNewRequestCount();
			}
		}
        return response()->json(null);
    }

    public function integrationWidget($depId)
    {
        $contents = view('integration.widget', ['depId' => $depId]);
        $response = Response::make($contents, 200);
        $response->header('Content-Type', 'application/javascript');

        return $response;
    }

    public function callbackkiller($depId)
    {
		\Log::info('callbackkiller Поступили данные');
		
        $name = Input::get('name', 'не указано');
        $phone = Input::get('phone', 'не указан');
		$phone = preg_replace('/[^0-9]/', '', $phone);
        $email = Input::get('email');
		
		$flag = true;
		
		if (strlen($phone) >= 9) {
			if ($phone{0} != '7' && $phone{0} != '8'){
				$phone = "7".$phone;
			} else {
				$phone = "7".substr($phone, 1);
			}
		}
		
		if ($phone == 'не указан' || $phone == '' || $phone == ' '){
			$flag = false;
		}
		
		if ($name == null || $name == '' || $name == ' ' || $name == 'не указано'){
			$flag = false;
		}
		
		\Log::info('callbackkiller Данные: name - '.$name.', phone - '.$phone);

		if($flag){
			$contact = Contact::firstOrNew(['phone' => $phone]);
			$contact->name = $name;
			$contact->phone = $phone;

			if ( $email )
			{
				$contact->email = $email;
				\Log::info('callbackkiller Данные: email - '.$email);
			}
			$contact->save();

			// @todo: check permissions
			$department = Department::findOrFail($depId);

			$task = new Task();
			$task->name = 'Звонок с CallbackKiller';
			$task->description = $this->getCallbackDescription();
			$task->type = 'request';
			$task->user_id = 0;
			$task->author_id = 0;
			$task->deadline = Carbon::now();
			$task->contact_id = $contact->id;
			$task->department_id = $department->id;
			$task->save();

			Task::updateNewRequestCount();
		}

        return response()->json(null);
    }

    protected function getCallbackDescription()
    {
        $description = '';

        $description .= 'Статус звонка: '.Input::get('call_state')."<br>";
        $description .= 'Длительность звонка: '.Input::get('call_duration')."<br>";
        $description .= 'Запись :'.Input::get('call_record')."\n\r"."<br>";

        return $description;
    }
}
