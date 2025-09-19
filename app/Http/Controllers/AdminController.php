<?php
namespace App\Http\Controllers;

use App\Models\Contact;

class AdminController extends Controller
{
    
    public function dashbord()
    {
        $latestContactLead = Contact::latest()->take(5)->get();
        return view('admin.dashbord', ['conatctLead' => $latestContactLead]);
    }
    // logged in user profile
    
}
