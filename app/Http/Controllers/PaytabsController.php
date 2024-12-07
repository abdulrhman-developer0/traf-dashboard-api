<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\PaytabsInvoice;
use App\Models\ServiceProvider;
use App\Models\serviceProviderPivot;
use App\Models\Subscription;
use Basel\Paytabs\Paytabs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaytabsController extends Controller
{

    // public function index()
    // {

    //     // $pt = app('paytabs'); //the instance through the register service provider singleton

    //     $result = Paytabs::getInstance()->create_pay_page(array(

    //         //Customer's Personal Information
    //         'cc_first_name' => "john",          //This will be prefilled as Credit Card First Name
    //         'cc_last_name' => "Doe",            //This will be prefilled as Credit Card Last Name
    //         'cc_phone_number' => "00973",
    //         'phone_number' => "33333333",
    //         'email' => "customer@gmail.com",

    //         //Customer's Billing Address (All fields are mandatory)
    //         //When the country is selected as USA or CANADA, the state field should contain a String of 2 characters containing the ISO state code otherwise the payments may be rejected.
    //         //For other countries, the state can be a string of up to 32 characters.
    //         'billing_address' => "manama bahrain",
    //         'city' => "manama",
    //         'state' => "manama",
    //         'postal_code' => "00973",
    //         'country' => "BHR",

    //         //Customer's Shipping Address (All fields are mandatory)
    //         'address_shipping' => "Juffair bahrain",
    //         'city_shipping' => "manama",
    //         'state_shipping' => "manama",
    //         'postal_code_shipping' => "00973",
    //         'country_shipping' => "BHR",

    //         //Product Information
    //         "products_per_title" => "Product1 || Product 2 || Product 4",   //Product title of the product. If multiple products then add “||” separator
    //         'quantity' => "1 || 1 || 1",                                    //Quantity of products. If multiple products then add “||” separator
    //         'unit_price' => "2 || 2 || 6",                                  //Unit price of the product. If multiple products then add “||” separator.
    //         "other_charges" => "91.00",                                     //Additional charges. e.g.: shipping charges, taxes, VAT, etc.
    //         'amount' => "101.00",                                          //Amount of the products and other charges, it should be equal to: amount = (sum of all products’ (unit_price * quantity)) + other_charges
    //         'discount' => "1",                                                //Discount of the transaction. The Total amount of the invoice will be= amount - discount

    //         //Invoice Information
    //         'title' => "John Doe",               // Customer's Name on the invoice
    //         "reference_no" => "1231231",        //Invoice reference number in your system

    //     ));


    //     // dd($result);

    //     if ($result->response_code == 4012) {
    //         return redirect($result->payment_url);
    //     }
    //     if ($result->response_code == 4094) {
    //         return $result->details;
    //     }

    //     return $result->result;
    // }

    // public function response(Request $request)
    // {

    //     $result = Paytabs::getInstance()->verify_payment($request->payment_reference);

    //     if ($result->response_code == 100) {
    //         //success
    //         $this->createInvoice((array)$result);
    //     }
    //     return $result->result;
    // }

    // public function createInvoice($request)
    // {
    //     $request['order_id'] = $request["reference_no"];
    //     PaytabsInvoice::create($request);
    // }
    // SERVER_KEY = 'SERVER_KEY',
    // BASE_URL = 'https://secure-egypt.paytabs.com/';
    public function initiatePayment(Request $request) {
        $request->validate([
            'package_id' => 'required|exists:packages,id',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'phone_country_code' => 'required|string',
            'phone_number' => 'required|string',
            'email' => 'required|email',
            'billing_address' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'postal_code' => 'required|string',
            'country' => 'required|string',
        ]);
        $package=Package::find($request->package_id);
        

       
        // $serviceProviderId = serviceProviderPivot::whereUserId(Auth::id())
        $serviceProvider = ServiceProvider::where('user_id', Auth::id())->first();
        if($serviceProvider){
            $serviceProviderId=$serviceProvider->id;
        }else {
            echo "Service provider not found.";
        }
      
        $subscription=Subscription::create([
            'service_provider_id'=>$serviceProviderId ,
            'package_id' => $package->id,
            'status' => 'pending',
            'payment_status' => 'pending'
        ]);
        $result = Paytabs::getInstance()->create_pay_page([
            // Customer Information
            'cc_first_name' => $request->first_name,
            'cc_last_name' => $request->last_name,
            'cc_phone_number' => $request->phone_country_code,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
    
            // Billing Address
            'billing_address' => $request->billing_address,
            'city' => $request->city,
            'state' => $request->state,
            'postal_code' => $request->postal_code,
            'country' => $request->country,
    
            // Shipping Address (same as billing here for simplicity)
            'address_shipping' => $request->billing_address,
            'city_shipping' => $request->city,
            'state_shipping' => $request->state,
            'postal_code_shipping' => $request->postal_code,
            'country_shipping' => $request->country,
    
            // Product Information
            'products_per_title' => $package->name,
            'quantity' => '1',
            'unit_price' => $package->price,
            'amount' => $package->price,
            'discount' => '0',
    
            // Invoice Information
            'title' => "Subscription Payment",
            'reference_no' => $subscription->id,
        ]);

        dd($result);

        if ($result->response_code == 4012) {
            return response()->json([
                'success' => true,
                'payment_url' => $result->payment_url,
                'subscription_id' => $subscription->id,
            ], 200);
        }
    
        return response()->json([
            'success' => false,
            'message' => $result->result,
        ], 400);
    
    }
    public function verifyPayment(Request $request)
    {
        $request->validate([
            'payment_reference' => 'required|string',
        ]);
    
        $result = Paytabs::getInstance()->verify_payment($request->payment_reference);
    
        if ($result->response_code == 100) {
            $subscription = Subscription::find($result->reference_no);
    
            if ($subscription) {
                $subscription->update([
                    'payment_status' => 'paid',
                    'status' => 'active',
                    'payment_id' => $result->transaction_id,
                    'transaction_reference' => $result->reference_no,
                    'start_date' => now(),
                    'end_date' => now()->addDays($subscription->package->duration_in_days),
                ]);
            }
    
            return response()->json([
                'success' => true,
                'message' => 'Payment successful!',
                'subscription' => $subscription,
            ], 200);
        }
    
        return response()->json([
            'success' => false,
            'message' => $result->result,
        ], 400);
    }
    
}
