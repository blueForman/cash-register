<?php

namespace Database\Factories;

use App\Cart\Domain\Model\Item;
use App\Cart\Domain\Model\Product;
use App\Cart\Domain\Value\Sku;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cart>
 */
class CartFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $productCount = $this->faker->numberBetween(1, 5);

        $items = [];
        for ($i = 0; $i < $productCount; $i++) {
            $product = new Product(
                sku: new Sku($this->faker->uuid),
                name: $this->faker->word(),
                price: $this->faker->randomFloat(2, 1.0, 10.0),
                discount: $this->faker->numberBetween(0, 100),
            );

            $items[] = new Item(
                product: $product,
                quantity: $this->faker->numberBetween(1, 10),
            );
        }

        $subtotal = 0;
        $total = 0;

        /** @var Item $item */
        foreach ($items as $item) {
            $subtotal += $item->getProduct()->getPrice() * $item->getQuantity();
            $total += ($item->getProduct()->getPrice() * $item->getQuantity()) * (1 - $item->getProduct()->getDiscount() / 100);
        }

        $discount = $subtotal - $total;

        return [
            'id' => $this->faker->uuid(),
            'customer_id' => Customer::factory()->create(),
            'items' => json_encode($items),
            'subtotal' => $subtotal,
            'total' => $total,
            'discount' => $discount,
        ];
    }
}
