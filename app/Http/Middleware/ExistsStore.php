<?php

namespace App\Http\Middleware;

use App\Models\Store;
use Closure;
use Illuminate\Http\Request;

class ExistsStore
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $Stores = Store::where('user_id',auth()->user()->id)->where('status',2)->get();
        if ($Stores->count() == 0){
            session()->flash('error_dashboard','برای ثبت محصول ابتدا باید یک فروشگاه شما تایید شده باشد');
            return redirect(route('stores.index'));
        }
        return $next($request);
    }
}
