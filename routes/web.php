<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Auth::routes(['register' => false]);
Route::get('language-change', [App\Http\Controllers\LanguageController::class, 'changeLanguage'])->name('changeLanguage');

Route::get('refreshcaptcha', [App\Http\Controllers\Auth\LoginController::class, 'refreshcaptcha'])->name('refreshcaptcha');
Route::get('qr', [App\Http\Controllers\IndexController::class, 'menu'])->name('qr');
Route::get('/', [App\Http\Controllers\IndexController::class, 'index'])->name('/')->middleware('auth.dashboard');
Route::get('blog', [App\Http\Controllers\IndexController::class, 'blog'])->name('blog');
Route::get('blog/{id}', [App\Http\Controllers\IndexController::class, 'blogDetail'])->name('blog.detail');

Route::get('policies', [App\Http\Controllers\IndexController::class, 'policies'])->name('policies');


Route::get('about', [App\Http\Controllers\IndexController::class, 'about'])->name('about');
Route::get('contacts', [App\Http\Controllers\IndexController::class, 'contacts'])->name('contacts');
Route::post('send', [App\Http\Controllers\IndexController::class, 'send'])->name('send');
Route::get('category', [App\Http\Controllers\IndexController::class, 'categories'])->name('category');
Route::get('menu/info/{id}', [App\Http\Controllers\IndexController::class, 'menuinfo'])->name('menu/info');
Route::get('product/info/{id}', [App\Http\Controllers\IndexController::class, 'proudactinfo'])->name('product/info');
Route::get('all_products', [App\Http\Controllers\IndexController::class, 'products'])->name('all_products');
Route::get('products/discount', [App\Http\Controllers\IndexController::class, 'discountprod'])->name('products/discount');
Route::get('product/category/{id}', [App\Http\Controllers\IndexController::class, 'productcategory'])->name('product/category');

