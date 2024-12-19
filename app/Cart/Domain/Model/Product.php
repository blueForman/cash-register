<?php
declare(strict_types=1);
namespace App\Cart\Domain\Model;

use App\Cart\Domain\Value\Sku;

final class Product implements \JsonSerializable
{
    public function __construct(
        private readonly Sku $sku,
        private readonly string $name,
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
            'price' => $this->price,
            'discount' => $this->discount,
        ];
    }

    public static function fromArray(array $data): self
    {
        if (!isset($data['sku'], $data['name'], $data['price'], $data['discount'])) {
            throw new \InvalidArgumentException('Invalid data provided');
        }

        return new self(
            sku: new Sku($data['sku']),
            name: $data['name'],
            price: (float) $data['price'],
            discount: $data['discount'],
        );
    }
}
