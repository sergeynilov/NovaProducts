<?php

namespace App\Jobs;

use App\Exceptions\EmptyCartException;
use App\Exceptions\EmptySock5CredentialsException;
use App\Http\Resources\Proxy\ProxyDetailResource;
use App\Models\User;
use App\Services\CartService;
use App\Services\OrderService;
//use App\Services\Proxy\UserProxyService;
//use App\Services\UnlimitedSubscriptionService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class OrderProcessed implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected User $user;
    protected OrderService $orderService;
    protected CartService $cartService;
//    protected UnlimitedSubscriptionService $unlimitedSubscriptionService;
//    protected UserProxyService $userProxyService;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        $this->user = auth()->user();
        $this->orderService = new OrderService($this->user);
        $this->cartService = new CartService($this->user);
//        $this->unlimitedSubscriptionService = new UnlimitedSubscriptionService($this->user);
//        $this->userProxyService = new UserProxyService($this->user);
    }

//`status` enum('D','I','C','P','O','R') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'D - Draft, I-Invoice, C-Cancelled, P-Processing, O - Completed, R - Refunded',
    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if(empty($this->user->socks5_username) || empty($this->user->socks5_password)){
            throw new EmptySock5CredentialsException();
        }

        $cart = $this->cartService->getUserCart();

        if(empty($cart) || $cart->proxies->isEmpty()) {
            throw new EmptyCartException();
        }

        $orderedProxyIds = $cart->proxies->pluck('id')->toArray();

//        $activeSubscription = $this->unlimitedSubscriptionService->getUserActiveSubscription();
//        if(!empty($activeSubscription)) {
//            $this->unlimitedSubscriptionService->attachProxiesFromCart($activeSubscription, $cart);
//        } else {
            $amount = $cart->total;
            $this->orderService->distributeReferralBonus($amount);
            $order = $this->orderService->createOrder($amount);
            $this->orderService->payForOrder($order);
//            $this->userProxyService->addProxiesFromCart($cart, $order);
//        }

        $orderedProxies = $this->user->proxies->whereIn('id', $orderedProxyIds);

        return response()->json([
            'status' => 'success',
            'message' => 'Success! Order confirmed!',
            'cartItems' => [],
            'cartTotal' => 0,
            'orderedProxies' => ProxyDetailResource::collection($orderedProxies)->resolve()
        ]);
    }
}
