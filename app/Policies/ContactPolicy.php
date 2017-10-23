<?php

namespace App\Policies;

use App\Contact;
use App\User;

class ContactPolicy extends AbstractPolicy
{

    public function show(User $user, Contact $contact)
    {
        return true;
    }


    public function update(User $user, Contact $contact)
    {
        return $this->show($user, $contact);
    }

    public function delete(User $user, Contact $contact)
    {
        return $contact->user_id == $user->id;
    }

}
