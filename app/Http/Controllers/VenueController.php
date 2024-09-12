<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Venue;
use App\Models\PaymentMethodDetail;
use App\Models\OpeningHour;
use App\Models\VenueImage;
use App\Models\ServiceEvent;
use Illuminate\Support\Facades\Auth;

class VenueController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user && $user->role === 'owner') {
            $venues = Venue::with('venueImages')
                ->where('owner_id', $user->owner->id)
                ->orderBy('created_at', 'desc')
                ->get();

            return view('back.pages.owner.venue-manage.index-venue', compact('venues', 'user'));
        }

        return redirect()->route('login')->with('error', 'Akses ditolak. Anda bukan owner.');
    }
    public function create()
    {
        try {
            return view('back.pages.owner.venue-manage.add-venue');
        } catch (\Exception $e) {
            return redirect()->back();
        }
    }
    public function needApproval()
    {
        $venue = Venue::where('status', 0)->get();
        return view('back.pages.admin.manage-venue.need-approval.index', compact('venue'));
    }
    public function approved()
    {
        $venue = Venue::where('status', 1)->get();
        return view('back.pages.admin.manage-venue.approved.index', compact('venue'));
    }
    public function rejected()
    {
        $venue = Venue::where('status', 2)->get();
        return view('back.pages.admin.manage-venue.rejected.index', compact('venue'));
    }
    public function approveVenue($id)
    {
        $venue = Venue::findOrFail($id);
        $venue->status = 1;
        $saved = $venue->save();
        if ($saved) {
            return redirect()->route('admin.venue.approved', ['id' => $id])->with('success', 'Venue <b>' . ucfirst($venue->name) . '</b> dengan owner <b>' . ucfirst($venue->owner->name) . '</b> telah di Approve');
        } else {
            return redirect()->route('admin.venue.need-approval', ['id' => $id])->with('fail', 'Venue gagal di Approve, coba lagi');
        }
    }
    public function rejectVenue(Request $request, $id)
    {
        $venue = Venue::findOrFail($id);
        $venue->status = 2;
        $venue->reject_note = $request->input('reject_note');
        $saved = $venue->save();
        if ($saved) {
            return redirect()->route('admin.venue.rejected')->with('success', 'Venue <b>' . ucfirst($venue->name) . '</b> dengan owner <b>' . ucfirst($venue->owner->name) . '</b> telah di Reject');
        } else {
            return redirect()->route('admin.venue.need-approval')->with('fail', 'Venue gagal di Reject, coba lagi');
        }
    }
    public function detailVenue($id)
    {
        $venue = Venue::findOrFail($id);
        $payment_method_detail = PaymentMethodDetail::where('venue_id', $id)->get();
        $uniqueDays = OpeningHour::select('day_id')
            ->where('venue_id', $venue->id)
            ->groupBy('day_id')
            ->get();
        $openingHours = [];
        foreach ($uniqueDays as $uniqueDay) {
            $openingHours[$uniqueDay->day_id] = $venue
                ->openingHours()
                ->where('day_id', $uniqueDay->day_id)
                ->get();
        }
        $venue_image = VenueImage::where('venue_id', $id)->get();
        return view('back.pages.admin.manage-venue.detail', compact('venue', 'payment_method_detail', 'uniqueDays', 'openingHours', 'venue_image'));
    }
    public function show(Venue $venue)
    {
        $venue = Venue::findOrFail($venue->id);
        $payment_method_detail = PaymentMethodDetail::where('venue_id', $venue->id)->get();
        $uniqueDays = OpeningHour::select('day_id')
            ->where('venue_id', $venue->id)
            ->groupBy('day_id')
            ->get();
        $openingHours = [];
        foreach ($uniqueDays as $uniqueDay) {
            $openingHours[$uniqueDay->day_id] = $venue
                ->openingHours()
                ->where('day_id', $uniqueDay->day_id)
                ->get();
        }
        $service_events = ServiceEvent::where('venue_id', $venue->id)->get();
        $venue_image = VenueImage::where('venue_id', $venue->id)->get();
        return view('back.pages.owner.venue-manage.show-venue', compact('venue', 'payment_method_detail', 'uniqueDays', 'openingHours', 'venue_image', 'service_events'));
    }
    public function edit(Venue $venue)
    {

        try {
            $venue = Venue::findOrFail($venue->id);
            return view('back.pages.owner.venue-manage.edit-venue', compact('venue'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menampilkan halaman edit venue');
        }
    }
    public function destroy(Venue $venue)
    {
        try {
            $venueName = $venue->name;
            $venue->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
