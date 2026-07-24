<?php



namespace App\Http\Controllers;

use App\Models\Admin;

use App\Models\House;

use App\Models\Education;

use App\Models\Payment;

use App\Models\Ward;

use App\Models\Excelcollection;

use App\library\CCAvenueCrypto;

use App\library\TransactionResponse;

use App\library\AtomAES;

use App\library\SMSConfig;

use Illuminate\Http\Request;

use Session;

use Redirect;

use Validator;

use Str;

use App\imports\HouseImport;

use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Support\Facades\DB;





class PaymentController extends Controller

{

    // public function initiate(Request $request)

    // {

    //     //echo "<pre>"; print_r($request->all()); exit;

    //     $orderId = 'ORD-' . Str::uuid();

    //     $billing_name = trim($request->firm_name);

    //     $billing_name = preg_replace('/[^a-zA-Z\s]/', '', $billing_name);

    //     $billing_name = preg_replace('/\s+/', ' ', $billing_name);



    //     if (empty($billing_name)) {

    //         $billing_name = 'Customer';

    //     }

    //     $amount = $request->amount;

    //     $address = $request->address ??'N/A';

    //     $mobile = $request->mobile ?? '7460939421';

    //     $email = $request->email ?? 'shaniy405@gmail.com';

    //     $firm_name = $request->firm_name ??'N/A';



    //     $merchantData = [

    //         "merchant_id"     => env('CCAVENUE_MERCHANT_ID'),

    //         "order_id"        => $orderId,

    //         "currency"        => "INR",

    //         "amount"          => $amount,

    //         "redirect_url"    => route('ccavenue.callback'),

    //         "cancel_url"      => route('ccavenue.cancel'),

    //         "integration_type"=> "redirect",

    //         "language"        => "EN",

    //         "billing_name"    => $billing_name,

    //         "billing_address" => $address,

    //         "billing_city"    => 'Lucknow',

    //         "billing_state"   => 'UP',

    //         "billing_zip"     => '226010',

    //         "billing_country" => 'IN',

    //         "billing_tel"     => $mobile,

    //         "billing_email"   => $email,

    //         "delivery_name"   => $firm_name,

    //         "delivery_address"=> $address,

    //         "delivery_city"   => 'Lucknow',

    //         "delivery_state"  => 'UP',

    //         "delivery_zip"    => '226010',

    //         "delivery_country"=> 'IN',

    //         "delivery_tel"    => $mobile,

    //         "merchant_param1" => $request->contr_id,

    //     ];



    //     //echo "<pre>"; print_r($merchantData); exit;

    //     $merchantQuery = http_build_query($merchantData);

    //     $encryptedData = CCAvenueCrypto::encrypt($merchantQuery, env('CCAVENUE_WORKING_KEY'));

        

    //     //dd($encryptedData);

        

    //     $data = [

        

    //         "order_id"        => $orderId,

    //         "currency"        => "INR",

    //         "amount"          => $amount,

    //         "billing_name"    => $billing_name,

    //         "billing_address" => $address,

    //         "billing_city"    => 'Lucknow',

    //         "billing_state"   => 'UP',

    //         "billing_zip"     => '226010',

    //         "billing_country" => 'IN',

    //         "billing_tel"     => $mobile,

    //         "billing_email"   => $email,

    //         "status"   => 'Pending',

    //         "contractor_id" => $request->contr_id,

            

            

    //     ];

        

    //     DB::table('paymenrequest')->insert($data);

    //     //  dd($data);

    //     return view('ccavenue.redirect', [

    //         'encrypted_data' => $encryptedData,

    //         'access_code'    => env('CCAVENUE_ACCESS_CODE')

    //     ]);

    // }





    public function initiate(Request $request)

    {

        $orderId = 'ORD-' . Str::uuid();

        



        $billing_name = trim($request->firm_name);

        $billing_name = preg_replace('/[^a-zA-Z\s]/', '', $billing_name);

        $billing_name = preg_replace('/\s+/', ' ', $billing_name);



        if (empty($billing_name)) {

            $billing_name = 'Customer';

        }



        $amount   = $request->amount;

        

        $address  = $request->address ?? 'N/A';

        $mobile   = $request->mobile ?? '7460939421';

        $email    = $request->email ?? 'shaniy405@gmail.com';

        $firmName = $request->firm_name ?? 'N/A';



        $merchantData = [

            "merchant_id"     => env('CCAVENUE_MERCHANT_ID'),

            "order_id"        => $orderId,

            "currency"        => "INR",

            "amount"          => $amount,

            "redirect_url" => url('/ccavenue/callback'),

            "cancel_url"   => url('/ccavenue/cancel'),

            "language"        => "EN",



            "billing_name"    => $billing_name,

            "billing_address" => $address,

            "billing_city"    => 'Lucknow',

            "billing_state"   => 'UP',

            "billing_zip"     => '226010',

            "billing_country" => 'IN',

            "billing_tel"     => $mobile,

            "billing_email"   => $email,



            "delivery_name"   => $firmName,

            "delivery_address"=> $address,

            "delivery_city"   => 'Lucknow',

            "delivery_state"  => 'UP',

            "delivery_zip"    => '226010',

            "delivery_country"=> 'IN',

            "delivery_tel"    => $mobile,



            "merchant_param1" => $request->nomination_id,

        ];



        



        // IMPORTANT: build query manually (do NOT use http_build_query)

        $merchantQuery = '';

        foreach ($merchantData as $key => $value) {

            $merchantQuery .= $key . '=' . $value . '&';

        }



        $encryptedData = CCAvenueCrypto::encrypt(

            $merchantQuery,

            env('CCAVENUE_WORKING_KEY')

        );



        DB::table('paymenrequest')->insert([

            "order_id"        => $orderId,

            "currency"        => "INR",

            "amount"          => $amount,

            "billing_name"    => $billing_name,

            "billing_address" => $address,

            "billing_city"    => 'Lucknow',

            "billing_state"   => 'UP',

            "billing_zip"     => '226010',

            "billing_country" => 'IN',

            "billing_tel"     => $mobile,

            "billing_email"   => $email,

            "status"          => 'Pending',

            "contractor_id"   => $request->contr_id,

        ]);



        return view('ccavenue.redirect', [

            'encrypted_data' => $encryptedData,

            'access_code'    => env('CCAVENUE_ACCESS_CODE')

        ]);

    }







