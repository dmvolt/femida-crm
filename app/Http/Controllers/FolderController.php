<?php

namespace App\Http\Controllers;

use App\Folder;
use App\MyFile;
use File;
use Illuminate\Http\Request;
use Session;
use Storage;

class FolderController extends Controller
{
    // @todo: check permission

    public function create($contactId, Request $request)
    {
        $folder = new Folder();
        $folder->contact_id = $contactId;
        $folder->name = \Input::get('name');
        $folder->parent_id = \Input::get('parent_id', 0);
        $folder->save();


        $url = Session::get('contactRedirect', '/contacts#contact'.$contactId);
        return redirect($url);
    }

    public function delete($contactId, Request $request)
    {
        $folder = Folder::findOrFail($request->get('folder'));
        if ( $folder->contact_id == $contactId )
        {
            // @todo: remove files and children folders
            $folder->delete();
        }

        $url = Session::get('contactRedirect', '/contacts#contact'.$contactId);
        return redirect($url);
    }

    public function upload($contactId, Request $request)
    {
        $file = $request->file('file');
        $extension = $file->getClientOriginalExtension();
        Storage::disk('local')->put('/public/'.$contactId.'/'.$file->getFilename().'.'.$extension,  File::get($file));

        $entry = new MyFile();
        $entry->mime = $file->getClientMimeType();
        $entry->original_filename = $file->getClientOriginalName();
        $entry->filename = $file->getFilename().'.'.$extension;
        $entry->folder_id = $request->get('folder');
        $entry->save();

        $url = Session::get('contactRedirect', '/contacts#contact'.$contactId);
        return redirect($url);
    }
}
