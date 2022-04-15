<?php

namespace Database\Factories;

use App\Models\Bill;
use App\Models\Model;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Order;
use App\Models\User;

class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $userID = random_int(1, 10);
        $user = User::find($userID);
        $orderId = random_int(1, 48);
        $billAr = Bill::where('order_id', $orderId)->get();
        $amount = 0;
        foreach ($billAr as $bill) {
            $amount += $bill->product_price * $bill->quantity;
        }

        return [
            'name' => $user->name,
            'phone' => $user->phone,
            'address' => $user->address,
            'mail' => $user->email,
            'user_id' => $userID,
            'code' => substr(md5(microtime()),rand(0,26),6),
            'status' => 3,
            'ship_id' => random_int(3,12),
            'fee_ship' => 0,
            'amount' => $amount,
            'paymethod' => random_int(0,3),
            'created_at' => $this->faker->dateTimeBetween('2021-01-01', '2021-12-20'),
            'updated_at' => $this->faker->dateTimeBetween('2021-01-01', '2021-12-20'),
        ];
    }
}
