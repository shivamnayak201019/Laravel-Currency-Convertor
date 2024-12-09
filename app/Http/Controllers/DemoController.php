<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DemoController extends Controller
{
    public function convert(Request $request)
    {
        $converted = null;

        // Validate inputs
        // $request->validate([
        //     'currency_from' => 'required|string|max:3',
        //     'currency_to' => 'required|string|max:3',
        //     'amount' => 'required|numeric|min:0.01',
        // ]);

        if ($request->filled(['currency_from', 'currency_to', 'amount'])) {
            $from = strtolower($request->get('currency_from'));
            $to = strtolower($request->get('currency_to'));
            $amount = $request->get('amount');

            $url = "https://cdn.jsdelivr.net/npm/@fawazahmed0/currency-api@latest/v1/currencies/{$from}.json";

            try {
                $response = Http::get($url);

                if ($response->successful()) {
                    $rates = $response->json();

                    // Verify structure and calculate conversion
                    if (isset($rates[$from][$to])) {
                        $rate = $rates[$from][$to];
                        $converted = $amount * $rate;
                    
                        return view('currency', compact('converted'));
                    } else {
                        return back()->withErrors(['error' => 'Invalid target currency selected.']);
                    }
                } else {
                    Log::error('Currency API call failed', ['url' => $url, 'status' => $response->status()]);
                    return back()->withErrors(['error' => 'Unable to fetch exchange rate. Try again later.']);
                }
            } catch (\Exception $e) {
                Log::error('Currency conversion failed', ['error' => $e->getMessage()]);
                return back()->withErrors(['error' => 'An unexpected error occurred.']);
            }
        }

        return view('currency', compact('converted'));
    }
}