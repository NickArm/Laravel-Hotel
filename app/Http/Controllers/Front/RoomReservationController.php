<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Events\NewReservationEvent;
use App\Events\RefreshDashboardEvent;
use App\Helpers\Helper;
use App\Http\Requests\ChooseRoomRequest;
use App\Http\Requests\StoreCustomerRequest;
use App\Models\Customer;
use App\Models\Room;
use App\Models\Transaction;
use App\Models\User;
use App\Notifications\NewRoomReservationDownPayment;
use App\Repositories\CustomerRepository;
use App\Repositories\PaymentRepository;
use App\Repositories\ReservationRepository;
use App\Repositories\TransactionRepository;
use Illuminate\Support\Facades\DB;



class RoomReservationController extends Controller
{


    public function __construct(ReservationRepository $reservationRepository)
    {
        $this->reservationRepository = $reservationRepository;
    }


    private function getOccupiedRoomID($stayFrom, $stayUntil) 
    {
        $occupiedRoomId = Transaction::where([['check_in', '<=', $stayFrom], ['check_out', '>=', $stayUntil]])
            ->orWhere([['check_in', '>=', $stayFrom], ['check_in', '<=', $stayUntil]])
            ->orWhere([['check_out', '>=', $stayFrom], ['check_out', '<=', $stayUntil]])
            ->pluck('room_id');
        return $occupiedRoomId;
    }



    public function chooseRoom(Request $request)
    {
        $stayFrom = $request->check_in;
        $stayUntil = $request->check_out;

        $occupiedRoomId = $this->getOccupiedRoomID($request->check_in, $request->check_out);

        $rooms = $this->reservationRepository->getUnocuppiedroom($request, $occupiedRoomId);
        $roomsCount = $this->reservationRepository->countUnocuppiedroom($request, $occupiedRoomId);

        return view('front.chooseRoom', compact('rooms', 'stayFrom', 'stayUntil', 'roomsCount'));
    }


    public function confirmation(Room $room, $stayFrom, $stayUntil)
    {
        $price = $room->price;
        $dayDifference = Helper::getDateDifference($stayFrom, $stayUntil);
        $downPayment = ($price * $dayDifference) * 0.15;
        return view('front.confirmation', compact('room', 'stayFrom', 'stayUntil', 'downPayment', 'dayDifference'));
    }

    public function getcustomerdata(Request $request){

        $email = $request->email;
        $result = DB::table('users')
        ->where('users.email','=', $email)
        ->join('customers', 'customers.user_id', '=', 'users.id')
        ->select('*')->get();
        return $result;
    }

    public function payDownPayment(Room $room, Request $request, TransactionRepository $transactionRepository, PaymentRepository $paymentRepository)
    {
        $customer = Customer::where('id', '=' ,$request->customer_id)->get();
        //dd($customer);
        $dayDifference = Helper::getDateDifference($request->check_in, $request->check_out);
        $minimumDownPayment = ($room->price * $dayDifference) * 0.15;
        
        $request->validate([
            'downPayment' => 'required|numeric|gte:' . $minimumDownPayment
        ]);

        $occupiedRoomId = $this->getOccupiedRoomID($request->check_in, $request->check_out);
        $occupiedRoomIdInArray = $occupiedRoomId->toArray();
       
        if (in_array($room->id, $occupiedRoomIdInArray)) {
            return redirect()->back()->with('failed', 'Sorry, room ' . $room->number . ' already occupied');
        }

        $transaction = $transactionRepository->store($request, $customer[0], $room);
        $status = 'Down Payment';
        $payment = $paymentRepository->store($request, $transaction, $status);

        //dd($customer);
        /*
        $superAdmins = User::where('role', 'Super')->get();

        foreach ($superAdmins as $superAdmin) {
            $message = 'Reservation added by ' . $customer->name;
            event(new NewReservationEvent($message, $superAdmin));
            $superAdmin->notify(new NewRoomReservationDownPayment($transaction, $payment));
        }*/

        //event(new RefreshDashboardEvent("Someone reserved a room"));

        return view('home')->with('success', 'Room ' . $room->number . ' has been reservated by ' . $customer[0]->name);
    }
}
