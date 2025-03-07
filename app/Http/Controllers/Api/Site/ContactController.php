<?php
namespace App\Http\Controllers\Api\Site;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ContactAmo;
use App\Models\Contact;

class ContactController extends Controller
{
    public function getContacts(Request $request)
    {
        $phones = Contact::query()
        ->where('phone', 'LIKE', '%'.$request->query('term') . '%')
        ->orWhere('name', 'LIKE', '%'.$request->query('term') . '%')
        ->orderBy('name')->paginate(10);

        return response()->json($phones);
    }

    public function getAmoContacts(Request $request)
    {
        $phones = ContactAmo::query()
            ->where('phone', 'LIKE', '%'.$request->query('term') . '%')
            ->orWhere('name', 'LIKE', '%'.$request->query('term') . '%')
            ->orderBy('name')->paginate(10);

        return response()->json($phones);
    }

    public function getByName(Request $request)
    {
        $contacts = Contact::query()
        ->where('name', 'LIKE', '%'.$request->query('q').'%')
        ->orderByDesc('id')->paginate(10,  ['*'], 'page', request()->query('page'));

        return response()->json($contacts);
    }

    public function getByPhone(Request $request)
    {
        $phones = Contact::query()
        ->where('phone', 'LIKE', '%'.$request->query('q') . '%')
        ->orderByDesc('id')->paginate(10,  ['*'], 'page', request()->query('page'));

        return response()->json($phones);
    }

    public function getBalance(Request $request)
    {
        $balance=Contact::find($request->id)->balance;

        return $balance;
    }

}
