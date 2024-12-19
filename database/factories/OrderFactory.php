<?php

namespace Database\Factories;

use App\Cart\Domain\Model\Product;
use App\Cart\Domain\Value\Sku;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $productCount = $this->faker->numberBetween(1, 5);
        $products = [];
        for ($i = 0; $i < $productCount; $i++) {
            $products[] = new Product(
                sku: new Sku($this->faker->uuid),
                name: $this->faker->word(),
                quantity: $this->faker->numberBetween(1, 10),
                price: $this->faker->randomFloat(2, 1.0, 10.0),
                discount: $this->faker->numberBetween(0, 100),
            );
        }

        $subtotal = 0;
        $total = 0;

        /** @var Product $product */
        foreach ($products as $product) {
            $subtotal += $product->getPrice() * $product->getQuantity();
            $total += ($product->getPrice() * $product->getQuantity()) * (1 - $product->getDiscount() / 100);
        }

        $discount = $subtotal - $total;

        return [
            'id' => $this->faker->uuid(),
            'customer_id' => Customer::factory()->create(),
            'items' => json_encode($products),
            'subtotal' => $subtotal,
            'total' => $total,
            'discount' => $discount,
        ];
    }
}
