<?php

namespace App\Http\Middleware;

use App\Models\Contact;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CountingВebtors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $debtors_balance = Contact::where('balance', '<', '0')->whereHas('shipments')->sum('balance');
        
        if ($debtors_balance) {
            $request['debtors_balance'] = $debtors_balance;
        }

        return $next($request);
    }
}
