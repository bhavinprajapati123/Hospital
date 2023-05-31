<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Appointment;
use App\Exports\DoctorExport;
use Maatwebsite\Excel\Facades\Excel;

class HomeController extends Controller
{
    public  function redirect()
    {
        if(Auth::id())
        {
            if(auth::user()->usertype=='0')
            {
                $doctor=Doctor::all();
                return view('user.home',compact('doctor'));
            }
            else
            {
                return view('admin.home');
            }
        }
        else
        {
            return redirect()->back();
        }
    }

    public function index()
    {
        if(Auth::id())
        {
            return redirect('home');
        }
        else
        {
            $doctor=Doctor::all();
            return view('user.home',compact('doctor'));
        }

    }

    public function appointment(Request $request)
    {
        $data=new appointment;
        $data->name=$request->name;
        $data->email=$request->email;
        $data->date=$request->date;
        $data->phone=$request->number;
        $data->message=$request->message;
        $data->doctor=$request->doctor;
        $data->status='In Progress';
        if(Auth::id())
        {
            $data->user_id=Auth::user()->id;
        }

        $data->save();
        return redirect()->back()->with('message','Appointment Request Successful.We will Contact You Soon. ');
    }

    public function myappointment()
    {
        if(Auth::id())
        {
            $userid=Auth::user()->id;
            $appoint=appointment::where('user_id',$userid)->get();
            return view('user.my_appointment',compact('appoint'));
        }
        else
        {
            return redirect()->back();
        }

    }

    public function cancel_appoint($id)
    {
        $data=appointment::find($id);
        $data->delete();
        return redirect()->back();
    }

    public function downloaddoctors()
    {
        return Excel::download(new DoctorExport, 'doctors.xlsx');
    }

}
