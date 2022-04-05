<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Coupon;
use App\Models\Delivery;
use App\Models\Product;
use App\Models\Order;
use App\Models\Bill;
use App\Models\User;
use App\Models\Sell;
use Mail;
use Role;
use Hash;
use Log;
use DB;

class CheckoutController extends Controller
{
    public function coupon(Request $request)
    {
        if(trim($request->coupon_code) !== '') {
            $coupon = Coupon::where('code', $request->coupon_code)->first();
            if (isset($coupon)) {
                if(strtotime($coupon->time_out_of) >= time()) {
                    $couponAuth = Coupon::where('code', $request->coupon_code)->where('used','LIKE', '%'.auth()->id().'%')->first();
                    if(isset($couponAuth)) {
                        return response()->json([
                            'message' => 'Coupon code is used!'
                        ], 422);
                    } else {
                        session()->put('coupon', $coupon);
                        return response()->json([
                            'coupon' => session()->get('coupon'),
                            'code' => 200
                        ], 200);
                    }
                } else {
                    return response()->json([
                        'message' => 'Coupon code is expired!'
                    ], 422);
                }
            } else {
                return response()->json([
                    'message' => 'Coupon code not exist!'
                ], 422);
            }
        } else {
            return response()->json([
                'message' => 'Enter your coupon code, please!'
            ], 422);
        }
    }

    public function calc_fee_ship(Request $request)
    {
        $validator = $request->validate([
            'province_id' => 'required',
            'district_id' => 'required',
            'ward_id'=> 'required',
            'details_address' => 'required'
        ]);

        try {
            DB::beginTransaction();
            $feeDelivery = Delivery::where('province_id', $request->province_id)->where('district_id', $request->district_id)->where('ward_id', $request->ward_id)->first();
            if(isset($wardFee)) {
                $fee = $feeDelivery->feeship;
            } else {
                $fee = 50;
            }

            session()->put('fee_ship', [
                'fee' => $fee,
                'province_id' => $request->province_id,
                'district_id' => $request->district_id,
                'ward_id' => $request->ward_id,
                'address' => $request->address
            ]);

            DB::commit();
            return response()->json([
                'feeship' => session()->get('fee_ship'),
                'coupon' => session()->get('coupon'),
                'code' => 200,
                'message' => 'success'
            ], 200);
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('Message: '.$exception->getMessage().' line: '.$exception->getLine());
            return response()->json([
                'message' => 'There are incorrect values in the form !',
                'errors' => $validator->getMessageBag()->toArray()
            ], 422);
        }
    }

    public function cash_on_delivery(Request $request)
    {
        $fee_ship = session()->get('fee_ship');
        if($fee_ship === null) {
            return response()->json([
                'message' => 'There are incorrect values in the form !',
                'errors' => [
                    'details_address' => 'Enter your address, please !'
                ]
            ], 422);
        } else {
            if($request->create_account == 1) {
                $validator = $request->validate([
                    'customer_name' => 'required',
                    'customer_mail' => 'bail|required|email|unique:users,email',
                    'customer_phone' => 'bail|required|regex:/^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/im',
                    'agreeTerm' => 'required',
                    'customer_password' => 'bail|required|min:6'
                ]);
            } else {
                $validator = $request->validate([
                    'customer_name' => 'required',
                    'customer_mail' => 'bail|required|email',
                    'customer_phone' => 'bail|required|regex:/^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/im'
                ]);
            }
            try {
                DB::beginTransaction();
                if($request->create_account == 1) {
                    $user = User::create([
                        'name' => $request->customer_name,
                        'email' => $request->customer_mail,
                        'password' => Hash::make($request->customer_password),
                        'phone' => $request->customer_phone,
                        'address' => $fee_ship['address']
                    ]);
                    $role = Role::where('name', 'Visitor')->first();
                    $user->roles()->sync($role->id);
                    $id = $user->id;
                } else {
                    $id = $request->user_id;
                }

                if(session()->get('coupon') !== null) {
                    Coupon::find(session()->get('coupon')->id                                                                                                                                                                                                                                                                                                                                                                                                                   )->update([
                        'quantity' => session()->get('coupon')->quantity - 1,
                        'used' => session()->get('coupon')->used !== null ? get('coupon')->used.','.$id : $id
                    ]);
                }

                $order = Order::create([
                    'code' => substr(md5(microtime()),rand(0,26),6),
                    'name' => $request->customer_name,
                    'phone' => $request->customer_phone,
                    'mail' => $request->customer_mail,
                    'address' => $fee_ship['address'],
                    'coupon_id' => session()->get('coupon') !== null ? session()->get('coupon')->id : null,
                    'user_id' => $id,
                    'fee_ship' => $fee_ship['fee'],
                    'amount' => $request->amount,
                    'paymethod' => $request->paymethod
                ]);

                $title = 'The order has been confirmed at '.date('d/m/Y');
                $cus_mail = $request->customer_mail;

                foreach (session()->get('cart') as $key => $cart) {
                    $bill[] = Bill::create([
                        'order_id' => $order->id,
                        'product_id' => $key,
                        'product_name' => $cart['name'],
                        'product_price' => $cart['price'],
                        'quantity' => $cart['quantity']
                    ]);

                    Product::find($key)->update([
                        'sell' => Product::find($key)->sell + $cart['quantity']
                    ]);

                    Sell::create([
                        'product_id' => $key,
                        'quantity' => $cart['quantity'],
                        'amount' => $cart['price'] * $cart['quantity']
                    ]);
                }

//                return response()->json($order, 200);
                Mail::send('asbab.mail_order', ['order' => $order], function ($message) use ($title, $cus_mail) {
                    $message->from('cuong.pq.haui@gmail.com', 'Asbab Furniture Shop');
                    $message->to($cus_mail, $title);
                });

                session()->forget('cart');
                session()->forget('coupon');
                session()->forget('fee_ship');

                DB::commit();
                return response()->json($bill, 200);
            } catch (\Exception $exception) {
                DB::rollBack();
                Log::error('Message: '.$exception->getMessage().' line: '.$exception->getLine());
                return response()->json([
                    'message' => 'There are incorrect values in the form !',
                    'errors' => $validator->getMessageBag()->toArray()
                ], 422);
            }
        }
    }

    public function confirm_order()
    {

    }
}
