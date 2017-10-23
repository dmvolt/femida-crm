<?php

namespace App\Http\Traits;

use Input;

trait DefaultControllerTrait
{

    public function deleteAction($model, $redirectRouteName)
    {
        if ( $deleteId = Input::get('delete', null) )
        {
            $model = $model->findOrFail($deleteId);
            $this->authorize('delete', $model);

            $model->delete();

            return redirect(route($redirectRouteName));
        }
    }

    public function getSource($id, $model)
    {

        $id = Input::get('modify', null) ?: $id;

        if ( $id )
        {
            $source = $model->find($id);
            $this->authorize('update', $source);
        }
        else
        {
            $source = $model;
        }

        return $source;
    }

    public function preferValues($store)
    {
        foreach ($store->fields as $field_obj) {
            if (in_array($field_obj->type, array('select','text')))
            {
                $fieldName = $field_obj->name;
                $prepareValue = Input::get($fieldName , null);

                if ( $prepareValue )
                {
                    $field_obj->value = $prepareValue;
                }
            }
        }
    }
}
