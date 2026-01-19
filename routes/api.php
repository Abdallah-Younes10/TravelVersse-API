<?php

// use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
// use App\Http\Controllers\UserController;
use App\Http\Controllers\FlightController;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AuthController,
    ImageController,
    TripController,
    ActivityController,
    CruiseController,
    FavoriteController,
    CartController,
    HotelController,
    RestaurantController,
    CarController,
    ReviewController,
    ReservationController,
    // UserController
};
use App\Notifications\WelcomeUser;


// 1. المسارات المتاحة للضيوف فقط (Guest Only)
Route::middleware(['guest.only'])->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail']);
    Route::post('/reset-password', [ResetPasswordController::class, 'reset']);


});

// 2. المسارات المتاحة للجميع (عرض بيانات فقط)
Route::get('/hotels', [HotelController::class, 'index']);
Route::get('/hotels/{hotel}', [HotelController::class, 'show']);

Route::get('/trips', [TripController::class, 'index']);
Route::get('/trips/{trip}', [TripController::class, 'show']);

Route::get('/activities', [ActivityController::class, 'index']);
Route::get('/activities/{activity}', [ActivityController::class, 'show']);

Route::get('/cruises', [CruiseController::class, 'index']);
Route::get('/cruises/{cruise}', [CruiseController::class, 'show']);

Route::get('/restaurants', [RestaurantController::class, 'index']);
Route::get('/restaurants/{restaurant}', [RestaurantController::class, 'show']);

Route::get('/cars', [CarController::class, 'index']);
Route::get('/cars/{car}', [CarController::class, 'show']);

Route::get('/flights', [FlightController::class, 'index']);
Route::get('/flights/{flight}', [FlightController::class, 'show']);


// routes/api.php
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [UserController::class, 'profile']);
    Route::post('/update-profile', [UserController::class, 'updateProfile']);
    Route::post('/change-password', [UserController::class, 'changePassword']);
    Route::delete('/delete-account', [UserController::class, 'deleteAccount']);
});

// 3. المسارات التي تتطلب مصادقة
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);

    // رفع وحذف الصور (متاحة لأي مستخدم مصادق عليه حالياً)
    Route::post('/images', [ImageController::class, 'store']);
    Route::delete('/images/{id}', [ImageController::class, 'destroy']);

    // 4. المسارات الخاصة بالمستخدم العادي فقط
    Route::middleware('usertype:user')->group(function () {
        // Favorites و Cart
        Route::get('/favorites', [FavoriteController::class, 'index']);
        Route::post('/favorites', [FavoriteController::class, 'store']);
        Route::delete('/favorites/{id}', [FavoriteController::class, 'destroy']);

        // Reviews - إضافة وعرض فقط للمستخدمين
        Route::apiResource('reviews', ReviewController::class)->only(['index', 'store', 'show']);

        // Reservations - حجز فقط للمستخدمين
        Route::get('/reservations', [ReservationController::class, 'index']);
        Route::post('/reservations', [ReservationController::class, 'store']);
        Route::get('/reservations/{reservation}', [ReservationController::class, 'show']);
        Route::delete('/reservations/{reservation}', [ReservationController::class, 'destroy']);

        // تحديث الحالة
        Route::patch('/reservations/{id}/status', [ReservationController::class, 'updateStatus']);

        // الدفع الوهمي
        Route::post('/reservations/{id}/pay', [ReservationController::class, 'pay']);
    });

    // 5. المسارات الخاصة بـ Admin و Tour Guide فقط
    Route::middleware('usertype:admin,tour_guide')->group(function () {
        // Hotels
        Route::post('/hotels', [HotelController::class, 'store']);
        Route::put('/hotels/{hotel}', [HotelController::class, 'update']);
        Route::delete('/hotels/{hotel}', [HotelController::class, 'destroy']);

         Route::get('/users', [UserController::class, 'index']);
        Route::post('/users', [UserController::class, 'store']);
        Route::put('/users/{id}', [UserController::class, 'update']);
        Route::delete('/users/{id}', [UserController::class, 'destroy']);
        Route::get('/users/all', [UserController::class, 'all']);

        // Trips
        Route::post('/trips', [TripController::class, 'store']);
        Route::put('/trips/{trip}', [TripController::class, 'update']);
        Route::delete('/trips/{trip}', [TripController::class, 'destroy']);
        Route::post('/trips/{id}/images', [TripController::class, 'uploadImages']);


        // Activities
        Route::post('/activities', [ActivityController::class, 'store']);
        Route::put('/activities/{activity}', [ActivityController::class, 'update']);
        Route::delete('/activities/{activity}', [ActivityController::class, 'destroy']);
        Route::get('/activities/types', [ActivityController::class, 'uniqueTypes']);

        // Cruises
        Route::post('/cruises', [CruiseController::class, 'store']);
        Route::put('/cruises/{cruise}', [CruiseController::class, 'update']);
        Route::delete('/cruises/{cruise}', [CruiseController::class, 'destroy']);

        // Restaurants
        Route::post('/restaurants', [RestaurantController::class, 'store']);
        Route::put('/restaurants/{restaurant}', [RestaurantController::class, 'update']);
        Route::delete('/restaurants/{restaurant}', [RestaurantController::class, 'destroy']);

        // Cars
        Route::post('/cars', [CarController::class, 'store']);
        Route::put('/cars/{car}', [CarController::class, 'update']);
        Route::delete('/cars/{car}', [CarController::class, 'destroy']);
        // داخل middleware usertype:admin,tour_guide
        Route::post('/flights', [FlightController::class, 'store']);
        Route::put('/flights/{flight}', [FlightController::class, 'update']);
        Route::delete('/flights/{flight}', [FlightController::class, 'destroy']);


        // Admin أو Guide ممكن يحذف reviews أو يشوف reservations
        Route::delete('/reviews/{review}', [ReviewController::class, 'destroy']);
        // Route::get('/reservations', [ReservationController::class, 'index']);
    });
});

// Route::get('/test-mail', function () {
//     $user = User::find(39); // أو أي مستخدم موجود
//     $user->notify(new WelcomeUser());
//     return 'تم إرسال البريد ✉️ بنجاح!';
// });
