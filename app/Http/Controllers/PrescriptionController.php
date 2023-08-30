<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\PaymentDetails;
use App\Models\Prescription;
use App\Models\PrescriptionDetails;
use App\Models\Service;
use Auth;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class PrescriptionController extends Controller
{
    public function PrescriptionAll()
    {

        $prescription = Prescription::latest()->get();

        return view('backend.prescription.prescription_all', compact('prescription'));

    }

    public function PrescriptionAdd()
    {

        // Retrieve all categories and customers
        $service = Service::all();
        $customer = Customer::all();

        // Get the last prescription number and increment it by 1
        $prescription_data = Prescription::orderBy('id', 'desc')->first();
        $prescription_no = $prescription_data ? $prescription_data->prescription_no + 1 : 1;

        // Get the current date
        $date = date('Y-m-d');

        // Return the view with the necessary data
        return view('backend.prescription.prescription_add', compact('prescription_no', 'service', 'date', 'customer'));

    }

    public function PrescriptionStore(Request $request)
    {

        if ($request->service_id === null) {
            $notification = array(
                'message' => 'Sorry , no item selected',
                'alert-type' => 'error',
            );

            return redirect()->back()->with($notification);
        } else {

            if ($request->paid_amount > $request->estimated_amount) {

                $notification = array(
                    'message' => 'Paid amount is greater than grand total',
                    'alert-type' => 'warning',
                );

                return redirect()->back()->with($notification);

            } else {
                $prescription = new Prescription();
                $prescription->prescription_no = $request->prescription_no;
                $prescription->date = date('Y-m-d', strtotime($request->date));
                $prescription->description = $request->description;
                $prescription->status = '0';
                $prescription->created_by = Auth::user()->id;
                $prescription->created_at = Carbon::now();
                // $prescription->save();

                // dd($request->prescription_no);

                DB::transaction(function () use ($request, $prescription) {

                    if ($prescription->save()) {

                        $count_service = count($request->service_id);

                        for ($i = 0; $i < $count_service; $i++) {

                            $prescription_detail = new PrescriptionDetails();
                            $prescription_detail->date = date('Y-m-d', strtotime($request->date));
                            $prescription_detail->prescription_id = $prescription->id;
                            $prescription_detail->service_id = $request->service_id[$i];
                            $prescription_detail->service_price = $request->service_price[$i];
                            $prescription_detail->service_selling_price = $request->service_selling_price[$i];
                            $prescription_detail->status = '0';
                            $prescription_detail->created_at = Carbon::now();
                            $prescription_detail->save();

                        }

                        if ($request->customer_id == '0') {

                            $customer = new Customer();
                            $customer->name = $request->name;
                            $customer->phonenumber = $request->phonenumber;
                            $customer->address = $request->address;
                            $customer->location_id = Auth::user()->location_id;
                            $customer->save();

                            $customer_id = $customer->id;

                        } else {

                            $customer_id = $request->customer_id;

                        }

                        $payment = new Payment();
                        $payment_details = new PaymentDetails();

                        $payment->prescription_id = $prescription->id;
                        $payment->customer_id = $customer_id;
                        $payment->paid_status = $request->paid_status;
                        $payment->payment_option = $request->payment_option;
                        $payment->discount_amount = $request->discount_amount;
                        $payment->total_amount = $request->estimated_amount;
                        $payment->created_at = Carbon::now();
                        $payment->created_by = Auth::user()->id;

                        if ($request->paid_status == 'full_paid') {

                            $payment->paid_amount = $request->estimated_amount;
                            $payment->due_amount = '0';
                            $payment_details->current_paid_amount = $request->estimated_amount;

                        } elseif ($request->paid_status == 'full_paid') {

                            $payment->paid_amount = '0';
                            $payment->due_amount = $request->estimated_amount;
                            $payment_details->current_paid_amount = '0';

                        } elseif ($request->paid_status == 'partial_paid') {

                            $payment->paid_amount = $request->paid_amount;
                            $payment->due_amount = $request->estimated_amount - $request->paid_amount;
                            $payment_details->current_paid_amount = $request->paid_amount;

                        }

                        $payment->save();

                        $payment_details->prescription_id = $prescription->id;
                        $payment_details->date = date('Y-m-d', strtotime($request->date));
                        $payment_details->save();

                    }

                });

            }

        }

        $notification = array(
            'message' => 'Invoice Created Successfully',
            'alert-type' => 'success',
        );

        return redirect()->route('prescription.all')->with($notification);

    }



}