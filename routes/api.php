<?php

use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\Auth\ResetPasswordController;
use App\Http\Controllers\Api\Auth\VerifyController;
use App\Http\Controllers\Api\Customer\Chat\ChatController;
use App\Http\Controllers\Api\Customer\Chat\ChatMessagesController;
use App\Http\Controllers\Api\Customer\Chat\ChatNotificationController;
use App\Http\Controllers\Api\Customer\Chat\UserChatPresenceStatusController;
use App\Http\Controllers\Api\Customer\City\CustomerCityController;
use App\Http\Controllers\Api\Customer\Complaint\CustomerComplaintController;
use App\Http\Controllers\Api\Customer\Complaint\CustomerComplaintMessageController;
use App\Http\Controllers\Api\Customer\PartyCategory\PartyCategoryController;
use App\Http\Controllers\Api\Customer\PartyPreparationTime\PartyPreparationTimeController;
use App\Http\Controllers\Api\Customer\PartyWishlist\PartyWishlistController;
use App\Http\Controllers\Api\Customer\Reservation\CustomerReservationController;
use App\Http\Controllers\Api\Customer\Party\CustomerPartyController;
use App\Http\Controllers\Api\Customer\PartyRate\CustomerPartyRateController;
use App\Http\Controllers\Api\Customer\Slider\CustomerSliderController;
use App\Http\Controllers\Api\Customer\Stats\CustomerStatsController;
use App\Http\Controllers\Api\Otp\OtpSenderController;
use App\Http\Controllers\Api\Payment\CustomerPaymentController;
use App\Http\Controllers\Api\Select\SelectController;
use App\Http\Controllers\Api\User\UserProfileController;
use App\Http\Controllers\Api\Vendor\Facility\FacilityController;
use App\Http\Controllers\Api\Vendor\Guest\GuestController;
use App\Http\Controllers\Api\Vendor\Party\PartyPriceListController;
use App\Http\Controllers\Api\Vendor\Party\VendorPartyController;
use App\Http\Controllers\Api\Vendor\Party\VendorPartyFacilityController;
use App\Http\Controllers\Api\Vendor\Party\VendorPartyMediaController;
use App\Http\Controllers\Api\Vendor\Party\VendorPartyTimeController;
use App\Http\Controllers\Api\Vendor\PaymentSummrize\PaymentReportController;
use App\Http\Controllers\Api\Vendor\PaymentSummrize\PaymentSummrizeController;
use App\Http\Controllers\Api\Vendor\PriceList\PriceListController;
use App\Http\Controllers\Api\Vendor\Reservation\VendorReservationController;
use App\Http\Controllers\Api\Vendor\Stats\VendorStatsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('auth')->group(function(): void{
    Route::post('register', [RegisterController::class, 'store']);
    Route::post('verify', [VerifyController::class, 'store']);
    Route::post('login', [LoginController::class, 'store']);
    Route::post('reset-password', [ResetPasswordController::class, 'store']);
});

Route::prefix('otp')->group(function(): void{
    Route::post('send', [OtpSenderController::class, 'store']);
});

Route::prefix('user-profile')->group(function(): void{
    Route::get('{id}', [UserProfileController::class, 'show']);
    Route::put('update', [UserProfileController::class, 'update']);
});


Route::prefix('cities')->group(callback: function(): void{
    Route::get('', [CustomerCityController::class, 'index']);
});

Route::prefix('parties/categories')->group(callback: function(): void{
    Route::get('', [PartyCategoryController::class, 'index']);
});

Route::prefix('selects')->group(function(){
    Route::get('', [SelectController::class, 'getSelects']);
});


Route::prefix('parties/preparation-times')->group(callback: function(): void{
    Route::get('', [PartyPreparationTimeController::class, 'index']);
});

// customer
Route::prefix('sliders')->group(callback: function(): void{
    Route::get('', [CustomerSliderController::class, 'index']);
});

Route::prefix('customer-api/parties')->group(callback: function(): void{
    Route::get('', [CustomerPartyController::class, 'index']);
    Route::get('{id}', [CustomerPartyController::class, 'show']);
});

Route::prefix('customer-api/wishlists')->group(callback: function(): void{
    Route::get('', [PartyWishlistController::class, 'index']);
    Route::post('store', [PartyWishlistController::class, 'store']);
    Route::delete('{id}', [PartyWishlistController::class, 'destroy']);
});

Route::prefix('customer-api/reservations')->group(callback: function(): void{
    Route::get('', [CustomerReservationController::class, 'index']);
    Route::post('store', [CustomerReservationController::class, 'store']);
    //Route::delete('{id}', [PartyWishlistController::class, 'destroy']);
});

Route::prefix('customer-api/parties-rate')->group(callback: function(): void{
    Route::get('', [CustomerPartyRateController::class, 'show']);
    Route::post('store', [CustomerPartyRateController::class, 'store']);
    //Route::delete('{id}', [PartyWishlistController::class, 'destroy']);
});

Route::prefix('customer-api/stats')->group(callback: function(): void{
    Route::get('', [CustomerStatsController::class, 'show']);
});

Route::prefix('customer-api/complaints')->group(callback: function(): void{
    Route::get('', [CustomerComplaintController::class, 'index']);
    Route::post('store', [CustomerComplaintController::class, 'store']);
});

Route::prefix('customer-api/complaint-messages')->group(callback: function(): void{
    Route::get('', [CustomerComplaintMessageController::class, 'index']);
    Route::post('store', [CustomerComplaintMessageController::class, 'store']);
});