    // CCAvenue requires AES-128-CBC encryption with static IV

    private function encrypt($plainText, $key)

    {

        $iv = "@@@@&&&&####$$$$";

        return base64_encode(openssl_encrypt($plainText, "AES-128-CBC", $key, 0, $iv));

    }



    private function decrypt($cipherText, $key)

    {

        $iv = "@@@@&&&&####$$$$";

        return openssl_decrypt(base64_decode($cipherText), "AES-128-CBC", $key, 0, $iv);

    }

  

    public function callback(Request $request)

    {


        \Log::info('CCAvenue path = '.$request->path());

        

        $encResp = $request->input('encResp');



        if (!$encResp) {

            \Log::error('CCAvenue: encResp missing');

            abort(400);

        }



        $decryptedString = CCAvenueCrypto::decrypt(

            $encResp,

            env('CCAVENUE_WORKING_KEY')

        );



        parse_str($decryptedString, $response);



        if (empty($response['order_id']) || empty($response['order_status'])) {

            \Log::error('CCAvenue: Invalid response', $response);

            abort(400);

        }



        $orderId      = $response['order_id'];

        $status       = $response['order_status'];

        $nomintion_id = $response['merchant_param1'] ?? null;

        \Log::info('Nomination Id = '. $nomintion_id);

        

        $amount       = $response['amount'] ?? '0';

        



        DB::beginTransaction();



        try {

        

            DB::table('ccavenue_transactions')->updateOrInsert(

                ['order_id' => $orderId],

                [

                    'tracking_id'     => $response['tracking_id'] ?? null,

                    'bank_ref_no'     => $response['bank_ref_no'] ?? null,

                    'order_status'    => $response['order_status'] ?? null,

                    'failure_message' => $response['failure_message'] ?? null,

                    'payment_mode'    => $response['payment_mode'] ?? null,

                    'card_name'       => $response['card_name'] ?? null,

                    'status_code'     => $response['status_code'] ?? null,

                    'status_message'  => $response['status_message'] ?? null,

                    'amount'          => $response['amount'] ?? null,

                    'currency'        => $response['currency'] ?? null,

                    'billing_name'    => $response['billing_name'] ?? null,

                    'billing_email'   => $response['billing_email'] ?? null,

                    'billing_tel'     => $response['billing_tel'] ?? null,

                    'merchant_param1' => $response['merchant_param1'] ?? null,

                    'updated_at'      => now(),

                ]

            );





            $year = date('Y');

            $month = date('m');

            $financialYear = ($month >= 4)

                ? $year . '-' . ($year + 1)

                : ($year - 1) . '-' . $year;



            if ($status === 'Success') {



                $updated = DB::table('paymenrequest')

                    ->where('order_id', $orderId)

                    ->where('status', '!=', 'Paid')

                    ->update([

                        'status' => 'Paid',

                        'financial_year' => $financialYear

                    ]);



                if ($updated === 0) {

                    \Log::warning("CCAvenue: No paymentRequest row updated", [$orderId]);

                }



                DB::table('nominations')

                    ->where('id', $nomintion_id)

                    ->update(['status' => 'Paid']);

               $nomations=     DB::table('nomination_participants')->where('status', 'confirm')->where('nomination_id', $nomintion_id)->update(['status' => 'Paid']);


                $finalStatus = 'Success';



            } elseif ($status === 'Aborted') {



                DB::table('paymenrequest')

                    ->where('order_id', $orderId)

                    ->update(['status' => 'Aborted']);



                $finalStatus = 'Aborted';



            } elseif ($status === 'Failure') {



                DB::table('paymenrequest')

                    ->where('order_id', $orderId)

                    ->update(['status' => 'Failed']);



                DB::table('nominations')

                    ->where('id', $nomintion_id)

                    ->update(['status' => 'Failed']);



                $finalStatus = 'Failure';



            } else {

                $finalStatus = 'Pending';

            }



            DB::commit();



        } catch (\Throwable $e) {

            DB::rollBack();

            \Log::error('CCAvenue Callback Failed', [

                'order_id' => $orderId,

                'error' => $e->getMessage()

            ]);

            abort(500);

        }

        

        $contr = DB::table('nominations')->where('id', $nomintion_id)->first();

        if ($contr && !empty($contr->mobile)) {

            try {

                (new SMSConfig)->sendPaymentStatusSMS(

                    $contr->mobile,

                    $contr->unique_id ?? $contr->id,

                    $finalStatus,

                    $orderId,

                    $amount

                );

            } catch (\Exception $e) {

                \Log::error('SMS Error', [$e->getMessage()]);

            }

        }



        return view("ccavenue." . strtolower($finalStatus), compact('response'));

    }



  

