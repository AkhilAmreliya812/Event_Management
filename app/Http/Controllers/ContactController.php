<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;
use Illuminate\Support\Carbon;
use Yajra\DataTables\Facades\DataTables;

class ContactController extends Controller
{
     // display all contact lead
     public function contactLead(Request $request)
     {
 
         if (!$request->ajax()) {
             return view('admin.contactLead');
         } else {
                
             $query = Contact::select('name', 'email', 'phone', 'description', 'created_at', 'id'); // Adjust columns as per your table
             if ($search = $request->input('search.value')) {
                 $query->where('name', 'like', "%{$search}%");
             }
 
             $orderColumnIndex = $request->input('order.0.column'); // e.g. 0
             $orderDirection = $request->input('order.0.dir');      // asc or desc
             $orderableColumns = ['name', 'email', 'created_at'];
 
             if (isset($orderableColumns[$orderColumnIndex])) {
                 $query->orderBy($orderableColumns[$orderColumnIndex], $orderDirection);
             }
 
             return datatables()->of($query)
                 ->addColumn('name', function ($row) {
                     return $row->name;
                 })
                 ->addColumn('email', function ($row) {
                     return $row->email;
                 })
                 ->addColumn('phone', function ($row) {
                     return $row->phone;
                 })
                 ->addColumn('created_at', function ($row) {
                     return $row->created_at ? with(new Carbon($row->created_at))->format('d-m-Y') : '';
                 })
                 ->addColumn('description', function ($row) {
                     $viewBtn = "";
                     $viewBtn .= '<i class="fa fa-commenting description_icon" aria-hidden="true" id="' . $row->id . '"></i>';
                     return $viewBtn;
                 })
                 ->rawColumns(['name', 'email', 'phone', 'description', 'created_at', 'action'])
                 ->addIndexColumn()
                 ->make(true);
         }
 
     }
 
     // get description by ajax
     public function getDescription(Request $request)
     {
         $description = Contact::where('id', $request->id)->value('description');
         return $description;
     }
}