// vendor api's

Route::prefix('vendor-api/parties')->group(callback: function(): void{
    Route::get('', [VendorPartyController::class, 'index']);
    Route::get('{id}', [VendorPartyController::class, 'show']);
    Route::post('store', [VendorPartyController::class, 'store']);
    Route::put('update', [VendorPartyController::class, 'update']);
});

Route::prefix('vendor-api/party-facilities')->group(callback: function(): void{
    Route::get('', [VendorPartyFacilityController::class, 'index']);
    Route::post('store', [VendorPartyFacilityController::class, 'store']);
    // Route::get('{id}', [VendorPartyFacilityController::class, 'show']);
    // Route::put('update', [VendorPartyFacilityController::class, 'update']);
    // Route::delete('{id}', [VendorPartyFacilityController::class, 'destroy']);
});

Route::prefix('vendor-api/party-prep-times')->group(callback: function(): void{
    Route::get('', [VendorPartyTimeController::class, 'index']);
    Route::post('store', [VendorPartyTimeController::class, 'store']);
    // Route::get('{id}', [VendorPartyTimeController::class, 'show']);
    // Route::put('update', [VendorPartyTimeController::class, 'update']);
    // Route::delete('{id}', [VendorPartyTimeController::class, 'destroy']);

});

Route::prefix('vendor-api/party-media')->group(callback: function(): void{
    Route::get('', [VendorPartyMediaController::class, 'index']);
    Route::post('store', [VendorPartyMediaController::class, 'store']);
    // Route::get('{id}', [VendorPartyTimeController::class, 'show']);
    // Route::put('update', [VendorPartyTimeController::class, 'update']);
    Route::delete('{id}', [VendorPartyMediaController::class, 'destroy']);

});

Route::prefix('vendor-api/pricelists')->group(callback: function(): void{
    Route::get('', [PriceListController::class, 'index']);
    Route::post('store', [PriceListController::class, 'store']);
    Route::get('{id}', [PriceListController::class, 'edit']);
    Route::put('update', [PriceListController::class, 'update']);
    Route::delete('{id}', [PriceListController::class, 'destroy']);

});

Route::prefix('vendor-api/facilities')->group(callback: function(): void{
    Route::get('', [FacilityController::class, 'index']);
});


Route::prefix('vendor-api/party-pricelist')->group(callback: function(): void{
    Route::get('', [PartyPriceListController::class, 'index']);
    Route::post('store', [PartyPriceListController::class, 'store']);
    Route::get('{id}', [PartyPriceListController::class, 'edit']);
    Route::put('update', [PartyPriceListController::class, 'update']);
    Route::delete('{id}', [PartyPriceListController::class, 'destroy']);
});

Route::prefix('vendor-api/reservations')->group(callback: function(): void{
    Route::get('', [VendorReservationController::class, 'index']);
    Route::post('store', [PartyPriceListController::class, 'store']);
    Route::get('{id}', [PartyPriceListController::class, 'edit']);
    Route::put('update', [PartyPriceListController::class, 'update']);
    Route::delete('{id}', [PartyPriceListController::class, 'destroy']);
});

Route::prefix('vendor-api/stats')->group(callback: function(): void{
    Route::get('', [VendorStatsController::class, 'show']);
});

Route::prefix('vendor-api/comming-guests')->group(callback: function(): void{
    Route::get('', [GuestController::class, 'index']);
});

Route::prefix('vendor-api/payment-summrized-report')->group(callback: function(): void{
    Route::get('', [PaymentSummrizeController::class, 'index']);
});

Route::prefix('vendor-api/payment-report')->group(callback: function(): void{
    Route::get('', [PaymentReportController::class, 'index']);
});








/*Route::prefix('vendor-api/party-media')->group(callback: function(): void{
    Route::get('store', [VendorPartyMediaController::class, 'store']);
});*/





Route::prefix('chats')->group(function(): void{
    Route::get('', [ChatController::class, 'index']);
});

Route::prefix('chats/messages')->group(function(): void{
    Route::get('', [ChatMessagesController::class, 'index']);
    Route::post('store', [ChatMessagesController::class, 'store']);
});

Route::prefix('chats/unread-message')->group(function(): void{
    Route::get('', [ChatNotificationController::class, 'show']);
    Route::put('update', [ChatNotificationController::class, 'update']);
});

Route::prefix('chats')->group(function(): void{
    Route::Post('user-connected', [UserChatPresenceStatusController::class, 'userConnected']);
    Route::Post('user-disconnected', [ChatNotificationController::class, 'userDisonnected']);
});




/*Route::post('/send-message', function (Illuminate\Http\Request $request) {
    broadcast(new App\Events\HomeEvent($request->input('message')));
    return ['status' => 'Message sent!'];
});*/

Route::get('/receive-messages', function () {
    return view('test-websocket.receive-messages');
});

Route::get('/global', function () {
    return view('test-websocket.global-messages');
});

Route::get('/send-party', function () {
    return view('test-websocket.send-party');
});

Route::get('/chat/{chatId}', function ($chatId) {
    return view('test-websocket.chat', compact('chatId'));
});



Route::get('all-chats', function () {
    return view('test-websocket.all-chats');
});


Route::get('/payment/callback', [CustomerPaymentController::class, 'paymentCallback'])->name('payment.callback');


Route::get('/payment', function () {
    return view('moyasar_payment');
});


