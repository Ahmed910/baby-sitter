<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\OfferRequest\changeStatusRequest;
use App\Http\Resources\Api\Notification\SenderResource;
use App\Models\Offer;
use App\Notifications\Offer\AcceptOffer;
use App\Notifications\Offer\ChangeOfferStatus;
use Illuminate\Http\Request;

class OfferRequestController extends Controller
{
    public function index()
    {
        $offer_requests = Offer::latest()->paginate(100);
        return view('dashboard.offer_request.index',compact('offer_requests'));
    }

    public function changeStatus(changeStatusRequest $request,$id)
    {
        // dd($request->validated());
        $offer = Offer::findOrFail($id);
        // if($offer->end_date < now()){
        //     return redirect(route('dashboard.offer_request.index'))->withFalse(trans('dashboard.messages.the_end_date_less_than_the_current_date'));
        // }
        $offer->update($request->validated());

        if(isset($offer->user)){
            $offer->user->notify(new ChangeOfferStatus($offer));
        }
        $fcm_notes = [
            'title' => $offer->status == 'accepted'? ['dashboard.notification.offer.offer_has_been_accepted_title']:['dashboard.notification.offer.offer_has_been_accepted_title'],
            'body' => $offer->status == 'accepted'? ['dashboard.notification.offer.offer_has_been_accepted_body', ['body' => auth()->user()->name ?? auth()->user()->phone]]:['dashboard.notification.offer.offer_has_been_rejected_body', ['body' => auth()->user()->name ?? auth()->user()->phone]],
            'sender_data' => new SenderResource(auth()->user())
        ];
        pushFcmNotes($fcm_notes, optional($offer->user)->devices);
        return redirect(route('dashboard.offer_request.index'))->withTrue(trans('dashboard.messages.offer_status_has_been_changed'));

    }

    public function show($id)
    {
        $offer_request = Offer::findOrFail($id);
        return view('dashboard.offer_request.show',compact('offer_request'));
    }


    // public function acceptOffer($offer_id)
    // {
    //     $offer = Offer::findOrFail($offer_id);
    //     if($offer->end_date < now()){
    //         return redirect(route('dashboard.offer_request.index'))->withFalse(trans('dashboard.messages.the_end_date_less_than_the_current_date'));
    //     }

    //     $offer->update(['status'=>'accepted']);

    //     if(isset($offer->user)){
    //         $offer->user->notify(new AcceptOffer($offer));
    //     }
    //     $fcm_notes = [
    //         'title' => ['dashboard.notification.offer.offer_has_been_accepted_title'],
    //         'body' => ['dashboard.notification.offer.offer_has_been_accepted_body', ['body' => auth()->user()->name ?? auth()->user()->phone]],
    //         'sender_data' => new SenderResource(auth()->user())
    //     ];
    //     pushFcmNotes($fcm_notes, optional($offer->user)->devices);
    //     return redirect(route('dashboard.offer_request.index'))->withTrue(trans('dashboard.messages.offer_accepted'));

    // }

    // public function rejectOffer($offer_id)
    // {
    //     $offer = Offer::findOrFail($offer_id);
    //     if($offer->end_date < now()){
    //         return redirect(route('dashboard.offer_request.index'))->withFalse(trans('dashboard.messages.the_end_date_less_than_the_current_date'));
    //     }
    //     $offer->update(['status'=>'rejected']);

    //     if(isset($offer->user)){
    //         $offer->user->notify(new AcceptOffer($offer));
    //     }
    //     $fcm_notes = [
    //         'title' => ['dashboard.notification.offer.offer_has_been_rejected_title'],
    //         'body' => ['dashboard.notification.offer.offer_has_been_rejected_body', ['body' => auth()->user()->name ?? auth()->user()->phone]],
    //         'sender_data' => new SenderResource(auth()->user())
    //     ];
    //     pushFcmNotes($fcm_notes, optional($offer->user)->devices);
    //     return redirect(route('dashboard.offer_request.index'))->withFalse(trans('dashboard.messages.offer_rejected'));

    // }
}
