<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class BillFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $pID = random_int(1, 9);
        $pName = Product::find($pID)->name;
        $pPrice = Product::find($pID)->price;
        return [
            'order_id' => random_int(1, 48),
            'product_id' => random_int(1, 9),
            'product_name' => $pName,
            'product_price' => $pPrice,
            'quantity' => random_int(1,5),
            'created_at' => $this->faker->dateTimeBetween('2022-01-01', '2022-04-11'),
            'updated_at' => $this->faker->dateTimeBetween('2022-01-01', '2022-04-11'),
        ];
    }
}
