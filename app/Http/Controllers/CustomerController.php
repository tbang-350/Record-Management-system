<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\PaymentDetail;
use App\Models\Prescription;
use App\Models\ServicePayment;
use App\Models\ServicePaymentDetail;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class CustomerController extends Controller
{
    public function CustomerAll()
    {

        $current_location = Auth::user()->location_id;

        if ($current_location == 1) {

            $customer = Customer::latest()->get();

        } else {

            $customer = Customer::latest()->where('location_id', $current_location)->get();

        }

        return view('backend.customer.customer_all', compact('customer'));

    }

    public function CustomerAdd()
    {

        return view('backend.customer.customer_add');

    }

    public function CustomerStore(Request $request)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'age' => 'required|integer|min:0',
            'sex' => 'required|string|in:male,female,other',
            'address' => 'required|string|max:255',
            'phonenumber' => 'required|string|max:15',
        ]);

        // Insert the validated data into the database
        Customer::insert([
            'name' => $request->name,
            'age' => $request->age,
            'sex' => $request->sex,
            'address' => $request->address,
            'phonenumber' => $request->phonenumber,
            'location_id' => Auth::user()->location_id,
            'created_by' => Auth::user()->id,
            'created_at' => Carbon::now(),
        ]);

        $notification = array(
            'message' => 'Customer Added Successfully',
            'alert-type' => 'success',
        );

        return redirect()->route('customer.all')->with($notification);
    }

    public function CustomerEdit($id)
    {

        $customer = Customer::findOrFail($id);

        return view('backend.customer.customer_edit', compact('customer'));

    }

    public function CustomerUpdate(Request $request)
    {

        $customer_id = $request->id;

        Customer::findOrFail($customer_id)->update([
            'name' => $request->name,
            'age' => $request->age,
            'sex' => $request->sex,
            'address' => $request->address,
            'phonenumber' => $request->phonenumber,
            'updated_by' => Auth::user()->id,
            'updated_at' => Carbon::now(),
        ]);

        $notification = array(
            'message' => 'Customer Updated Successfully',
            'alert-type' => 'success',
        );

        return redirect()->route('customer.all')->with($notification);

    }

    public function CustomerDelete($id)
    {

        Customer::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Customer Deleted Successfully',
            'alert-type' => 'success',
        );

        return redirect()->back()->with($notification);

    } // End Method

    public function CreditCustomer()
    {

        $current_location = Auth::user()->location_id;

        if ($current_location == 1) {

            $allData = Payment::whereIn('paid_status', ['partial_paid'])->get();

        } else {

            $allData = Payment::whereIn('paid_status', ['partial_paid'])->where('location_id', $current_location)->get();

        }

        return view('backend.customer.customer_credit', compact('allData'));

    } // End Method

    public function CreditServiceCustomer()
    {

        $current_location = Auth::user()->location_id;

        if ($current_location == 1) {

            $allServiceData = ServicePayment::whereIn('paid_status', ['partial_paid'])->get();

        } else {

            $allServiceData = ServicePayment::whereIn('paid_status', ['partial_paid'])->where('location_id', $current_location)->get();

        }

        return view('backend.customer.customer_service_credit', compact('allServiceData'));

    } // End Method

    public function CreditCustomerPrintPdf()
    {

        $current_location = Auth::user()->location_id;

        if ($current_location == 1) {

            $allData = Payment::whereIn('paid_status', ['full_due', 'partial_paid'])->get();

        } else {

            $allData = Payment::whereIn('paid_status', ['full_due', 'partial_paid'])->where('location_id', $current_location)->get();

        }

        return view('backend.pdf.customer_credit_pdf', compact('allData'));

    } // End Method

    public function ServiceCreditCustomerPrintPdf()
    {

        $current_location = Auth::user()->location_id;

        if ($current_location == 1) {

            $allData = ServicePayment::whereIn('paid_status', ['full_due', 'partial_paid'])->get();

        } else {

            $allData = ServicePayment::whereIn('paid_status', ['full_due', 'partial_paid'])->where('location_id', $current_location)->get();

        }

        return view('backend.pdf.service_customer_credit_pdf', compact('allData'));

    } // End Method

    public function CustomerEditInvoice($invoice_id)
    {

        $payment = Payment::where('invoice_id', $invoice_id)->first();

        return view('backend.customer.edit_customer_invoice', compact('payment'));

    } // End Method

    public function CustomerUpdateInvoice(Request $request, $invoice_id)
    {

        if ($request->paid_amount > $request->new_paid_amount) {
            $notification = array(
                'message' => 'Sorry , you paid maximum value',
                'alert-type' => 'error',
            );

            return redirect()->back()->with($notification);
        } else {

            $payment = Payment::where('invoice_id', $invoice_id)->first();

            $payment_details = new PaymentDetail();

            $payment->paid_status = $request->paid_status;

            if ($request->paid_status == 'full_paid') {

                $payment->paid_amount = Payment::where('invoice_id', $invoice_id)->first()['paid_amount'] + $request->new_paid_amount;

                $payment->due_amount = '0';

                // $payment->paid_status = 'full_paid';

                $payment_details->current_paid_amount = $request->new_paid_amount;

            } elseif ($request->paid_status == 'partial_paid') {

                $payment->paid_amount = Payment::where('invoice_id', $invoice_id)->first()['paid_amount'] + $request->paid_amount;

                $payment->due_amount = Payment::where('invoice_id', $invoice_id)->first()['due_amount'] - $request->paid_amount;

                $payment_details->current_paid_amount = $request->paid_amount;

            }

            $payment->save();

            $payment_details->invoice_id = $invoice_id;
            $payment_details->date = date('Y-m-d', strtotime($request->date));
            $payment_details->updated_by = Auth::user()->id;
            $payment_details->save();

            $notification = array(
                'message' => 'Invoice Updated Succesfully',
                'alert-type' => 'success',
            );

            return redirect()->route('credit.customer')->with($notification);

        }

    } // End Method

    public function CustomerEditServiceInvoice($service_invoice_id)
    {

        $service_payment = ServicePayment::where('service_invoice_id', $service_invoice_id)->first();

        return view('backend.customer.edit_customer_service_invoice', compact('service_payment'));

    } // End Method

    public function CustomerUpdateServiceInvoice(Request $request, $service_invoice_id)
    {

        if ($request->paid_amount > $request->new_paid_amount) {
            $notification = array(
                'message' => 'Sorry , you paid maximum value',
                'alert-type' => 'error',
            );

            return redirect()->back()->with($notification);
        } else {

            $payment = ServicePayment::where('service_invoice_id', $service_invoice_id)->first();

            $payment_details = new ServicePaymentDetail();

            $payment->paid_status = $request->paid_status;

            if ($request->paid_status == 'full_paid') {

                $payment->paid_amount = ServicePayment::where('service_invoice_id', $service_invoice_id)->first()['paid_amount'] + $request->new_paid_amount;

                $payment->due_amount = '0';

                // $payment->paid_status = 'full_paid';

                $payment_details->current_paid_amount = $request->new_paid_amount;

            } elseif ($request->paid_status == 'partial_paid') {

                $payment->paid_amount = ServicePayment::where('service_invoice_id', $service_invoice_id)->first()['paid_amount'] + $request->paid_amount;

                $payment->due_amount = ServicePayment::where('service_invoice_id', $service_invoice_id)->first()['due_amount'] - $request->paid_amount;

                $payment_details->current_paid_amount = $request->paid_amount;

            }

            $payment->save();

            $payment_details->service_invoice_id = $service_invoice_id;
            $payment_details->date = date('Y-m-d', strtotime($request->date));
            $payment_details->payment_option = $request->payment_option;
            $payment_details->location_id = Auth::user()->location_id;
            $payment_details->updated_by = Auth::user()->id;
            $payment_details->save();

            $notification = array(
                'message' => 'Invoice Updated Succesfully',
                'alert-type' => 'success',
            );

            return redirect()->route('credit.service.customer')->with($notification);

        }

    } // End Method

    public function CustomerInvoiceDetails($invoice_id)
    {

        $payment = Payment::where('invoice_id', $invoice_id)->first();

        return view('backend.pdf.invoice_details_pdf', compact('payment'));

    } // End Method

    public function CustomerServiceInvoiceDetails($service_invoice_id)
    {

        $service_payment = ServicePayment::where('service_invoice_id', $service_invoice_id)->first();

        return view('backend.pdf.service_invoice_details_pdf', compact('service_payment'));

    } // End Method

    public function PaidCustomer()
    {

        $current_location = Auth::user()->location_id;

        if ($current_location == 1) {

            $allData = Payment::whereIn('paid_status', ['partial_paid', 'full_paid'])->get();

        } else {

            $allData = Payment::whereIn('paid_status', ['partial_paid', 'full_paid'])->where('location_id', $current_location)->get();

        }

        return view('backend.customer.customer_paid', compact('allData'));

    } //End Method

    public function PaidCustomerPrintPdf()
    {

        $current_location = Auth::user()->location_id;

        if ($current_location == 1) {

            $allData = Payment::whereIn('paid_status', ['partial_paid', 'full_paid'])->get();

        } else {

            $allData = Payment::whereIn('paid_status', ['partial_paid', 'full_paid'])->where('location_id', $current_location)->get();
        }

        return view('backend.pdf.customer_paid_pdf', compact('allData'));

    } // End Method

    public function CustomerWiseReport()
    {
        $current_location = Auth::user()->location_id;

        if ($current_location == 1) {

            $customers = Customer::all();

        } else {

            $customers = Customer::where('location_id', $current_location)->get();

        }

        return view('backend.customer.customer_wise_report', compact('customers'));

    } // End Method

    public function CustomerWiseCreditReport(Request $request)
    {

        $current_location = Auth::user()->location_id;

        if ($current_location == 1) {

            $allData = Payment::where('customer_id', $request->customer_id)->whereIn('paid_status', ['partial_paid'])->get();

        } else {

            $allData = Payment::where('customer_id', $request->customer_id)->whereIn('paid_status', ['partial_paid'])->where('location_id', $current_location)->get();

        }

        return view('backend.pdf.customer_wise_credit_pdf', compact('allData'));

    } // End Method

    public function CustomerWisePaidReport(Request $request)
    {

        $current_location = Auth::user()->location_id;

        if ($current_location == 1) {

            $allData = Payment::where('customer_id', $request->customer_id)->whereIn('paid_status', ['partial_paid', 'full_paid'])->get();

        } else {

            $allData = Payment::where('customer_id', $request->customer_id)->whereIn('paid_status', ['partial_paid', 'full_paid'])->where('location_id', $current_location)->get();

        }

        $allData = Payment::where('customer_id', $request->customer_id)->whereIn('paid_status', ['partial_paid', 'full_paid'])->get();

        return view('backend.pdf.customer_wise_paid_pdf', compact('allData'));

    } // End Method

    public function CustomerPrescriptionHistory($id)
    {

        $customer_id = $id;

//        dd($customer_id);

        $prescription = Prescription::latest()->where('customer_id', $customer_id)->get();

        if ($prescription->isEmpty()) {
            // Prescription not found, display toast notification
            $notification = array(
                'message' => 'Sorry, customer has no such history',
                'alert-type' => 'error',
            );

            // Redirect back to the previous page
            return redirect()->back()->with($notification);
        }

        // Get the customer associated with the first prescription
        $customer = $prescription->first()->customer;

//        dd($customer);

        return view('backend.customer.customer_prescription_history', compact(['prescription', 'customer']));

    }

    public function CustomerPurchaseHistory($id)
    {

        $customer_id = $id;

        // Fetch invoice IDs for the customer from payments
        $invoice_ids = Payment::where('customer_id', $customer_id)->pluck('invoice_id');

        // Retrieve purchase history based on the invoice IDs
        $purchase_history = Invoice::whereIn('id', $invoice_ids)->get();

        $allData = Invoice::whereIn('id', $invoice_ids)->where('status', '1')->orderBy('date', 'desc')->orderBy('id', 'desc')->get();

        if ($allData->isEmpty()) {
            $notification = array(
                'message' => 'Sorry, customer has no such history',
                'alert-type' => 'error',
            );

            // Redirect back to the previous page
            return redirect()->back()->with($notification);
        }

        // Get the customer associated with the first prescription
        $customer = $allData->first()->payment->customer;

        // Return the view with the invoice data
        return view('backend.customer.customer_purchase_history', compact(['allData', 'customer']));

    }

}
