<?php
declare(strict_types=1);
namespace App\Cart\Domain\Model;

use App\Cart\Domain\Value\Sku;

final class Product implements \JsonSerializable
{
    public function __construct(
        private readonly Sku $sku,
        private readonly string $name,
        private int $quantity,
        private readonly float $price,
        private readonly int $discount
    ) {
    }

    public function getSku(): Sku
    {
        return $this->sku;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setQuantity(int $quantity): Product
    {
        $this->quantity = $quantity;
        return $this;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getDiscount(): int
    {
        return $this->discount;
    }


    public function jsonSerialize(): mixed
    {
        return [
            'sku' => $this->sku->value(),
            'name' => $this->name,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'discount' => $this->discount,
        ];
    }

    public static function fromArray(array $data): self
    {
        if (!isset($data['sku'], $data['name'], $data['quantity'], $data['price'], $data['discount'])) {
            throw new \InvalidArgumentException('Invalid data provided');
        }

        return new self(
            sku: new Sku($data['sku']),
            name: $data['name'],
            quantity: (int) $data['quantity'],
            price: (float) $data['price'],
            discount: $data['discount'],
        );
    }
}
