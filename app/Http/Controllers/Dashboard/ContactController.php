<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\{Contact , ContactReply};
use Illuminate\Http\Request;
use App\Mail\Dashboard\ReplyContact;
use App\Http\Requests\Dashboard\Contact\{ContactRequest};
use App\Notifications\General\{GeneralNotification};

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!request()->ajax()) {
            $rMessages = Contact::latest()->readMessages();
            $unMessages = Contact::latest()->unReadMessages();
            if ($month=request('month')) {
                $rMessages->whereMonth('created_at',$month);
                $unMessages->whereMonth('created_at',$month);
             }
            if ($year=request('year')) {
                $rMessages->whereYear('created_at',$year);
                $unMessages->whereYear('created_at',$year);
            }
            $rMessages=$rMessages->get();
            $unMessages=$unMessages->get();
            return view('dashboard.contact.index',compact('unMessages','rMessages'));
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ContactRequest $request)
    {
        if (!request()->ajax()) {
            $contact = Contact::findOrFail($request->contact_id);
            $reply = ContactReply::create($request->validated()+['receiver_id' => $contact->sender_id , 'sender_id' => auth()->id()]);
            try{
                // \Mail::to($contact->email)->send(new ReplyContact($reply));
                $pushFcmNotes = [
                    'title' => trans('dashboard.fcm.reply_of_admin_on_ur_message'),
                    'body' => $reply->reply,
                    'notify_type' => 'management',
                ];
                if ($request->send_type == 'fcm') {
                    pushFcmNotes($pushFcmNotes, [$contact->user_id]);
                }else{
                    send_sms($contact->phone,$reply->reply);
                }
                \Notification::send($contact->user,new GeneralNotification($pushFcmNotes+['contact_id' => $contact->id]));
            }catch(\Exception $e){
                $notSend=1;
            }
            return redirect(route('dashboard.contact.show',$request->contact_id))->withTrue(trans('dashboard.messages.success_add'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!request()->ajax()) {
            $contact = Contact::findOrFail($id);

            foreach (auth()->user()->unreadNotifications as $notification) {
                if (isset($notification->data['contact_id']) && $notification->data['contact_id'] == $contact->id && ! $notification->read_at) {
                    $notification->markAsRead();
                }
            }

            if (is_null($contact->read_at)) {
                $contact->read_at=now();
                $contact->save();
              }
              $recentMsgs=Contact::whereNull('read_at')->where('id','<>',$contact->id)->latest()->take(5)->get();
              $archives=Contact::select(\DB::raw('YEAR(created_at) year, MONTH(created_at) month,MONTHNAME(created_at) month_name,COUNT(*) count'))
                      ->groupBy('year','month','month_name')
                      ->orderByRaw('min(created_at) desc')
                      ->get();
           return view('dashboard.contact.show',compact('contact','recentMsgs','archives'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contact $contact)
    {
        if ($contact->delete()) {
          return response()->json(['value' => 1]);
        }
    }

    public function deleteReply($id)
    {
        $contact = ContactReply::findOrFail($id);
        $contact->delete();
        $count = ContactReply::where('contact_id',$contact->contact_id)->count();
        return response()->json(['value'=>1,'count' => $count]);

    }

}