    public function callback_oldd(Request $request)

    {

        $encResp = $request->input('encResp');



        if (!$encResp) {

            return response()->json(['message' => 'Encrypted response missing'], 400);

        }



        $decryptedString = CCAvenueCrypto::decrypt($encResp, env('CCAVENUE_WORKING_KEY'));

        parse_str($decryptedString, $response);

        // dd($response);

        DB::table('ccavenue_transactions')->updateOrInsert(

        ['order_id' => $response['order_id']],

        $response

        );



        if (!isset($response['order_status'])) {

            return response()->json(['message' => 'Invalid response format'], 400);

        }



        $status = $response['order_status'];

        $bankRefNo     = $response['bank_ref_no'] ?? null;

        $nomintion_id = $response['merchant_param1'];

        $orderId = $response['order_id'];

        $amount = $response['amount'] ?? '0';

        

        

            

        $currentMonth = date('m');

        $currentYear = date('Y');



        if ($currentMonth >= 4) {

            $financialYear = $currentYear . '-' . ($currentYear + 1);

        } else {

            $financialYear = ($currentYear - 1) . '-' . $currentYear;

        }



        if ($status === 'Success' && !empty($bankRefNo)) {



        DB::table('paymenrequest')

            ->where('order_id', $orderId)

            ->update([

                'status' => 'Paid',

                'financial_year' => $financialYear

            ]);



        DB::table('contractors')

            ->where('id', $nomintion_id)

            ->update(['paymentStatus' => 'Paid']);



        $finalStatusForSMS = 'Success';



        } elseif ($status === 'Aborted') {

        

            DB::table('paymenrequest')

                ->where('order_id', $orderId)

                ->update([

                    'status' => 'Aborted',

                    'financial_year' => $financialYear

                ]);

        

            $finalStatusForSMS = 'Aborted';

        

        } elseif ($status === 'Failure') {

        

            DB::table('paymenrequest')

                ->where('order_id', $orderId)

                ->update(['status' => 'Failed']);

        

            DB::table('contractors')

                ->where('id', $nomintion_id)

                ->update(['paymentStatus' => 'Failed']);

        

            $finalStatusForSMS = 'Failure';

        

        } else {

        

            DB::table('paymenrequest')

                ->where('order_id', $orderId)

                ->update(['status' => 'Pending']);

        

            $finalStatusForSMS = 'Pending';

        }





    $contr = DB::table('contractors')->where('id', $nomintion_id)->first();



        if ($contr && !empty($contr->mobile)) {

            try {

                $smsService = new SMSConfig;

                $smsService->sendPaymentStatusSMS(

                    $contr->mobile,

                    $contr->unique_id ?? $contr->id,

                    $finalStatusForSMS,

                    $orderId,

                    $amount

                );

            } catch (\Exception $e) {

                \Log::error("SMS Error: " . $e->getMessage());

            }

        }



        if ($finalStatusForSMS === 'Success') {

        return view('ccavenue.success', compact('response'));

        }

        

        if ($finalStatusForSMS === 'Pending') {

            return view('ccavenue.pending', compact('response'));

        }

        

        if ($finalStatusForSMS === 'Aborted') {

            return view('ccavenue.aborted', compact('response'));

        }

        

        return view('ccavenue.failure', compact('response'));



    }





public function cancel(Request $request)

{

    return view('ccavenue.cancel');

}

public function ManageDivisions(Request $request)

{

    

       $query = DB::table('paymenrequest as p')

        ->join('contractors as c', 'p.contractor_id', '=', 'c.id')

        ->select('p.id', 'p.order_id', 'c.firm_name', 'p.amount', 'p.billing_name', 'p.created_at', 'p.status');



    // Apply filters

    if ($request->search) {

        $query->where(function($q) use ($request) {

            $q->where('p.order_id', 'like', "%{$request->search}%")

              ->orWhere('c.firm_name', 'like', "%{$request->search}%");

        });

    }



    if ($request->status) {

        $query->where('p.status', $request->status);

    }



    return DataTables::of($query)

        ->addIndexColumn()

        ->make(true);

}





    

}

