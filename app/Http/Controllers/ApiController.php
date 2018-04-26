<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use App\Contact;
use App\ContactData;
use App\ContactOrigin;
use Carbon\Carbon;
use Input;
use Response;

class ApiController extends BaseController
{
	public function addLead()
    {
		//api/addLead?lastName=Иванов&firstName=Иван&secondName=Сергеевич&phone=89139845629&amount=300000&city=Сочи

		if(Input::get()){
			
			\Log::info('API Поступили данные');

			$lastName = Input::get('lastName');
			$firstName = Input::get('firstName');
			$secondName = Input::get('secondName');
			
			$name = $lastName.' '.$firstName.' '.$secondName;
			
			$phone = Input::get('phone', 'не указан');
			$phone = preg_replace('/[^0-9]/', '', $phone);
			
			$sum = Input::get('amount');
			$city = Input::get('city');

			$flag = true;
			
			if (strlen($phone) >= 9) {
				if ($phone{0} != '7' && $phone{0} != '8'){
					$phone = "7".$phone;
				} else {
					$phone = "7".substr($phone, 1);
				}
			}
			
			\Log::info('API Данные: name - '.$name.', phone - '.$phone);
			
			if ($flag) {
				
				$contact = Contact::firstOrNew(['phone' => $phone]);
				$contact->name = ($name == null) ? 'не указано' : $name;
				$contact->phone = $phone;
				
				if($currentOrigin = ContactOrigin::where('name', 'ЛИД-КРЕДИТ')->first()){
					$contact->origin_id = $currentOrigin->id;
				}
				else
				{
					$contact->origin_id = 5;
				}

				$contact->save();
				
				$contactData = ContactData::firstOrNew(['contact_id' => $contact->id]);
				
				$contactData->credit_sum = $sum;
				$contactData->contact_address = $city;
				$contactData->address = $city;
				$contactData->contact_id = $contact->id;
				
				$contactData->save();
				
				\Log::info('API Contact Id - '.$contact->id);
			}
			
			return response('', 200);
		}
		else
		{
			return response('Нет данных', 204);
		}
    }
}
