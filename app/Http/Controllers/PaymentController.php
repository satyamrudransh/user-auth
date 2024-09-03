<?php
namespace App\Http\Controllers;

use App\Services\StripeService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    protected $stripeService;

    public function __construct(StripeService $stripeService)
    {
        $this->stripeService = $stripeService;
    }

    public function charge(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'currency' => 'required|string',
            'source' => 'required|string',
            'customer.name' => 'required|string',
            'customer.address.line1' => 'required|string',
            'customer.address.city' => 'required|string',
            'customer.address.state' => 'required|string',
            'customer.address.country' => 'required|string',
            'customer.address.postal_code' => 'required|string',
        ]);

        try {
            $charge = $this->stripeService->createCharge(
                $request->input('amount') * 100, // Convert to cents
                $request->input('currency'),
                $request->input('source'),
                $request->input('customer.name'),
                $request->input('customer.address')
            );

            return response()->json(['success' => true, 'charge' => $charge]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
