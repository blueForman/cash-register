<?php

namespace App\Cart\Infrastructure\Controllers;

use App\Cart\Application\CartFacade;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class CartController extends Controller
{
    public function __construct(private readonly CartFacade $cartFacade)
    {
    }

    public function initiate(Request $request): JsonResponse
    {
        $customerId = $request->input('customerId');
        $cart = $this->cartFacade->initiateCartForCustomer((int) $customerId);
        return new JsonResponse($cart);
    }

    public function addToCart(Request $request): JsonResponse
    {
        $cartId = $request->input('cartId');
        $sku = $request->input('sku');
        $quantity = $request->input('quantity');
        $cart = $this->cartFacade->addProductToCart((string) $cartId, (string) $sku, (int) $quantity);

        return new JsonResponse($cart);
    }

    public function removeFromCart(Request $request): JsonResponse
    {
        $cartId = $request->input('cartId');
        $sku = $request->input('sku');
        $quantity = $request->input('quantity');
        $cart = $this->cartFacade->removeFromCart((string) $cartId, (string) $sku, (int) $quantity);

        return new JsonResponse($cart);
    }
}
