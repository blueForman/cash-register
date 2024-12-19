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

    /**
     * Display a listing of the resource.
     */
    public function initiate(Request $request): JsonResponse
    {
        $customerId = $request->input('customerId');
        $cart = $this->cartFacade->initiateCartForCustomer((int) $customerId);
        return new JsonResponse($cart);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