// Review Routes
Route::post('reviews/store', [App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');
Route::put('reviews/{id}', [App\Http\Controllers\ReviewController::class, 'update'])->name('reviews.update');
Route::delete('reviews/{id}', [App\Http\Controllers\ReviewController::class, 'destroy'])->name('reviews.destroy');
Route::get('reviews/product/{productId}', [App\Http\Controllers\ReviewController::class, 'getProductReviews'])->name('reviews.product');

Route::post('cart/store', [App\Http\Controllers\CartController::class, 'store'])->name('cart.store');
Route::get('/cart', [App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
Route::get('/checkout', [App\Http\Controllers\CartController::class, 'checkout'])->name('checkout');
Route::delete('/cart/remove/{product_id}', [App\Http\Controllers\CartController::class, 'remove'])->name('cart.remove');
Route::get('/cart/items/count', [App\Http\Controllers\CartController::class, 'getCartItemCount'])->name('cart.items.count');
Route::post('/cart/update/{product_id}', [App\Http\Controllers\CartController::class, 'updateQuantity'])->name('cart.updateQuantity');
Route::post('/cart/check-item', [App\Http\Controllers\CartController::class, 'checkCartItem'])->name('cart.checkItem');
Route::get('/cart/debug-stock/{product_id}', [App\Http\Controllers\CartController::class, 'debugStock'])->name('cart.debugStock');
Route::post('/cart/clear', [App\Http\Controllers\CartController::class, 'clear'])->name('cart.clear');

Route::get('home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('report/all', [App\Http\Controllers\HomeController::class, 're'])->name('report/all');

Route::post('order/store', [App\Http\Controllers\OrderController::class, 'store'])->name('order.store');
Route::get('order/success', [App\Http\Controllers\OrderController::class, 'success'])->name('order/success');

Route::get('/search', [App\Http\Controllers\SearchController::class, 'index'])->name('search');

// Customer Authentication Routes
Route::get('customer/login', [App\Http\Controllers\Front\CustomerAuthController::class, 'showLoginForm'])->name('customer.login');
Route::post('customer/login', [App\Http\Controllers\Front\CustomerAuthController::class, 'login'])->name('customer.login.post');
Route::get('customer/register', [App\Http\Controllers\Front\CustomerAuthController::class, 'showRegisterForm'])->name('customer.register');
Route::post('customer/register', [App\Http\Controllers\Front\CustomerAuthController::class, 'register'])->name('customer.register.post');
Route::post('customer/logout', [App\Http\Controllers\Front\CustomerAuthController::class, 'logout'])->name('customer.logout');

// Customer Password Reset Routes
Route::get('customer/password/reset', [App\Http\Controllers\Front\CustomerAuthController::class, 'showLinkRequestForm'])->name('customer.password.request');
Route::post('customer/password/email', [App\Http\Controllers\Front\CustomerAuthController::class, 'sendResetLinkEmail'])->name('customer.password.email');
Route::get('customer/password/reset/{token}', [App\Http\Controllers\Front\CustomerAuthController::class, 'showResetForm'])->name('customer.password.reset');
Route::post('customer/password/reset', [App\Http\Controllers\Front\CustomerAuthController::class, 'reset'])->name('customer.password.update');

// Customer Wishlist Routes
Route::prefix('customer')->name('customer.')->middleware('auth.customer')->group(function () {
    Route::post('wishlist/toggle', [App\Http\Controllers\WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::get('wishlist/count', [App\Http\Controllers\WishlistController::class, 'getCount'])->name('wishlist.count');
    Route::post('wishlist/check', [App\Http\Controllers\WishlistController::class, 'checkProduct'])->name('wishlist.check');
    Route::get('wishlist', [App\Http\Controllers\WishlistController::class, 'index'])->name('wishlist.index');
    Route::delete('wishlist/remove', [App\Http\Controllers\WishlistController::class, 'remove'])->name('wishlist.remove');
});

// Public Wishlist Routes (for non-authenticated users)
Route::post('wishlist/toggle', [App\Http\Controllers\WishlistController::class, 'toggle'])->name('wishlist.toggle.public');
Route::post('wishlist/check', [App\Http\Controllers\WishlistController::class, 'checkProduct'])->name('wishlist.check.public');

// Customer Dashboard Routes
Route::middleware('auth.customer')->prefix('customer')->name('customer.')->group(function () {
    Route::get('dashboard', [App\Http\Controllers\Front\CustomerDashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('profile', [App\Http\Controllers\Front\CustomerDashboardController::class, 'profile'])->name('profile');
    Route::put('profile', [App\Http\Controllers\Front\CustomerDashboardController::class, 'updateProfile'])->name('profile.update');
    Route::get('orders', [App\Http\Controllers\Front\CustomerDashboardController::class, 'orders'])->name('orders');
    Route::get('order/{id}', [App\Http\Controllers\Front\CustomerDashboardController::class, 'showOrder'])->name('order.show');

    // AI Recommendation Routes
    Route::get('ai-recommendations', [App\Http\Controllers\Front\AIRecommendationController::class, 'getRecommendations'])->name('ai.recommendations');
    Route::post('ai-chat', [App\Http\Controllers\Front\AIRecommendationController::class, 'getAIChatResponse'])->name('ai.chat');
});


Route::namespace('Dashbord')->group(function ()
 {
  Route::get('sitesetting', [App\Http\Controllers\HomeController::class, 'sitesetting'])->name('sitesetting');
  Route::get('ordersindex', [App\Http\Controllers\HomeController::class, 'ordersindex'])->name('ordersindex');

  Route::get('users', [App\Http\Controllers\Dashbord\UserController::class, 'index'])->name('users');

  Route::get('users/create', [App\Http\Controllers\Dashbord\UserController::class, 'create'])->name('users/create');
  Route::post('users/create', [App\Http\Controllers\Dashbord\UserController::class, 'store'])->name('users/store');;
  Route::get('users/users', [App\Http\Controllers\Dashbord\UserController::class, 'users'])->name('users/users');
  Route::get('users/changeStatus/{id}', [App\Http\Controllers\Dashbord\UserController::class, 'changeStatus'])->name('users/changeStatus');
  Route::get('users/edit/{id}', [App\Http\Controllers\Dashbord\UserController::class, 'edit'])->name('users/edit');
  Route::post('users/edit/{id}', [App\Http\Controllers\Dashbord\UserController::class, 'update'])->name('users/update');
  Route::get('users/profile/{id}', [App\Http\Controllers\Dashbord\UserController::class, 'show'])->name('users/profile');
  Route::get('users/changepassword/{id}', [App\Http\Controllers\Dashbord\UserController::class, 'showChangePasswordForm'])->name('users/ChangePasswordForm');
  Route::POST('users/changepassword/{id}', [App\Http\Controllers\Dashbord\UserController::class, 'changePassword'])->name('users/changepassword');
  Route::get('users/myactivity', [App\Http\Controllers\Dashbord\UserController::class, 'myactivity'])->name('users/myactivity');

  // Roles and Permissions
  Route::get('roles', [App\Http\Controllers\Dashbord\RoleController::class, 'index'])->name('roles.index');
  Route::get('roles/create', [App\Http\Controllers\Dashbord\RoleController::class, 'create'])->name('roles.create');
  Route::post('roles', [App\Http\Controllers\Dashbord\RoleController::class, 'store'])->name('roles.store');
  Route::get('roles/roles', [App\Http\Controllers\Dashbord\RoleController::class, 'roles'])->name('roles.roles');
  Route::get('roles/edit/{id}', [App\Http\Controllers\Dashbord\RoleController::class, 'edit'])->name('roles.edit');
  Route::put('roles/{id}', [App\Http\Controllers\Dashbord\RoleController::class, 'update'])->name('roles.update');
  Route::delete('roles/{id}', [App\Http\Controllers\Dashbord\RoleController::class, 'destroy'])->name('roles.destroy');

    //----------------------------city-----------------------------------------------------------------       
    Route::get('addresses', [App\Http\Controllers\Dashbord\AddressController::class, 'index'])->name('addresses');
    Route::get('addresses/create', [App\Http\Controllers\Dashbord\AddressController::class, 'create'])->name('addresses/create');
    Route::post('addresses/create', [App\Http\Controllers\Dashbord\AddressController::class, 'store'])->name('addresses/store');;
    Route::get('addresses/addresses', [App\Http\Controllers\Dashbord\AddressController::class, 'addresses'])->name('addresses/addresses');;
    Route::get('addresses/edit/{id}', [App\Http\Controllers\Dashbord\AddressController::class, 'edit'])->name('addresses/edit');
    Route::post('addresses/edit/{id}', [App\Http\Controllers\Dashbord\AddressController::class, 'update'])->name('addresses/update');
    Route::delete('addresses/delete/{id}', [App\Http\Controllers\Dashbord\AddressController::class, 'delete'])->name('addresses/delete');

    Route::get('aboutus', [App\Http\Controllers\Dashbord\AboutusController::class, 'index'])->name('aboutus');
    Route::get('aboutus/create', [App\Http\Controllers\Dashbord\AboutusController::class, 'create'])->name('aboutus/create');
    Route::get('aboutus/aboutus', [App\Http\Controllers\Dashbord\AboutusController::class, 'aboutus'])->name('aboutus/aboutus');;
    Route::post('aboutus/create', [App\Http\Controllers\Dashbord\AboutusController::class, 'store'])->name('aboutus/store');;
    Route::get('aboutus/show/{id}', [App\Http\Controllers\Dashbord\AboutusController::class, 'show'])->name('aboutus/show');
    Route::get('aboutus/edit', [App\Http\Controllers\Dashbord\AboutusController::class, 'edit'])->name('aboutus/edit');
    Route::post('aboutus/edit', [App\Http\Controllers\Dashbord\AboutusController::class, 'update'])->name('aboutus/update');
    Route::delete('aboutus/delete/{id}', [App\Http\Controllers\Dashbord\AboutusController::class, 'destroy'])->name('aboutus.destroy');

    Route::get('slider', [App\Http\Controllers\Dashbord\SliderController::class, 'index'])->name('slider');
    Route::get('slider/create', [App\Http\Controllers\Dashbord\SliderController::class, 'create'])->name('slider/create');
    Route::post('slider/create', [App\Http\Controllers\Dashbord\SliderController::class, 'store'])->name('slider/store');;

    Route::get('slider/slider', [App\Http\Controllers\Dashbord\SliderController::class, 'sliders'])->name('slider/slider');
    Route::get('slider/delete/{id}', [App\Http\Controllers\Dashbord\SliderController::class, 'destroy'])->name('slider/delete');
  
    

    Route::get('contactus', [App\Http\Controllers\Dashbord\ContactusController::class, 'index'])->name('contactus');
    Route::get('contactus/edit', [App\Http\Controllers\Dashbord\ContactusController::class, 'edit'])->name('contactus/edit');
    Route::post('contactus/edit', [App\Http\Controllers\Dashbord\ContactusController::class, 'update'])->name('contactus/update');


    Route::get('inbox', [App\Http\Controllers\Dashbord\InboxController::class, 'index'])->name('inbox');
    Route::get('inbox/inbox', [App\Http\Controllers\Dashbord\InboxController::class, 'inbox'])->name('inbox/inbox');
  

    Route::get('categories',  [App\Http\Controllers\Dashbord\CategoriesController::class, 'index'])->name('categories.index');
    Route::get('categories/create',  [App\Http\Controllers\Dashbord\CategoriesController::class, 'create'])->name('categories.create');
    Route::post('categories',  [App\Http\Controllers\Dashbord\CategoriesController::class, 'store'])->name('categories.store');
    Route::get('categories/categories',  [App\Http\Controllers\Dashbord\CategoriesController::class, 'categories'])->name('categories.categories');
    Route::get('categories/{id}/edit',  [App\Http\Controllers\Dashbord\CategoriesController::class, 'edit'])->name('categories.edit');
    Route::put('categories/{id}',  [App\Http\Controllers\Dashbord\CategoriesController::class, 'update'])->name('categories.update');
    Route::delete('categories/{id}',  [App\Http\Controllers\Dashbord\CategoriesController::class, 'destroy'])->name('categories.destroy');
    Route::post('categories/change-status/{id}', [App\Http\Controllers\Dashbord\CategoriesController::class, 'changeCategoryStatus'])->name('categories.changeStatus');

    Route::get('blogs',  [App\Http\Controllers\Dashbord\BlogController::class, 'index'])->name('blogs.index');
    Route::get('blogs/create',  [App\Http\Controllers\Dashbord\BlogController::class, 'create'])->name('blogs.create');
    Route::post('blogs',  [App\Http\Controllers\Dashbord\BlogController::class, 'store'])->name('blogs.store');
    Route::get('blogs/blogs',  [App\Http\Controllers\Dashbord\BlogController::class, 'blogs'])->name('blogs.blogs');
    Route::get('blogs/{id}/edit',  [App\Http\Controllers\Dashbord\BlogController::class, 'edit'])->name('blogs.edit');
    Route::put('blogs/{id}',  [App\Http\Controllers\Dashbord\BlogController::class, 'update'])->name('blogs.update');
    Route::delete('blogs/{id}',  [App\Http\Controllers\Dashbord\BlogController::class, 'destroy'])->name('blogs.destroy');
    Route::post('blogs/change-status/{id}', [App\Http\Controllers\Dashbord\BlogController::class, 'changeBlogStatus'])->name('blogs.changeStatus');


    Route::get('products', [App\Http\Controllers\Dashbord\ProductsController::class, 'index'])->name('products');
    Route::get('products/create', [App\Http\Controllers\Dashbord\ProductsController::class, 'create'])->name('products/create');
    Route::post('products/create', [App\Http\Controllers\Dashbord\ProductsController::class, 'store'])->name('products/store');;
    Route::get('products/products', [App\Http\Controllers\Dashbord\ProductsController::class, 'products'])->name('products/products');
    Route::get('products/changeStatus/{id}', [App\Http\Controllers\Dashbord\ProductsController::class, 'changeStatus'])->name('products/changeStatus');
    Route::get('products/edit/{id}', [App\Http\Controllers\Dashbord\ProductsController::class, 'edit'])->name('products/edit');
    Route::get('products/delete/{id}', [App\Http\Controllers\Dashbord\ProductsController::class, 'destroy'])->name('products/delete');
    Route::post('products/edit/{id}', [App\Http\Controllers\Dashbord\ProductsController::class, 'update'])->name('products/update');
    Route::get('products/gellary/{id}', [App\Http\Controllers\Dashbord\ProductsController::class, 'gellary'])->name('products/gellary');
    Route::delete('products/image/{id}', [App\Http\Controllers\Dashbord\ProductsController::class, 'deleteImage'])->name('products.image.delete');
    Route::get('products/show/{id}', [App\Http\Controllers\Dashbord\ProductsController::class, 'show'])->name('products/show');
 


    Route::get('discounts', [App\Http\Controllers\Dashbord\DiscountController::class, 'index'])->name('discounts');
    Route::get('discounts/create', [App\Http\Controllers\Dashbord\DiscountController::class, 'create'])->name('discounts/create');

    Route::get('discounts/getproudact', [App\Http\Controllers\Dashbord\DiscountController::class, 'getproudact'])->name('discounts/getproudact');
    Route::post('discounts/create', [App\Http\Controllers\Dashbord\DiscountController::class, 'store'])->name('discounts/store');
    Route::get('discounts/discounts', [App\Http\Controllers\Dashbord\DiscountController::class, 'discount'])->name('discounts/discounts');
    Route::delete('discounts/{id}', [App\Http\Controllers\Dashbord\DiscountController::class, 'destroy'])->name('discounts.destroy');

    
    
    Route::get('pending/oreder', [App\Http\Controllers\Dashbord\OrderController::class, 'pedningindex'])->name('pending/oreder');
    Route::get('pending/oreders', [App\Http\Controllers\Dashbord\OrderController::class, 'orderspenidng'])->name('pending/oreders');
   
   Route::get('all/oreder', [App\Http\Controllers\Dashbord\OrderController::class, 'allindex'])->name('all/oreder');
    Route::get('all/oreders', [App\Http\Controllers\Dashbord\OrderController::class, 'ordersall'])->name('all/oreders');
   
   
   
   
    Route::post('pending/preparationfuction/{id}', [App\Http\Controllers\Dashbord\OrderController::class, 'preparationfuction'])->name('pending/preparationfuction');
    Route::post('pending/cancelfunction/{id}', [App\Http\Controllers\Dashbord\OrderController::class, 'cacnelfuction'])->name('pending/cancelfunction');
    Route::get('complete/oreder', [App\Http\Controllers\Dashbord\OrderController::class, 'completelindex'])->name('complete/oreder');
    Route::get('complete/oreders', [App\Http\Controllers\Dashbord\OrderController::class, 'orderscompletel'])->name('complete/oreders');
 
    
    
    Route::get('cancel/oreder', [App\Http\Controllers\Dashbord\OrderController::class, 'cancelindex'])->name('cancel/oreder');
    Route::get('cancel/oreders', [App\Http\Controllers\Dashbord\OrderController::class, 'orderscancel'])->name('cancel/oreders');

    Route::get('underprocess/oreder', [App\Http\Controllers\Dashbord\OrderController::class, 'underptocessindex'])->name('underprocess/oreder');
    Route::get('underprocess/oreders', [App\Http\Controllers\Dashbord\OrderController::class, 'underptocessindexs'])->name('underprocess/oreders');
    Route::get('orderitem/{id}', [App\Http\Controllers\Dashbord\OrderController::class, 'orderitem'])->name('orderitem');
    Route::put('order/update/{id}', [App\Http\Controllers\Dashbord\OrderController::class, 'update'])->name('order.update');
    Route::delete('/order/item/remove/{id}', [App\Http\Controllers\Dashbord\OrderController::class, 'removeItemFromOrder'])->name('order.item.remove');
    Route::post('order/complete/{id}', [App\Http\Controllers\Dashbord\OrderController::class, 'markAsComplete'])->name('order.complete');


    Route::get('notifications', [App\Http\Controllers\Dashbord\NotificationController::class, 'index'])->name('notifications.index');
    Route::get('notifications/read/{id}', [App\Http\Controllers\Dashbord\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::get('/notifications/fetch', [App\Http\Controllers\Dashbord\NotificationController::class, 'fetch'])->name('notifications.fetch');


    // Product Reviews Routes
    Route::get('reviews', [App\Http\Controllers\Dashbord\ProductReviewController::class, 'index'])->name('reviews.index');
    Route::get('reviews/reviews', [App\Http\Controllers\Dashbord\ProductReviewController::class, 'reviews'])->name('reviews.reviews');
    Route::delete('reviews/delete/{id}', [App\Http\Controllers\Dashbord\ProductReviewController::class, 'destroy'])->name('reviews.destroy');
    Route::post('reviews/change-status/{id}', [App\Http\Controllers\Dashbord\ProductReviewController::class, 'changeReviewStatus'])->name('reviews.changeStatus');
    
    
    
    


    Route::get('stock', [App\Http\Controllers\Dashbord\StockController::class, 'all'])->name('stock');
    Route::get('stock/stock', [App\Http\Controllers\Dashbord\StockController::class, 'stock'])->name('stock/stock');
  
    Route::get('stockall/{id}', [App\Http\Controllers\Dashbord\StockController::class, 'indexall'])->name('stockall');
    Route::get('stock/stockall/{id}', [App\Http\Controllers\Dashbord\StockController::class, 'stockall'])->name('stock/stockall');
  

    // Suppliers Routes
    Route::get('suppliers', [App\Http\Controllers\Dashbord\SupplierController::class, 'index'])->name('suppliers');
    Route::get('suppliers/create', [App\Http\Controllers\Dashbord\SupplierController::class, 'create'])->name('suppliers/create');
    Route::post('suppliers/create', [App\Http\Controllers\Dashbord\SupplierController::class, 'store'])->name('suppliers/store');
    Route::get('suppliers/data', [App\Http\Controllers\Dashbord\SupplierController::class, 'getSuppliers'])->name('suppliers/data');
    Route::get('suppliers/edit/{id}', [App\Http\Controllers\Dashbord\SupplierController::class, 'edit'])->name('suppliers/edit');
    Route::put('suppliers/update/{id}', [App\Http\Controllers\Dashbord\SupplierController::class, 'update'])->name('suppliers/update');
    Route::delete('suppliers/delete/{id}', [App\Http\Controllers\Dashbord\SupplierController::class, 'destroy'])->name('suppliers/destroy');

    // Customers Routes
    Route::get('customers', [App\Http\Controllers\Dashbord\CustomerController::class, 'index'])->name('customers.index');
    Route::get('customers/create', [App\Http\Controllers\Dashbord\CustomerController::class, 'create'])->name('customers.create');
    Route::post('customers', [App\Http\Controllers\Dashbord\CustomerController::class, 'store'])->name('customers.store');
    Route::get('customers/{id}', [App\Http\Controllers\Dashbord\CustomerController::class, 'show'])->name('customers.show');
    Route::get('customers/{id}/edit', [App\Http\Controllers\Dashbord\CustomerController::class, 'edit'])->name('customers.edit');
    Route::put('customers/{id}', [App\Http\Controllers\Dashbord\CustomerController::class, 'update'])->name('customers.update');
    Route::delete('customers/{id}', [App\Http\Controllers\Dashbord\CustomerController::class, 'destroy'])->name('customers.destroy');
    
    // Customer DataTable endpoint
    Route::get('customers/datatable', [App\Http\Controllers\Dashbord\CustomerController::class, 'datatable'])->name('customers.datatable');

    // Customer invoices check endpoint
    Route::get('customers/{id}/check-invoices', [App\Http\Controllers\Dashbord\CustomerController::class, 'checkInvoices'])->name('customers.checkInvoices');

    // Receipts Routes
    Route::get('receipts', [App\Http\Controllers\Dashbord\ReceiptController::class, 'index'])->name('receipts');
    Route::get('receipts/create', [App\Http\Controllers\Dashbord\ReceiptController::class, 'create'])->name('receipts/create');
    Route::post('receipts/create', [App\Http\Controllers\Dashbord\ReceiptController::class, 'store'])->name('receipts/store');
    Route::get('receipts/receipts', [App\Http\Controllers\Dashbord\ReceiptController::class, 'receipts'])->name('receipts/receipts');
    Route::get('receipts/show/{id}', [App\Http\Controllers\Dashbord\ReceiptController::class, 'show'])->name('receipts/show');
    Route::get('get-coolors-sizes', [App\Http\Controllers\Dashbord\ReceiptController::class, 'getCoolorsAndSizes'])->name('get/coolors/sizes');
    Route::get('receipts/search', [App\Http\Controllers\Dashbord\ReceiptController::class, 'search'])->name('receipts/search');

    
  

  Route::get('returns', [App\Http\Controllers\Dashbord\ReturnsController::class, 'index'])->name('returns');
  Route::get('returns/create', [App\Http\Controllers\Dashbord\ReturnsController::class, 'create'])->name('returns/create');
  Route::get('returns/fetch/invoice', [App\Http\Controllers\Dashbord\ReturnsController::class, 'fetchInvoice'])->name('returns/fetch/invoice');
  Route::post('returns/process', [App\Http\Controllers\Dashbord\ReturnsController::class, 'processReturn'])->name('returns/process');
  Route::get('returns/returns', [App\Http\Controllers\Dashbord\ReturnsController::class, 'returns'])->name('returns/returns');

  

  Route::get('Invoice', [App\Http\Controllers\Dashbord\InvoiceController::class, 'index'])->name('Invoice');
  Route::get('Invoice/create', [App\Http\Controllers\Dashbord\InvoiceController::class, 'create'])->name('Invoice/create');
  Route::post('Invoice/create', [App\Http\Controllers\Dashbord\InvoiceController::class, 'store'])->name('Invoice/store');;
  Route::get('Invoice/Invoice', [App\Http\Controllers\Dashbord\InvoiceController::class, 'invocies'])->name('Invoice/Invoice');
  Route::get('Invoice/show/{id}', [App\Http\Controllers\Dashbord\InvoiceController::class, 'show'])->name('Invoice/show');
  Route::get('Invoice/stockall', [App\Http\Controllers\Dashbord\InvoiceController::class, 'stockall'])->name('Invoice/stockall');
  Route::get('Invoice/invoice/{id}', [App\Http\Controllers\Dashbord\InvoiceController::class, 'invoice'])->name('Invoice/invoice');

  
  Route::get('report/sales', [App\Http\Controllers\Dashbord\ReportController::class, 'sales'])->name('report/sales');
  Route::get('/dashbord/report/search-sales', [App\Http\Controllers\Dashbord\ReportController::class, 'searchSales'])->name('report.searchSales');


  Route::get('report/return', [App\Http\Controllers\Dashbord\ReportController::class, 'return'])->name('report/return');
  Route::get('/dashbord/report/search-return', [App\Http\Controllers\Dashbord\ReportController::class, 'searchreturn'])->name('report.searchreturn');

 
  
  
});

Route::prefix('policy')->name('policy.')->group(function () {
    Route::get('/', [App\Http\Controllers\Dashbord\PolicyController::class, 'index'])->name('index');
    Route::get('/create', [App\Http\Controllers\Dashbord\PolicyController::class, 'create'])->name('create');
    Route::post('/store', [App\Http\Controllers\Dashbord\PolicyController::class, 'store'])->name('store');
    Route::get('/edit/{id}', [App\Http\Controllers\Dashbord\PolicyController::class, 'edit'])->name('edit');
    Route::put('/update/{id}', [App\Http\Controllers\Dashbord\PolicyController::class, 'update'])->name('update');
    Route::delete('/destroy/{id}', [App\Http\Controllers\Dashbord\PolicyController::class, 'destroy'])->name('destroy');
});
