<?php

namespace App\Http\Middleware;

use Closure;

class ClientMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (auth('api')->check() && in_array(auth('api')->user()->user_type, ['client']) && !auth('api')->user()->is_user_deactive) {
            $current_lang = $request->header('Accept-Language') ? app()->getLocale($request->header('Accept-Language')) : app()->getLocale();
            $user_locale = auth('api')->user()->current_lang;
            if ($current_lang != $user_locale) {
                auth('api')->user()->update(['current_lang' => $current_lang]);
            }
            return $next($request);
        } elseif (auth('api')->check() &&  auth('api')->user()->is_user_deactive) {
            return response()->json(['status' => 'fail', 'message' => 'تم حظر حسابك رجاء التواصل مع الادارة للتفعيل', 'data' => null], 403);
        } else {
            return response()->json(['status' => 'fail', 'message' => 'بيانات تسجيل الدخول غير صحيحة', 'data' => null], 401);
        }
    }
}
