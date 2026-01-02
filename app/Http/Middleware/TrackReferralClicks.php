<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Influencer;
use App\Models\User;
use App\Models\Customer;
use App\Models\ReferralLinkClick;

class TrackReferralClicks
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->has('ref')) {
            $ref = $request->get('ref');

            // Prevent multiple logs in the same session for the same code
            $sessionKey = 'tracked_ref_' . $ref;
            if (!session()->has($sessionKey)) {
                $referrer = $this->findReferrer($ref);

                if ($referrer) {
                    $userAgent = $request->userAgent();
                    $deviceType = 'desktop';

                    if (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i', $userAgent)) {
                        $deviceType = 'mobile';
                    } elseif (preg_match('/tablet|ipad|playbook|silk/i', $userAgent)) {
                        $deviceType = 'tablet';
                    }

                    ReferralLinkClick::create([
                        'referrer_id' => $referrer->id,
                        'referrer_type' => get_class($referrer),
                        'ip_address' => $request->ip(),
                        'user_agent' => $userAgent,
                        'device_type' => $deviceType,
                    ]);

                    session([$sessionKey => true]);
                    session(['referral_code' => $ref]);
                }
            }
        }

        return $next($request);
    }

    protected function findReferrer(string $code)
    {
        // Try Influencer
        if ($influencer = Influencer::where('referral_code', $code)->first()) {
            return $influencer;
        }

        // Try Business Owner (User)
        if ($user = User::where('referral_code', $code)->first()) {
            return $user;
        }

        // Try Customer
        if ($customer = Customer::where('referral_code', $code)->first()) {
            return $customer;
        }

        return null;
    }
}
