<?php
namespace App\Http\Controllers;

use App\Models\Contact;

class AdminController extends Controller
{
    public function dashbord()
    {
        $latestContactLead = Contact::orderBy('id', 'DESC')->limit(5)->get();
        return view('admin.dashbord', ['conatctLead' => $latestContactLead]);
    }
}
