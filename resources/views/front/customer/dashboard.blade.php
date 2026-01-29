

@extends('front.app')

@section('title', __('app.customer_dashboard'))

@section('content')
<!-- Dashboard Layout with Tab Navigation -->
<div class="dashboard-layout login">
    <!-- Tab Navigation -->
    <div class="dashboard-tabs">
        <div class="tab-nav-container">
            <ul class="tab-nav">
                <li class="tab-item active">
                    <a href="#" class="tab-link" data-tab="dashboard">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>{{ __('app.dashboard') }}</span>
                    </a>
                </li>
                <li class="tab-item">
                    <a href="#" class="tab-link" data-tab="profile">
                        <i class="fas fa-user"></i>
                        <span>{{ __('app.profile') }}</span>
                    </a>
                </li>
                <li class="tab-item">
                    <a href="#" class="tab-link" data-tab="orders">
                        <i class="fas fa-shopping-bag"></i>
                        <span>{{ __('app.my_orders') }}</span>
                    </a>
                </li>
                <li class="tab-item">
                    <a href="#" class="tab-link" data-tab="wishlist">
                        <i class="fas fa-heart"></i>
                        <span>{{ __('app.wishlist') }}</span>
                    </a>
                </li>
                <li class="tab-item">
                    <a href="#" class="tab-link" data-tab="reviews">
                        <i class="fas fa-star"></i>
                        <span>{{ __('app.my_reviews') }}</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container">
        <div class="login__content">
            <div class="dashboard-main">
                <!-- Dashboard Content -->
                <div class="dashboard-content">
                    <!-- Dashboard Tab Content -->
                    <div class="breadcrumb tab-content active" id="dashboard-content">
                        <!-- Welcome Section -->
                        <div class="welcome-section">
                            <div class="welcome-card">
                                <h3>{{ __('app.welcome_back') }}, {{ Auth::guard('customer')->user()->name }}!</h3>
                                <p>{{ __('app.dashboard_welcome_message') }}</p>
                            </div>
                        </div>

                        <!-- Quick Stats -->
                        <div class="stats-grid">
                            <div class="stat-card">
                                <div class="stat-icon">
                                    <i class="fas fa-shopping-bag"></i>
                                </div>
                                <div class="stat-content">
                                    <h4>{{ $orders->count() }}</h4>
                                    <p>{{ __('app.total_orders') }}</p>
                                </div>
                            </div>

                            <div class="stat-card status-pending-card">
                                <div class="stat-icon pending-icon">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div class="stat-content">
                                    <h4>{{ $orderStatusCounts['pending'] ?? 0 }}</h4>
                                    <p>{{ __('app.pending_orders') }}</p>
                                </div>
                            </div>

                            <div class="stat-card status-processing-card">
                                <div class="stat-icon processing-icon">
                                    <i class="fas fa-cog"></i>
                                </div>
                                <div class="stat-content">
                                    <h4>{{ $orderStatusCounts['processing'] ?? 0 }}</h4>
                                    <p>{{ __('app.processing_orders') }}</p>
                                </div>
                            </div>

                            <div class="stat-card status-completed-card">
                                <div class="stat-icon completed-icon">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="stat-content">
                                    <h4>{{ $orderStatusCounts['completed'] ?? 0 }}</h4>
                                    <p>{{ __('app.completed_orders') }}</p>
                                </div>
                            </div>

                            <div class="stat-card status-cancelled-card">
                                <div class="stat-icon cancelled-icon">
                                    <i class="fas fa-times-circle"></i>
                                </div>
                                <div class="stat-content">
                                    <h4>{{ $orderStatusCounts['cancelled'] ?? 0 }}</h4>
                                    <p>{{ __('app.cancelled_orders') }}</p>
                                </div>
                            </div>

                            <div class="stat-card">
                                <div class="stat-icon">
                                    <i class="fas fa-heart"></i>
                                </div>
                                <div class="stat-content">
                                    <h4>{{ $wishlistCount ?? 0 }}</h4>
                                    <p>{{ __('app.wishlist_items') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Orders -->
                        <div class="section-card">
                            <div class="section-header">
                                <h3>{{ __('app.recent_orders') }}</h3>
                                <a href="#" class="view-all-btn" onclick="switchTab('orders')">{{ __('app.view_all') }}</a>
                            </div>

                            @if($orders->count() > 0)
                                <div class="orders-list">
                                    @foreach($orders->take(5) as $order)
                                        <div class="order-item">
                                            <div class="order-info">
                                                <div class="order-id">#{{ $order->ordersnumber }}</div>
                                                <div class="order-date">{{ $order->created_at ? $order->created_at->format('M d, Y') : '-' }}</div>
                                            </div>
                                            <div class="order-status status-{{ strtolower($order->status) }}">
                                                {{ $order->status }}
                                            </div>
                                            <div class="order-total">{{trans('app.lyd')}}{{ number_format($order->total_price, 2) }}</div>
                                            <div class="order-actions">
                                                <a href="{{ route('customer.order.show', $order->id) }}" class="btn btn-sm btn-outline">
                                                    {{ __('app.view_details') }}
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="empty-state">
                                    <i class="fas fa-shopping-bag"></i>
                                    <h4>{{ __('app.no_orders_yet') }}</h4>
                                    <p>{{ __('app.start_shopping_message') }}</p>
                                    <a style="color: white" href="{{ route('all_products') }}" class="btn btn-primary">
                                        {{ __('app.shop_now') }}
                                    </a>
                                </div>
                            @endif
                        </div>

                        <!-- Quick Actions -->
                        <div class="section-card">
                            <div class="section-header">
                                <h3>{{ __('app.quick_actions') }}</h3>
                            </div>

                            <div class="quick-actions-grid">
                                <a href="#" class="action-card" onclick="switchTab('profile')">
                                    <i class="fas fa-user-edit"></i>
                                    <span>{{ __('app.update_profile') }}</span>
                                </a>
                                <a href="#" class="action-card" onclick="switchTab('orders')">
                                    <i class="fas fa-history"></i>
                                    <span>{{ __('app.order_history') }}</span>
                                </a>
                                <a href="#" class="action-card" onclick="switchTab('wishlist')">
                                    <i class="fas fa-heart"></i>
                                    <span>{{ __('app.manage_wishlist') }}</span>
                                </a>
                            </div>
                        </div>

                        <!-- AI Product Recommendation Chat Section -->
                        <div class="section-card ai-recommendation-section">
                            <div class="section-header">
                                <h3>{{ __('app.ai_product_recommendations') }}</h3>
                            </div>

                            <div class="ai-chat-container">
                                <div class="ai-chat-header">
                                    <div class="ai-avatar">
                                        <i class="fas fa-robot"></i>
                                    </div>
                                    <div class="ai-info">
                                        <h4>{{ __('app.ai_assistant') }}</h4>
                                        <p>{{ __('app.ai_assistant_description') }}</p>
                                    </div>
                                </div>

                                <div class="ai-chat-messages" id="ai-chat-messages">
                                    <div class="ai-message loading-message" id="ai-loading-message">
                                        <div class="ai-avatar">
                                            <i class="fas fa-robot"></i>
                                        </div>
                                        <div class="ai-message-content">
                                            <div class="typing-indicator">
                                                <span></span>
                                                <span></span>
                                                <span></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="ai-chat-input">
                                    <input type="text" id="ai-chat-input" placeholder="{{ __('app.type_your_message') }}...">
                                    <button id="ai-send-button">
                                        <i class="fas fa-paper-plane"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="recommended-products" id="recommended-products">
                                <div class="products-loading">
                                    <div class="spinner"></div>
                                    <p>{{ __('app.loading_recommendations') }}...</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Profile Tab Content -->
                    <div class="tab-content" id="profile-content">
                        <div class="login__form">
                            <div class="checkout__form">
                                <div class="checkout__form__contact">
                                    <div class="checkout__form__contact__title">
                                        <h2 class="checkout-title">{{ __('app.edit_profile') }}</h2>
                                        <p>{{ __('app.update_your_profile_info') }}</p>
                                    </div>

                                    @if(session('success'))
                                        <div class="alert alert-success">
                                            {{ session('success') }}
                                        </div>
                                    @endif

                                    @if($errors->any())
                                        <div class="alert alert-danger">
                                            <ul class="mb-0">
                                                @foreach($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    <form action="{{ route('customer.profile.update') }}" method="POST">
                                        @csrf
                                        @method('PUT')

                                        <div class="input-validator">
                                            <label>{{ __('app.full_name') }} *</label>
                                            <input
                                                type="text"
                                                name="name"
                                                value="{{ old('name', Auth::guard('customer')->user()->name) }}"
                                                placeholder="{{ __('app.name_placeholder') }}"
                                                required
                                            />
                                        </div>

                                        <div class="input-validator">
                                            <label>{{ __('app.email') }} *</label>
                                            <input
                                                type="email"
                                                name="email"
                                                value="{{ old('email', Auth::guard('customer')->user()->email) }}"
                                                placeholder="{{ __('app.email_placeholder') }}"
                                                required
                                            />
                                        </div>

                                        <div class="input-validator">
                                            <label>{{ __('app.phone') }} *</label>
                                            <input
                                                type="text"
                                                name="phone"
                                                value="{{ old('phone', Auth::guard('customer')->user()->phone) }}"
                                                placeholder="{{ __('app.phone_placeholder') }}"
                                                required
                                            />
                                        </div>

                                        <div class="input-validator">
                                            <label>{{ __('app.address') }}</label>
                                            <textarea
                                                name="address"
                                                placeholder="{{ __('app.address_placeholder') }}"
                                                rows="3"
                                            >{{ old('address', Auth::guard('customer')->user()->address) }}</textarea>
                                        </div>

                                        <div class="input-validator">
                                            <label>{{ __('app.password') }} ({{ __('app.leave_blank') }})</label>
                                            <input
                                                type="password"
                                                name="password"
                                                placeholder="{{ __('app.password_placeholder') }}"
                                                id="password"
                                            />
                                        </div>

                                        <div class="input-validator">
                                            <label>{{ __('app.confirm_password') }}</label>
                                            <input
                                                type="password"
                                                name="password_confirmation"
                                                placeholder="{{ __('app.confirm_password_placeholder') }}"
                                                id="password_confirmation"
                                            />
                                        </div>

                                        <div class="input-validator">
                                            <button type="submit" class="btn -dark">
                                                {{ __('app.update_profile') }}
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Orders Tab Content -->
                    <div class="tab-content" id="orders-content">
                        <div class="section-card">
                            <div class="section-header">
                                <h3>{{ __('app.my_orders') }}</h3>
                            </div>

                            @if($orders->count() > 0)
                                <form action="#">
                                    <table class="table-responsive cart-wrap">
                                        <thead>
                                            <tr>
                                                <th class="images images-b">{{ __('app.order_number') }}</th>
                                                <th class="product">{{ __('app.date') }}</th>
                                                <th class="ptice">{{ __('app.status') }}</th>
                                                <th class="ptice">{{ __('app.total') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($orders as $order)
                                                <tr>
                                                    <td class="images">#{{ $order->ordersnumber }}</td>
                                                    <td class="product">{{ $order->created_at ? $order->created_at->format('d : m : Y') : '-' }}</td>
                                                    <td class="ptice">
                                                        @if(app()->getLocale() == 'ar')
                                                            {{ $order->orderstatues->state}}
                                                        @else
                                                            {{ $order->orderstatues->state_en}}
                                                        @endif

                                                    </td>
                                                    <td class="">{{ number_format($order->total_price, 2) }} {{ __('app.lyd') }}</td>

                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </form>
                            @else
                                <div class="alert alert-info text-center">
                                    {{ __('app.no_orders_yet') }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Wishlist Tab Content -->
                    <div class="tab-content" id="wishlist-content">
                        <div class="section-card">
                            <div class="section-header">
                                <h3>{{ __('app.my_wishlist') }}</h3>
                                <span class="wishlist-count">{{ $wishlistCount ?? 0 }} {{ __('app.items') }}</span>
                            </div>

                            @if(isset($wishlistItems) && $wishlistItems->count() > 0)
                                <div class="wishlist-items">
                                    <div class="wishlist-table-wrapper">
                                        <table class="wishlist-table">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('app.product') }}</th>
                                                    <th>{{ __('app.unit_price') }}</th>
                                                    <th>{{ __('app.stock_status') }}</th>
                                                    <th>{{ __('app.actions') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($wishlistItems as $wishlistItem)
                                                    <tr class="wishlist-item" data-product-id="{{ $wishlistItem->product->id }}">
                                                        <td>
                                                            <div class="wishlist-product-info">
                                                                <div class="product-image">
                                                                    @if($wishlistItem->product->image)
                                                                        <img src="{{ asset('images/product/'.$wishlistItem->product->image) }}" alt="{{ $wishlistItem->product->name }}" />
                                                                    @else
                                                                        <img src="{{ asset('public/images/no-image.png') }}" alt="No image" />
                                                                    @endif
                                                                </div>
                                                                <div class="product-details">
                                                                    @if(app()->getLocale() == 'ar')
                                                                        <h5>{{ $wishlistItem->product->category->name ?? __('app.uncategorized') }}</h5>
                                                                        <a href="{{ route('product/info', $wishlistItem->product->id) }}" class="product-name">
                                                                            {{ $wishlistItem->product->name }}
                                                                        </a>
                                                                    @else
                                                                        <h5>{{ $wishlistItem->product->category->englishname ?? __('app.uncategorized') }}</h5>
                                                                        <a href="{{ route('product/info', $wishlistItem->product->id) }}" class="product-name">
                                                                            {{ $wishlistItem->product->namee ?? $wishlistItem->product->name }}
                                                                        </a>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="product-price">
                                                            @if(app()->getLocale() == 'ar')
                                                                دينار

                                                            @else
                                                                LYD
                                                            @endif

                                                            {{ number_format($wishlistItem->product->price, 2) }}</td>
                                                        <td class="stock-status">
                                                            @if($wishlistItem->product->available > 0)
                                                                <span class="in-stock">{{ __('app.in_stock') }}</span>
                                                            @else
                                                                <span class="out-of-stock">{{ __('app.out_of_stock') }}</span>
                                                            @endif
                                                        </td>
                                                        <td class="wishlist-actions">
                                                            @if($wishlistItem->product->available > 0)
                                                                <button class="btn btn-sm btn-primary add-to-cart-btn" data-product-id="{{ $wishlistItem->product->id }}">
                                                                    {{ __('app.add_to_cart') }}
                                                                </button>
                                                            @endif
                                                            <button class="btn btn-sm btn-outline remove-from-wishlist-btn" data-product-id="{{ $wishlistItem->product->id }}">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @else
                                <div class="empty-state">
                                    <i class="fas fa-heart"></i>
                                    <h4>{{ __('app.no_items_in_wishlist') }}</h4>
                                    <p>{{ __('app.add_items_to_wishlist') }}</p>
                                    <a href="{{ route('all_products') }}" class="btn btn-primary">
                                        {{ __('app.browse_products') }}
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Reviews Tab Content -->
                    <div class="tab-content" id="reviews-content">
                        <div class="section-card">
                            <div class="section-header">
                                <h3>{{ __('app.my_reviews') }}</h3>
                            </div>

                            @if(isset($myReviews) && $myReviews->count() > 0)
                                <div class="reviews-list">
                                    @foreach($myReviews as $review)
                                        <div class="review-item">
                                            <div class="review-header">
                                                <div class="review-product-info">
                                                    @if($review->product)
                                                        <a href="{{ route('product/info', encrypt($review->product->id)) }}">
                                                            @if($review->product->image)
                                                                <img src="{{ asset('images/product/'.rawurlencode($review->product->image)) }}" alt="{{ $review->product->name }}" class="review-product-image"/>
                                                            @else
                                                                <img src="{{ asset('images/product/default-product.jpg') }}" alt="{{ $review->product->name }}" class="review-product-image"/>
                                                            @endif
                                                            <span>
                                                                {{ app()->getLocale() == 'ar' 
                                                                    ? ($review->product->name ?? $review->product->namee) 
                                                                    : ($review->product->namee ?? $review->product->name) }}
                                                            </span>
                                                        </a>
                                                    @else
                                                        <span>{{ __('app.product_unavailable') }}</span>
                                                    @endif
                                                </div>
                                                <div class="review-date">
                                                    {{ $review->created_at->format('M d, Y') }}
                                                </div>
                                            </div>
                                            <div class="review-rating">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= $review->rating)
                                                        <i class="fas fa-star"></i>
                                                    @else
                                                        <i class="far fa-star"></i>
                                                    @endif
                                                @endfor
                                            </div>
                                            <div class="review-comment">
                                                <p>{{ $review->comment }}</p>
                                            </div>
                                            @if($review->is_verified_purchase)
                                                <div class="verified-badge">
                                                    <i class="fas fa-check-circle"></i>
                                                    {{ __('app.verified_purchase') }}
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                                <div class="pagination-wrapper">
                                    {{ $myReviews->links() }}
                                </div>
                            @else
                                <div class="empty-state">
                                    <i class="fas fa-star"></i>
                                    <h4>{{ __('app.no_reviews_yet') }}</h4>
                                    <p>{{ __('app.write_first_review') }}</p>
                                    <a href="{{ route('all_products') }}" class="btn btn-primary">
                                        {{ __('app.browse_products') }}
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Custom CSS for Dashboard -->
<style>
.dashboard-layout {
    min-height: 100vh;
    background-color: #f8f9fa;
}

/* Tab Navigation */
.dashboard-tabs {
    background: white;
    border-bottom: 1px solid #e9ecef;
    position: sticky;
    top: 0;
    z-index: 100;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.tab-nav-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
}

.tab-nav {
    display: flex;
    list-style: none;
    margin: 0;
    padding: 0;
    gap: 0;
}

.tab-item {
    flex: 1;
}

.tab-link {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 1.5rem 1rem;
    color: #6c757d;
    text-decoration: none;
    border-bottom: 3px solid transparent;
    transition: all 0.3s ease;
    font-weight: 500;
    position: relative;
}

.tab-link:hover {
    color: #aa6969;
    background: #f8f9fa;
}

.tab-item.active .tab-link {
    color: #aa6969;
    border-bottom-color: #aa6969;
    background: #f8f9fa;
}

.tab-link i {
    font-size: 1rem;
}

.tab-link span {
    font-size: 0.9rem;
}

/* Main Content */
.dashboard-main {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0;
}

.dashboard-content {
    padding: 2rem;
}

.tab-content {
    display: none;
}

.tab-content.active {
    display: block;
}

/* Welcome Section */
.welcome-section {
    margin-bottom: 2rem;
}

.welcome-card h3 {
    margin: 0 0 0.5rem 0;
    font-size: 1.5rem;
    font-weight: 600;
}

.welcome-card p {
    margin: 0;
    opacity: 0.9;
}

/* Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: white;
    padding: 1.5rem;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    background: linear-gradient(135deg, #aa6969 0%, #aa6969 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
}

/* Order Status Specific Styling */
.status-pending-card .stat-icon {
    background: linear-gradient(135deg, #ffc107 0%, #ff8f00 100%);
}

.status-processing-card .stat-icon {
    background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
}

.status-completed-card .stat-icon {
    background: linear-gradient(135deg, #aa6969 0%, #1e7e34 100%);
}

.status-cancelled-card .stat-icon {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
}

.pending-icon {
    background: linear-gradient(135deg, #ffc107 0%, #ff8f00 100%);
}

.processing-icon {
    background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
}

.completed-icon {
    background: linear-gradient(135deg, #aa6969 0%, #1e7e34 100%);
}

.cancelled-icon {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
}

.status-pending-card .stat-content h4 {
    color: #ffc107;
}

.status-processing-card .stat-content h4 {
    color: #17a2b8;
}

.status-completed-card .stat-content h4 {
    color: #aa6969;
}

.status-cancelled-card .stat-content h4 {
    color: #dc3545;
}

.stat-content h4 {
    margin: 0;
    font-size: 2rem;
    font-weight: 700;
    color: #aa6969;
}

.stat-content p {
    margin: 0;
    color: #6c757d;
    font-size: 0.9rem;
}

/* Section Cards */
.section-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    margin-bottom: 2rem;
    overflow: hidden;
}

.section-header {
    padding: 1.5rem 2rem;
    border-bottom: 1px solid #e9ecef;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.section-header h3 {
    margin: 0;
    color: #aa6969;
    font-weight: 600;
}

.view-all-btn {
    color: #aa6969;
    text-decoration: none;
    font-size: 0.9rem;
    font-weight: 500;
}

.view-all-btn:hover {
    text-decoration: underline;
}

/* Orders List */
.orders-list {
    padding: 1rem 2rem 2rem 2rem;
}

.order-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1rem 0;
    border-bottom: 1px solid #f1f3f4;
}

.order-item:last-child {
    border-bottom: none;
}

.order-info {
    flex: 1;
}

.order-id {
    font-weight: 600;
    color: #aa6969;
    margin-bottom: 0.25rem;
}

.order-date {
    color: #6c757d;
    font-size: 0.9rem;
}

.order-status {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
    text-transform: uppercase;
}

.status-pending {
    background-color: #fff3cd;
    color: #856404;
}

.status-processing {
    background-color: #d4edda;
    color: #155724;
}

.status-completed {
    background-color: #d1ecf1;
    color: #0c5460;
}

.status-cancelled {
    background-color: #f8d7da;
    color: #721c24;
}

.order-total {
    font-weight: 600;
    color: #aa6969;
}

.order-actions {
    margin-left: 1rem;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 3rem 2rem;
    color: #6c757d;
}

.empty-state i {
    font-size: 3rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

.empty-state h4 {
    margin: 0 0 0.5rem 0;
    color: #aa6969;
}

.empty-state p {
    margin: 0 0 1.5rem 0;
}

/* Quick Actions */
.quick-actions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    padding: 1.5rem 2rem 2rem 2rem;
}

.action-card {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.75rem;
    padding: 2rem 1rem;
    background: #f8f9fa;
    border: 2px solid #e9ecef;
    border-radius: 12px;
    text-decoration: none;
    color: #aa6969;
    transition: all 0.3s ease;
    text-align: center;
}

.action-card:hover {
    background: #aa6969;
    color: white;
    border-color: #aa6969;
    transform: translateY(-2px);
}

.action-card i {
    font-size: 2rem;
}

/* Buttons */
.btn {
    display: inline-block;
    padding: 0.5rem 1rem;
    border: none;
    border-radius: 6px;
    text-decoration: none;
    font-size: 0.9rem;
    font-weight: 500;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-primary {
    background: #aa6969;
    color: white;
}

.btn-primary:hover {
    background: #aa6969db;
}

.btn-outline {
    background: transparent;
    color: #aa6969;
    border: 1px solid #aa6969;
}

.btn-outline:hover {
    background: #aa6969;
    color: white;
}

.btn-sm {
    padding: 0.375rem 0.75rem;
    font-size: 0.8rem;
}

/* AI Recommendation Section */
.ai-recommendation-section {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border: 1px solid #dee2e6;
}

.ai-chat-container {
    padding: 1.5rem;
    background: white;
    border-radius: 8px;
    margin: 1rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

.ai-chat-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #e9ecef;
}

.ai-avatar {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #aa6969 0%, #8b5a5a 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    flex-shrink: 0;
}

.ai-info h4 {
    margin: 0;
    color: #aa6969;
    font-weight: 600;
}

.ai-info p {
    margin: 0.25rem 0 0 0;
    color: #6c757d;
    font-size: 0.9rem;
}

.ai-chat-messages {
    min-height: 150px;
    max-height: 250px;
    overflow-y: auto;
    margin-bottom: 1rem;
    padding: 0.5rem;
    background: #f8f9fa;
    border-radius: 8px;
    border: 1px solid #e9ecef;
}

.ai-message {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    margin-bottom: 1rem;
    animation: fadeIn 0.3s ease;
}

.ai-message .ai-avatar {
    width: 32px;
    height: 32px;
    font-size: 1rem;
}

.ai-message-content {
    background: white;
    padding: 0.75rem 1rem;
    border-radius: 12px 12px 12px 0;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    max-width: 80%;
    position: relative;
}

.ai-message-content::after {
    content: '';
    position: absolute;
    left: -8px;
    top: 12px;
    width: 0;
    height: 0;
    border-top: 8px solid transparent;
    border-bottom: 8px solid transparent;
    border-right: 8px solid white;
}

.typing-indicator {
    display: flex;
    gap: 0.25rem;
}

.typing-indicator span {
    width: 8px;
    height: 8px;
    background: #aa6969;
    border-radius: 50%;
    display: inline-block;
    animation: typing 1.4s infinite ease-in-out;
}

.typing-indicator span:nth-child(1) {
    animation-delay: 0s;
}

.typing-indicator span:nth-child(2) {
    animation-delay: 0.2s;
}

.typing-indicator span:nth-child(3) {
    animation-delay: 0.4s;
}

@keyframes typing {
    0%, 60%, 100% {
        transform: translateY(0);
    }
    30% {
        transform: translateY(-5px);
    }
}

.ai-chat-input {
    display: flex;
    gap: 0.5rem;
    margin-top: 1rem;
}

.ai-chat-input input {
    flex: 1;
    padding: 0.75rem 1rem;
    border: 1px solid #ddd;
    border-radius: 20px;
    font-size: 0.95rem;
    transition: all 0.3s ease;
}

.ai-chat-input input:focus {
    outline: none;
    border-color: #aa6969;
    box-shadow: 0 0 0 2px rgba(170, 105, 105, 0.1);
}

.ai-chat-input button {
    width: 40px;
    height: 40px;
    background: #aa6969;
    color: white;
    border: none;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.ai-chat-input button:hover {
    background: #8b5a5a;
    transform: scale(1.05);
}

.recommended-products {
    margin-top: 1.5rem;
    padding: 1rem;
    background: white;
    border-radius: 8px;
    border: 1px solid #e9ecef;
}

.products-loading {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 1rem;
    padding: 2rem;
    color: #6c757d;
}

.spinner {
    width: 40px;
    height: 40px;
    border: 4px solid #f3f3f3;
    border-top: 4px solid #aa6969;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.recommended-products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-top: 1rem;
}

.product-card {
    background: white;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 1rem;
    transition: all 0.3s ease;
    cursor: pointer;
}

.product-card:hover {
    border-color: #aa6969;
    box-shadow: 0 2px 8px rgba(170, 105, 105, 0.1);
    transform: translateY(-2px);
}

.product-card-image {
    width: 100%;
    height: 120px;
    border-radius: 6px;
    overflow: hidden;
    margin-bottom: 0.75rem;
    background: #f8f9fa;
}

.product-card-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.product-card:hover .product-card-image img {
    transform: scale(1.05);
}

.product-card-info h5 {
    margin: 0 0 0.25rem 0;
    font-size: 0.85rem;
    color: #6c757d;
    font-weight: 500;
}

.product-card-info h4 {
    margin: 0 0 0.5rem 0;
    font-size: 0.95rem;
    color: #aa6969;
    font-weight: 600;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.product-card-price {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-top: 0.5rem;
}

.product-card-price .current-price {
    font-weight: 600;
    color: #aa6969;
    font-size: 1rem;
}

.product-card-price .original-price {
    text-decoration: line-through;
    color: #6c757d;
    font-size: 0.8rem;
}

.product-card-price .sale-badge {
    background: #ffc107;
    color: #856404;
    padding: 0.15rem 0.3rem;
            right: 8px;
        }
    }

    .alert {
        padding: 1rem;
        border-radius: 6px;
        margin-bottom: 1.5rem;
    }

    .alert-success {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    border: 1px solid #c3e6cb;
}

.alert-danger {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.alert-info {
    background: #d1ecf1;
    color: #0c5460;
    border: 1px solid #bee5eb;
}

.alert ul {
    margin: 0;
    padding-left: 1.5rem;
}

/* Register Design Classes Integration */
.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 15px;
}

.login__content {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    margin: 2rem 0;
    overflow: hidden;
}

.login__form {
    padding: 2rem;
}

.checkout__form__contact__title {
    margin-bottom: 2rem;
    text-align: center;
}

.checkout-title {
    margin: 0 0 0.5rem 0;
    color: #aa6969;
    font-weight: 600;
    font-size: 1.5rem;
}

.checkout__form__contact__title p {

        .checkout-title {
            margin: 0 0 0.5rem 0;
            color: #aa6969;
            font-weight: 600;
            font-size: 1.5rem;
        }

        .checkout__form__contact__title p {
            margin: 0;
            color: #6c757d;
            font-size: 1rem;
        }

        .input-validator {
            margin-bottom: 1.5rem;
        }

        .input-validator label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #aa6969;
            font-size: 0.9rem;
        }

        .input-validator input,
        .input-validator textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
            background: white;
        }

        .input-validator input:focus,
        .input-validator textarea:focus {
            outline: none;
            border-color: #aa6969;
            box-shadow: 0 0 0 2px rgba(102, 126, 234, 0.1);
        }

        .input-validator textarea {
            resize: vertical;
            min-height: 100px;
        }

        .btn.-dark {
            background: #aa6969;
            color: white;
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
        }

        .btn.-dark:hover {
            background: #34495e;
            transform: translateY(-1px);
        }

        .password-toggle {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #6c757d;
            padding: 5px;
            z-index: 1;
        }

        .password-toggle:hover {
            color: #aa6969;
        }

        .input-validator {
            position: relative;
        }

        /* Table Styles */
        .cart-wrap {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        .cart-wrap th,
        .cart-wrap td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #e9ecef;
        }

        .cart-wrap th {
            background: #f8f9fa;
            font-weight: 600;
            color: #aa6969;
        }

        .cart-wrap tbody tr:hover {
            background: #f8f9fa;
        }

        /* Status Badges */
        .stock {
            background: #fff3cd;
            color: #856404;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .pro {
            background: #d4edda;
            color: #155724;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .Del {
            background: #d1ecf1;
            color: #0c5460;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .can {
            background: #f8d7da;
            color: #721c24;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        /* Wishlist Table Styles */
        .wishlist-count {
            background: #aa6969;
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .wishlist-table-wrapper {
            overflow-x: auto;
        }

        .wishlist-table {
            width: 100%;
            border-collapse: collapse;
        }

        .wishlist-table th,
        .wishlist-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #e9ecef;
        }

        .wishlist-table th {
            background: #f8f9fa;
            font-weight: 600;
            color: #aa6969;
            font-size: 0.9rem;
        }

        .wishlist-item:hover {
            background: #f8f9fa;
        }

        .wishlist-product-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .product-image {
            width: 60px;
            height: 60px;
            border-radius: 8px;
            overflow: hidden;
            flex-shrink: 0;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .product-details h5 {
            margin: 0 0 0.25rem 0;
            font-size: 0.8rem;
            color: #6c757d;
            font-weight: 500;
            text-transform: uppercase;
        }

        .product-name {
            color: #aa6969;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
        }

        .product-name:hover {
            color: #8b5a5a;
            text-decoration: underline;
        }

        .product-price {
            font-weight: 600;
            color: #aa6969;
            font-size: 1rem;
        }

        .stock-status .in-stock {
            background: #d4edda;
            color: #155724;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .stock-status .out-of-stock {
            background: #f8d7da;
            color: #721c24;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .wishlist-actions {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        .wishlist-actions .btn {
            padding: 0.375rem 0.75rem;
            font-size: 0.8rem;
            border-radius: 4px;
            border: 1px solid #aa6969;
            background: transparent;
            color: #aa6969;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .wishlist-actions .btn:hover {
            background: #aa6969;
            color: white;
        }

        .wishlist-actions .btn.btn-primary {
            background: #aa6969;
            color: white;
            border-color: #aa6969;
        }

        .wishlist-actions .btn.btn-primary:hover {
            background: #8b5a5a;
            border-color: #8b5a5a;
        }

        .wishlist-actions .btn.btn-outline {
            padding: 0.375rem;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Reviews Tab Styles */
        .review-item {
            background: #fff;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            transition: box-shadow 0.3s ease;
        }

        .review-item:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .review-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .review-product-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .review-product-info a {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            color: #333;
            transition: color 0.3s ease;
        }

        .review-product-info a:hover {
            color: #aa6969;
        }

        .review-product-image {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 6px;
            border: 1px solid #e9ecef;
        }

        .review-product-info span {
            font-weight: 500;
        }

        .review-date {
            color: #6c757d;
            font-size: 0.875rem;
        }

        .review-rating {
            color: #ffc107;
            margin-bottom: 0.75rem;
        }

        .review-rating i {
            margin-right: 2px;
        }

        .review-comment {
            color: #495057;
            line-height: 1.6;
        }

        .review-comment p {
            margin: 0;
        }
    }

    /* AI Recommendation Section */
    .ai-recommendation-section {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border: 1px solid #dee2e6;
    }

    .ai-chat-container {
        padding: 1.5rem;
        background: white;
        border-radius: 8px;
        margin: 1rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    .ai-chat-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #e9ecef;
    }

    .ai-avatar {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #aa6969 0%, #8b5a5a 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
        flex-shrink: 0;
    }

    .ai-info h4 {
        margin: 0;
        color: #aa6969;
        font-weight: 600;
    }

    .ai-info p {
        margin: 0.25rem 0 0 0;
        color: #6c757d;
        font-size: 0.9rem;
    }

    .ai-chat-messages {
        min-height: 150px;
        max-height: 250px;
        overflow-y: auto;
        margin-bottom: 1rem;
        padding: 0.5rem;
        background: #f8f9fa;
        border-radius: 8px;
        border: 1px solid #e9ecef;
    }

    .ai-message {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        margin-bottom: 1rem;
        animation: fadeIn 0.3s ease;
    }

    .ai-message .ai-avatar {
        width: 32px;
        height: 32px;
        font-size: 1rem;
    }

    .ai-message-content {
        background: white;
        padding: 0.75rem 1rem;
        border-radius: 12px 12px 12px 0;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        max-width: 80%;
        position: relative;
    }

    .ai-message-content::after {
        content: '';
        position: absolute;
        left: -8px;
        top: 12px;
        width: 0;
        height: 0;
        border-top: 8px solid transparent;
        border-bottom: 8px solid transparent;
        border-right: 8px solid white;
    }

    .typing-indicator {
        display: flex;
        gap: 0.25rem;
    }

    .typing-indicator span {
        width: 8px;
        height: 8px;
        background: #aa6969;
        border-radius: 50%;
        display: inline-block;
        animation: typing 1.4s infinite ease-in-out;
    }

    .typing-indicator span:nth-child(1) {
        animation-delay: 0s;
    }

    .typing-indicator span:nth-child(2) {
        animation-delay: 0.2s;
    }

    .typing-indicator span:nth-child(3) {
        animation-delay: 0.4s;
    }

    @keyframes typing {
        0%, 60%, 100% {
            transform: translateY(0);
        }
        30% {
            transform: translateY(-5px);
        }
    }

    .ai-chat-input {
        display: flex;
        gap: 0.5rem;
        margin-top: 1rem;
    }

    .ai-chat-input input {
        flex: 1;
        padding: 0.75rem 1rem;
        border: 1px solid #ddd;
        border-radius: 20px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }

    .ai-chat-input input:focus {
        outline: none;
        border-color: #aa6969;
        box-shadow: 0 0 0 2px rgba(170, 105, 105, 0.1);
    }

    .ai-chat-input button {
        width: 40px;
        height: 40px;
        background: #aa6969;
        color: white;
        border: none;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .ai-chat-input button:hover {
        background: #8b5a5a;
        transform: scale(1.05);
    }

    .recommended-products {
        margin-top: 1.5rem;
        padding: 1rem;
        background: white;
        border-radius: 8px;
        border: 1px solid #e9ecef;
    }

    .products-loading {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 1rem;
        padding: 2rem;
        color: #6c757d;
    }

    .spinner {
        width: 40px;
        height: 40px;
        border: 4px solid #f3f3f3;
        border-top: 4px solid #aa6969;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .recommended-products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-top: 1rem;
    }

    .product-card {
        background: white;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 1rem;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .product-card:hover {
        border-color: #aa6969;
        box-shadow: 0 2px 8px rgba(170, 105, 105, 0.1);
        transform: translateY(-2px);
    }

    .product-card-image {
        width: 100%;
        height: 120px;
        border-radius: 6px;
        overflow: hidden;
        margin-bottom: 0.75rem;
        background: #f8f9fa;
    }

    .product-card-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .product-card:hover .product-card-image img {
        transform: scale(1.05);
    }

    .product-card-info h5 {
        margin: 0 0 0.25rem 0;
        font-size: 0.85rem;
        color: #6c757d;
        font-weight: 500;
    }

    .product-card-info h4 {
        margin: 0 0 0.5rem 0;
        font-size: 0.95rem;
        color: #aa6969;
        font-weight: 600;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .product-card-price {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-top: 0.5rem;
    }

    .product-card-price .current-price {
        font-weight: 600;
        color: #aa6969;
        font-size: 1rem;
    }

    .product-card-price .original-price {
        text-decoration: line-through;
        color: #6c757d;
        font-size: 0.8rem;
    }

    .product-card-price .sale-badge {
        background: #ffc107;
        color: #856404;
        padding: 0.15rem 0.3rem;
        border-radius: 4px;
        font-size: 0.7rem;
        font-weight: 500;
    }

    .add-to-cart-btn-small {
        width: 100%;
        padding: 0.5rem 0;
        background: #aa6969;
        color: white;
        border: none;
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-top: 0.75rem;
    }

    .add-to-cart-btn-small:hover {
        background: #8b5a5a;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Responsive AI Section */
    @media (max-width: 768px) {
        .ai-chat-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }

        .ai-chat-messages {
            min-height: 120px;
            max-height: 200px;
        }

        .recommended-products-grid {
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 0.75rem;
        }
    }

    /* User message styling */
    .user-message {
        display: flex;
        justify-content: flex-end;
        margin-bottom: 1rem;
        animation: fadeIn 0.3s ease;
    }

    .user-message-content {
        background: #aa6969;
        color: white;
        padding: 0.75rem 1rem;
        border-radius: 12px 12px 0 12px;
        max-width: 80%;
        position: relative;
    }

    .user-message-content::after {
        content: '';
        position: absolute;
        right: -8px;
        top: 12px;
        width: 0;
        height: 0;
        border-top: 8px solid transparent;
        border-bottom: 8px solid transparent;
        border-left: 8px solid #aa6969;
    }

            /* AI Recommendation Section */
            .ai-recommendation-section {
                background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
                border: 1px solid #dee2e6;
            }

            .ai-chat-container {
                padding: 1.5rem;
                background: white;
                border-radius: 8px;
                margin: 1rem;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            }

            .ai-chat-header {
                display: flex;
                align-items: center;
                gap: 1rem;
                margin-bottom: 1.5rem;
                padding-bottom: 1rem;
                border-bottom: 1px solid #e9ecef;
            }

            .ai-avatar {
                width: 50px;
                height: 50px;
                background: linear-gradient(135deg, #aa6969 0%, #8b5a5a 100%);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-size: 1.5rem;
                flex-shrink: 0;
            }

            .ai-info h4 {
                margin: 0;
                color: #aa6969;
                font-weight: 600;
            }

            .ai-info p {
                margin: 0.25rem 0 0 0;
                color: #6c757d;
                font-size: 0.9rem;
            }

            .ai-chat-messages {
                min-height: 150px;
                max-height: 250px;
                overflow-y: auto;
                margin-bottom: 1rem;
                padding: 0.5rem;
                background: #f8f9fa;
                border-radius: 8px;
                border: 1px solid #e9ecef;
            }

            .ai-message {
                display: flex;
                align-items: flex-start;
                gap: 0.75rem;
                margin-bottom: 1rem;
                animation: fadeIn 0.3s ease;
            }

            .ai-message .ai-avatar {
                width: 32px;
                height: 32px;
                font-size: 1rem;
            }

            .ai-message-content {
                background: white;
                padding: 0.75rem 1rem;
                border-radius: 12px 12px 12px 0;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
                max-width: 80%;
                position: relative;
            }

            .ai-message-content::after {
                content: '';
                position: absolute;
                left: -8px;
                top: 12px;
                width: 0;
                height: 0;
                border-top: 8px solid transparent;
                border-bottom: 8px solid transparent;
                border-right: 8px solid white;
            }

            .typing-indicator {
                display: flex;
                gap: 0.25rem;
            }

            .typing-indicator span {
                width: 8px;
                height: 8px;
                background: #aa6969;
                border-radius: 50%;
                display: inline-block;
                animation: typing 1.4s infinite ease-in-out;
            }

            .typing-indicator span:nth-child(1) {
                animation-delay: 0s;
            }

            .typing-indicator span:nth-child(2) {
                animation-delay: 0.2s;
            }

            .typing-indicator span:nth-child(3) {
                animation-delay: 0.4s;
            }

            @keyframes typing {

                0%,
                60%,
                100% {
                    transform: translateY(0);
                }

                30% {
                    transform: translateY(-5px);
                }
            }

            .ai-chat-input {
                display: flex;
                gap: 0.5rem;
                margin-top: 1rem;
            }

            .ai-chat-input input {
                flex: 1;
                padding: 0.75rem 1rem;
                border: 1px solid #ddd;
                border-radius: 20px;
                font-size: 0.95rem;
                transition: all 0.3s ease;
            }

            .ai-chat-input input:focus {
                outline: none;
                border-color: #aa6969;
                box-shadow: 0 0 0 2px rgba(170, 105, 105, 0.1);
            }

            .ai-chat-input button {
                width: 40px;
                height: 40px;
                background: #aa6969;
                color: white;
                border: none;
                border-radius: 50%;
                cursor: pointer;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: all 0.3s ease;
            }

            .ai-chat-input button:hover {
                background: #8b5a5a;
                transform: scale(1.05);
            }

            .recommended-products {
                margin-top: 1.5rem;
                padding: 1rem;
                background: white;
                border-radius: 8px;
                border: 1px solid #e9ecef;
            }

            .products-loading {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                gap: 1rem;
                padding: 2rem;
                color: #6c757d;
            }

            .spinner {
                width: 40px;
                height: 40px;
                border: 4px solid #f3f3f3;
                border-top: 4px solid #aa6969;
                border-radius: 50%;
                animation: spin 1s linear infinite;
            }

            @keyframes spin {
                0% {
                    transform: rotate(0deg);
                }

                100% {
                    transform: rotate(360deg);
                }
            }

            .recommended-products-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                gap: 1rem;
                margin-top: 1rem;
            }

            .product-card {
                background: white;
                border: 1px solid #e9ecef;
                border-radius: 8px;
                padding: 1rem;
                transition: all 0.3s ease;
                cursor: pointer;
            }

            .product-card:hover {
                border-color: #aa6969;
                box-shadow: 0 2px 8px rgba(170, 105, 105, 0.1);
                transform: translateY(-2px);
            }

            .product-card-image {
                width: 100%;
                height: 120px;
                border-radius: 6px;
                overflow: hidden;
                margin-bottom: 0.75rem;
                background: #f8f9fa;
            }

            .product-card-image img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                transition: transform 0.3s ease;
            }

            .product-card:hover .product-card-image img {
                transform: scale(1.05);
            }

            .product-card-info h5 {
                margin: 0 0 0.25rem 0;
                font-size: 0.85rem;
                color: #6c757d;
                font-weight: 500;
            }

            .product-card-info h4 {
                margin: 0 0 0.5rem 0;
                font-size: 0.95rem;
                color: #aa6969;
                font-weight: 600;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .product-card-price {
                display: flex;
                align-items: center;
                gap: 0.5rem;
                margin-top: 0.5rem;
            }

            .product-card-price .current-price {
                font-weight: 600;
                color: #aa6969;
                font-size: 1rem;
            }

            .product-card-price .original-price {
                text-decoration: line-through;
                color: #6c757d;
                font-size: 0.8rem;
            }

            .product-card-price .sale-badge {
                background: #ffc107;
                color: #856404;
                padding: 0.15rem 0.3rem;
                border-radius: 4px;
                font-size: 0.7rem;
                font-weight: 500;
            }

            .add-to-cart-btn-small {
                width: 100%;
                padding: 0.5rem 0;
                background: #aa6969;
                color: white;
                border: none;
                border-radius: 6px;
                font-size: 0.8rem;
                font-weight: 500;
                cursor: pointer;
                transition: all 0.3s ease;
                margin-top: 0.75rem;
            }

            .add-to-cart-btn-small:hover {
                background: #8b5a5a;
            }

            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(10px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            /* Responsive AI Section */

                // Check if we're on the dashboard tab
                if (window.location.hash === '#dashboard' || window.location.hash === '') {
                    loadAIRecommendations();
                }

                // Load recommendations when dashboard tab is activated
                $(document).on('click', '[data-tab="dashboard"]', function() {
     setTimeout(loadAIRecommendations, 500);
                });
        }

        // Load AI recommendations
        function loadAIRecommendations() {
            const chatMessages=$('#ai-chat-messages');
            const recommendedProducts=$('#recommended-products');

            // Show loading state
            chatMessages.html(` <div class="ai-message loading-message"> <div class="ai-avatar"> <i class="fas fa-robot"></i> </div> <div class="ai-message-content"> <div class="typing-indicator"> <span></span> <span></span> <span></span> </div> </div> </div> `);

            recommendedProducts.html(` <div class="products-loading"> <div class="spinner"></div> <p>{{ __('app.loading_recommendations') }}...</p> </div> `);

            // Fetch recommendations from API
            $.ajax({

                url: '/customer/ai-recommendations',
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success && response.recommendations.length > 0) {
                        displayRecommendations(response.recommendations);
                        displayAIMessage(response.recommendations);
                    }

                    else {
                        // No recommendations found
                        chatMessages.html(` <div class="ai-message"> <div class="ai-avatar"> <i class="fas fa-robot"></i> </div> <div class="ai-message-content"> <p>{{ __('app.no_recommendations_message') }}</p> </div> </div> `);

                        recommendedProducts.html(` <div class="empty-state"> <i class="fas fa-box-open"></i> <h4>{{ __('app.no_recommendations') }}</h4> <p>{{ __('app.browse_all_products_message') }}</p> <a href="{{ route('all_products') }}" class="btn btn-primary"> {{ __('app.browse_products') }} </a> </div> `);
                    }
                }

                ,
                error: function(xhr, status, error) {
                    console.error('Error loading recommendations:', error);

                    chatMessages.html(` <div class="ai-message"> <div class="ai-avatar"> <i class="fas fa-robot"></i> </div> <div class="ai-message-content"> <p>{{ __('app.error_loading_recommendations') }}</p> </div> </div> `);

                    recommendedProducts.html(` <div class="empty-state"> <i class="fas fa-exclamation-triangle"></i> <h4>{{ __('app.error_loading_recommendations') }}</h4> <button class="btn btn-outline" onclick="loadAIRecommendations()"> {{ __('app.try_again') }} </button> </div> `);
                }
            });

        // Set up chat input
        setupChatInput();
        }

        // Display AI message with recommendations
        function displayAIMessage(recommendations) {
            const locale = '{{ app()->getLocale() }}';
            const customerName = '{{ Auth::guard('customer')->user()->name }}';
            let message;

            if (locale=='ar') {
                message=`مرحباً $ {
                    customerName
                }

                ! بناءً على طلباتك السابقة، أقترح عليك هذه المنتجات الرائعة التي قد تعجبك:`;
            }

            else {
                message=`Hello $ {
                    customerName
                }

                ! Based on your previous orders,
                I recommend these great products you might like:`;
            }

            $('#ai-chat-messages').html(` <div class="ai-message"> <div class="ai-avatar"> <i class="fas fa-robot"></i> </div> <div class="ai-message-content"> <p>$ {
                    message
                }

                </p> </div> </div> `);
        }

        // Display recommended products
        function displayRecommendations(products) {
            let productsHtml = '<div class="recommended-products-grid">';

            products.forEach(product=> {
                    const productName = '{{ app()->getLocale() }}' === 'ar' ? (product.name_ar || product.name_en) : (product.name_en || product.name_ar);

                    const priceHtml=product.on_sale ? ` <span class="current-price">$ {
                        product.discounted_price
                    }

                    {{ __('app.lyd') }}</span> <span class="original-price">$ {
                        product.price
                    }

                    {{ __('app.lyd') }}</span> <span class="sale-badge">{{ __('app.sale') }}</span> ` : `$ {
                        product.price
                    }

                    {{ __('app.lyd') }}`;

                    productsHtml +=` <div class="product-card" data-product-id="${product.id}"> <div class="product-card-image"> <img src="${product.image}" alt="${productName}"> </div> <div class="product-card-info"> $ {
                        product.category ? `<h5>$ {
                            product.category.name
                        }

                        </h5>` : ''
                    }

                    <h4>$ {
                        productName
                    }

                    </h4> <div class="product-card-price"> $ {
                        priceHtml
                    }

                    </div> <button class="add-to-cart-btn-small" data-product-id="${product.id}"> {{ __('app.add_to_cart') }} </button> </div> </div> `;
                });

            productsHtml+='</div>';
            $('#recommended-products').html(productsHtml);

            // Add event listeners to product cards and add to cart buttons
            $('.product-card').on('click', function() {
                    const productId=$(this).data('product-id');
                    window.location.href = '{{ route('product/info', '') }}/' + productId;
                });

            $('.add-to-cart-btn-small').on('click', function(e) {
                    e.stopPropagation();
                    addProductToCart($(this).data('product-id'));
                });
        }

        // Add product to cart
        function addProductToCart(productId) {
            const button=$(`.add-to-cart-btn-small[data-product-id="${productId}"]`);
            button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');

            $.ajax({

                url: '{{ route('cart.store') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    product_id: productId,
                    quantity: 1
                }

                ,
                success: function(response) {
                    if (response.success) {

                        // Show success message
                        Swal.fire({
                            title: '{{ __('app.added') }}',
                            text: response.message || '{{ __('app.product_added_to_cart') }}',
                            icon: 'success',
                            timer: 2000,
                            timerProgressBar: true,
                            showConfirmButton: false
                        });

                    // Update button
                    button.html('<i class="fas fa-check"></i> {{ __('app.added') }}') .css('background', '#28a745') .prop('disabled', true);

                    // Update cart count if element exists
                    updateCartCount(response.cart_count);
                }

                else {
                    showAlert('error', response.message || '{{ __('app.error_adding_to_cart') }}');
                    button.prop('disabled', false).html('{{ __('app.add_to_cart') }}');
                }
            }

            ,
            error: function(xhr) {
                const response=xhr.responseJSON;
                showAlert('error', response.message || '{{ __('app.error_adding_to_cart') }}');
                button.prop('disabled', false).html('{{ __('app.add_to_cart') }}');
            }
        });
        }

        // Set up chat input functionality
        function setupChatInput() {
            const chatInput=$('#ai-chat-input');
            const sendButton=$('#ai-send-button');

            // Send message on button click
            sendButton.on('click', sendChatMessage);

            // Send message on Enter key
            chatInput.on('keypress', function(e) {
                    if (e.which===13) {
                        sendChatMessage();
                    }
                });

            function sendChatMessage() {
                const message=chatInput.val().trim();
                if (message === '') return;

                // Add user message to chat
                const chatMessages=$('#ai-chat-messages');

                chatMessages.append(` <div class="user-message"> <div class="user-message-content"> $ {
                        message
                    }

                    </div> </div> `);

                // Show AI typing indicator
                chatMessages.append(` <div class="ai-message loading-message"> <div class="ai-avatar"> <i class="fas fa-robot"></i> </div> <div class="ai-message-content"> <div class="typing-indicator"> <span></span> <span></span> <span></span> </div> </div> </div> `);

                // Scroll to bottom
                chatMessages.scrollTop(chatMessages[0].scrollHeight);

                // Clear input
                chatInput.val('');

                // Send to AI endpoint
                $.ajax({

                    url: '{{ route('customer.ai.chat') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        message: message
                    }

                    ,
                    success: function(response) {
                        // Remove typing indicator
                        $('.loading-message').remove();

                        if (response.success) {

                            // Add AI response
                            chatMessages.append(` <div class="ai-message"> <div class="ai-avatar"> <i class="fas fa-robot"></i> </div> <div class="ai-message-content"> <p>$ {
                                    response.message
                                }

                                </p> </div> </div> `);

                            // Update recommendations if provided
                            if (response.recommendations && response.recommendations.length > 0) {
                                displayRecommendations(response.recommendations);
                            }

                            // Scroll to bottom
                            chatMessages.scrollTop(chatMessages[0].scrollHeight);
                        }
                    }

                    ,
                    error: function(xhr) {
                        // Remove typing indicator
                        $('.loading-message').remove();

                        // Show error message
                        chatMessages.append(` <div class="ai-message"> <div class="ai-avatar"> <i class="fas fa-robot"></i> </div> <div class="ai-message-content"> <p>{{ __('app.error_processing_request') }}</p> </div> </div> `);

                        // Scroll to bottom
                        chatMessages.scrollTop(chatMessages[0].scrollHeight);
                    }
                });
        }
        }

        // Add user message styling
        $(document).ready(function() {
                // Initialize AI chat when dashboard loads
                initAIRecommendationChat();

                // Add CSS for user messages
                $('head').append(` <style> .user-message {
                        display: flex;
                        justify-content: flex-end;
                        margin-bottom: 1rem;
                        animation: fadeIn 0.3s ease;
                    }

                    .user-message-content {
                        background: #aa6969;
                        color: white;
                        padding: 0.75rem 1rem;
                        border-radius: 12px 12px 0 12px;
                        max-width: 80%;
                        position: relative;
                    }

                    .user-message-content::after {
                        content: '';
                        position: absolute;
                        right: -8px;
                        top: 12px;
                        width: 0;
                        height: 0;
                        border-top: 8px solid transparent;
                        border-bottom: 8px solid transparent;
                        border-left: 8px solid #aa6969;
                    }
    
  
    });
    @media (max-width: 768px) {
    .ai-chat-header {
    flex-direction: column;
    align-items: flex-start;
    gap: 0.5rem;
    }

    .ai-chat-messages {
    min-height: 120px;
    max-height: 200px;
    }

    .recommended-products-grid {
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 0.75rem;
    }
    }
    background: #aa6969;
    color: white;
    border-color: #aa6969;
    }

    .wishlist-actions .btn.btn-primary:hover {
    background: #8b5a5a;
    border-color: #8b5a5a;
    }

    .wishlist-actions .btn.btn-outline {
    padding: 0.375rem;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    }

    @media (max-width: 768px) {
    .wishlist-table {
    font-size: 0.9rem;
    }

    .wishlist-table th,
    .wishlist-table td {
    padding: 0.75rem 0.5rem;
    }

    .wishlist-product-info {
    gap: 0.5rem;
    }

    .product-image {
    width: 50px;
    height: 50px;
    }

    .wishlist-actions {
    flex-direction: column;
    gap: 0.25rem;
    }

    .wishlist-actions .btn {
    width: 100%;
    text-align: center;
    }
    }
    </style>

    <!-- Tab Functionality Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Tab switching functionality
            const tabLinks = document.querySelectorAll('.tab-link');
            const tabContents = document.querySelectorAll('.tab-content');

            // Function to switch tabs
            window.switchTab = function(tabName) {
                // Remove active class from all tabs and contents
                tabLinks.forEach(link => {
                    link.closest('.tab-item').classList.remove('active');
                });

                tabContents.forEach(content => {
                    content.classList.remove('active');
                });

                // Add active class to clicked tab and corresponding content
                const activeLink = document.querySelector(`[data-tab="${tabName}"]`);
                const activeContent = document.getElementById(`${tabName}-content`);

                if (activeLink && activeContent) {
                    activeLink.closest('.tab-item').classList.add('active');
                    activeContent.classList.add('active');
                }
            };

            // Add click event listeners to tab links
            tabLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const tabName = this.getAttribute('data-tab');
                    switchTab(tabName);
                });
            });

            // Handle browser back/forward buttons
            window.addEventListener('popstate', function(e) {
                if (e.state && e.state.tab) {
                    switchTab(e.state.tab);
                }
            });

            // Set initial tab from URL hash or default to dashboard
            const hash = window.location.hash.substring(1);
            const validTabs = ['dashboard', 'profile', 'orders', 'wishlist'];

            if (hash && validTabs.includes(hash)) {
                switchTab(hash);
            } else {
                switchTab('dashboard');
            }

            // Update URL without page refresh when switching tabs
            const originalSwitchTab = window.switchTab;
            window.switchTab = function(tabName) {
                originalSwitchTab(tabName);
                if (history.pushState) {
                    history.pushState({
                        tab: tabName
                    }, '', `#${tabName}`);
                }
            };

            // Initialize form validation and interactions
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    // Let Laravel handle password validation
                    // Remove client-side password matching check to allow proper server-side validation
                });
            });

            // Toggle switch interactions
            const toggles = document.querySelectorAll('.switch input');
            toggles.forEach(toggle => {
                toggle.addEventListener('change', function() {
                    console.log(`Toggle ${this.id} is now ${this.checked ? 'ON' : 'OFF'}`);
                    // Here you can add AJAX calls to save settings
                });
            });

            // Auto-hide alerts after 5 seconds
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.opacity = '0';
                    setTimeout(() => {
                        alert.remove();
                    }, 300);
                }, 5000);
            });

            // Mobile menu toggle (if needed)
            const mobileToggle = document.querySelector('.mobile-menu-toggle');
            const tabNav = document.querySelector('.tab-nav');

            if (mobileToggle && tabNav) {
                mobileToggle.addEventListener('click', function() {
                    tabNav.classList.toggle('mobile-open');
                });
            }

            // Smooth scroll to top when switching tabs
            function scrollToTop() {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            }

            // Override switchTab to include scroll to top
            const switchTabWithScroll = window.switchTab;
            window.switchTab = function(tabName) {
                switchTabWithScroll(tabName);
                scrollToTop();
            };

            // Password toggle functionality for profile form
            function initPasswordToggles() {
                // Add toggle buttons to password fields
                $('input[type="password"]').each(function() {
                    if (!$(this).next('.password-toggle').length) {
                        $(this).after(
                            '<button type="button" class="password-toggle"><i class="fas fa-eye"></i></button>'
                            );
                    }
                });

                // Handle toggle clicks
                $(document).on('click', '.password-toggle', function() {
                    const passwordField = $(this).prev('input[type="password"]');
                    const type = passwordField.attr('type') === 'password' ? 'text' : 'password';
                    passwordField.attr('type', type);
                    $(this).find('i').toggleClass('fa-eye fa-eye-slash');
                });
            }

            // Initialize password toggles when profile tab is shown
            $(document).ready(function() {
                initPasswordToggles();

                // Re-initialize when switching to profile tab
                $(document).on('click', '[data-tab="profile"]', function() {
                    setTimeout(initPasswordToggles, 100);
                });

                // Wishlist functionality
                initWishlistFunctionality();

                // Re-initialize wishlist when switching to wishlist tab
                $(document).on('click', '[data-tab="wishlist"]', function() {
                    setTimeout(initWishlistFunctionality, 100);
                });
            });

            // Wishlist functionality
            function initWishlistFunctionality() {
                // Add to cart from wishlist
                $('.add-to-cart-btn').off('click').on('click', function(e) {
                    e.preventDefault();
                    const productId = $(this).data('product-id');
                    const button = $(this);

                    // Disable button and show loading
                    button.prop('disabled', true).html(
                        '<i class="fas fa-spinner fa-spin"></i> {{ __('app.adding') }}');

                    $.ajax({
                        url: '{{ route('cart.store') }}',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            product_id: productId,
                            quantity: 1
                        },
                        success: function(response) {
                            if (response.success) {
                                // Update cart count
                                updateCartCount(response.cart_count);

                                // Show success message with SweetAlert
                                Swal.fire({
                                    title: '{{ __('app.added') }}',
                                    text: response.message ||
                                        '{{ __('app.product_added_to_cart') }}',
                                    icon: 'success',
                                    timer: 2000,
                                    timerProgressBar: true,
                                    showConfirmButton: false,
                                    customClass: {
                                        popup: 'animated fadeInDown'
                                    }
                                });

                                // Update button
                                button.html('{{ __('app.added') }}').addClass('btn-success')
                                    .removeClass('btn-primary');

                                // Refresh dashboard after 2 seconds
                                setTimeout(function() {
                                    location.reload();
                                }, 2000);
                            } else {
                                showAlert('error', response.message ||
                                    '{{ __('app.error_adding_to_cart') }}');
                                button.prop('disabled', false).html(
                                    '{{ __('app.add_to_cart') }}');
                            }
                        },
                        error: function(xhr) {
                            const response = xhr.responseJSON;
                            showAlert('error', response.message ||
                                '{{ __('app.error_adding_to_cart') }}');
                            button.prop('disabled', false).html(
                            '{{ __('app.add_to_cart') }}');
                        }
                    });
                });

                // Remove from wishlist
                $('.remove-from-wishlist-btn').off('click').on('click', function(e) {
                    e.preventDefault();
                    const productId = $(this).data('product-id');
                    const button = $(this);
                    const row = button.closest('.wishlist-item');

                    // Use SweetAlert for confirmation
                    Swal.fire({
                        title: '{{ __('app.confirm_remove_from_wishlist') }}',
                        text: '{{ __('app.action_cannot_be_undone') }}',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#aa6969',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: '{{ __('app.yes_remove') }}',
                        cancelButtonText: '{{ __('app.cancel') }}',
                        customClass: {
                            popup: 'animated fadeInDown'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            button.prop('disabled', true).html(
                                '<i class="fas fa-spinner fa-spin"></i>');

                            $.ajax({
                                url: '{{ route('customer.wishlist.remove') }}',
                                method: 'DELETE',
                                data: {
                                    _token: '{{ csrf_token() }}',
                                    product_id: productId
                                },
                                success: function(response) {
                                    if (response.success) {
                                        // Remove row with animation
                                        row.fadeOut(300, function() {
                                            $(this).remove();

                                            // Update wishlist count
                                            updateWishlistCount(response
                                                .wishlist_count);

                                            // Check if wishlist is empty
                                            if ($('.wishlist-item').length ===
                                                0) {
                                                location.reload();
                                            }
                                        });

                                        // Show success message with SweetAlert
                                        Swal.fire({
                                            title: '{{ __('app.removed') }}',
                                            text: response.message ||
                                                '{{ __('app.product_removed_from_wishlist') }}',
                                            icon: 'success',
                                            timer: 2000,
                                            timerProgressBar: true,
                                            showConfirmButton: false,
                                            customClass: {
                                                popup: 'animated fadeInDown'
                                            }
                                        });
                                    } else {
                                        showAlert('error', response.message ||
                                            '{{ __('app.error_removing_from_wishlist') }}'
                                            );
                                        button.prop('disabled', false).html(
                                            '<i class="fas fa-times"></i>');
                                    }
                                },
                                error: function(xhr) {
                                    const response = xhr.responseJSON;
                                    showAlert('error', response.message ||
                                        '{{ __('app.error_removing_from_wishlist') }}'
                                        );
                                    button.prop('disabled', false).html(
                                        '<i class="fas fa-times"></i>');
                                }
                            });
                        }
                    });
                });
            }

            // Update cart count
            function updateCartCount(count) {
                const cartCountElements = $('.cart__quantity, .cart-count');
                cartCountElements.text(count);
            }

            // Update wishlist count
            function updateWishlistCount(count) {
                $('.wishlist-count').text(count + ' {{ __('app.items') }}');
                $('.stat-content h4').eq(1).text(count); // Update the wishlist stat card
            }

            // Show alert message
            function showAlert(type, message) {
                const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
                const alertHtml = `
            <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;

                // Remove existing alerts
                $('.alert').remove();

                // Add new alert at the top of the content
                $('.dashboard-content').prepend(alertHtml);

                // Auto-hide after 5 seconds
                setTimeout(function() {
                    $('.alert').fadeOut();
                }, 5000);
            }
        });
    </script>
    <!-- AI Recommendation JavaScript -->
    <script>
        // Initialize AI Recommendation Chat
        function initAIRecommendationChat() {
            // Check if we're on the dashboard tab
            if (window.location.hash === '#dashboard' || window.location.hash === '') {
                loadAIRecommendations();
            }

            // Load recommendations when dashboard tab is activated
            $(document).on('click', '[data-tab="dashboard"]', function() {
                setTimeout(loadAIRecommendations, 500);
            });
        }

        // Load AI recommendations
        function loadAIRecommendations() {
            const chatMessages = $('#ai-chat-messages');
            const recommendedProducts = $('#recommended-products');

            // Show loading state
            chatMessages.html(`<div class="ai-message loading-message">
                <div class="ai-avatar">
                    <i class="fas fa-robot"></i>
                </div>
                <div class="ai-message-content">
                    <div class="typing-indicator">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </div>
            </div>`);

            recommendedProducts.html(`<div class="products-loading">
                <div class="spinner"></div>
                <p>{{ __('app.loading_recommendations') }}...</p>
            </div>`);

            // Fetch recommendations from API
            $.ajax({
                url: '{{ route('customer.ai.recommendations') }}',
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success && response.recommendations.length > 0) {
                        displayRecommendations(response.recommendations);
                        displayAIMessage(response.recommendations);
                    } else {
                        // No recommendations found
                        chatMessages.html(`<div class="ai-message">
                            <div class="ai-avatar">
                                <i class="fas fa-robot"></i>
                            </div>
                            <div class="ai-message-content">
                                <p>{{ __('app.no_recommendations_message') }}</p>
                            </div>
                        </div>`);

                        recommendedProducts.html(`<div class="empty-state">
                            <i class="fas fa-box-open"></i>
                            <h4>{{ __('app.no_recommendations') }}</h4>
                            <p>{{ __('app.browse_all_products_message') }}</p>
                            <a href="{{ route('all_products') }}" class="btn btn-primary">
                                {{ __('app.browse_products') }}
                            </a>
                        </div>`);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading recommendations:', error);

                    chatMessages.html(`<div class="ai-message">
                        <div class="ai-avatar">
                            <i class="fas fa-robot"></i>
                        </div>
                        <div class="ai-message-content">
                            <p>{{ __('app.error_loading_recommendations') }}</p>
                        </div>
                    </div>`);

                    recommendedProducts.html(`<div class="empty-state">
                        <i class="fas fa-exclamation-triangle"></i>
                        <h4>{{ __('app.error_loading_recommendations') }}</h4>
                        <button class="btn btn-outline" onclick="loadAIRecommendations()">
                            {{ __('app.try_again') }}
                        </button>
                    </div>`);
                }
            });
        }

        // Display AI message with recommendations
        function displayAIMessage(recommendations) {
            const locale = '{{ app()->getLocale() }}';
            const customerName = '{{ Auth::guard('customer')->user()->name }}';
            let message;

            if (locale == 'ar') {
                message = `مرحباً ${customerName}! بناءً على طلباتك السابقة، أقترح عليك هذه المنتجات الرائعة التي قد تعجبك:`;
            } else {
                message = `Hello ${customerName}! Based on your previous orders, I recommend these great products you might like:`;
            }

            $('#ai-chat-messages').html(`<div class="ai-message">
                <div class="ai-avatar">
                    <i class="fas fa-robot"></i>
                </div>
                <div class="ai-message-content">
                    <p>${message}</p>
                </div>
            </div>`);
        }

        // Display recommended products
        function displayRecommendations(products) {
            let productsHtml = '<div class="recommended-products-grid">';

            products.forEach(product => {
                const productName = '{{ app()->getLocale() }}' === 'ar' ? (product.name_ar || product.name_en) : (product.name_en || product.name_ar);

                const priceHtml = product.on_sale ?
                    `<span class="current-price">${product.discounted_price} {{ __('app.lyd') }}</span>
                     <span class="original-price">${product.price} {{ __('app.lyd') }}</span>
                     <span class="sale-badge">{{ __('app.sale') }}</span>` :
                    `${product.price} {{ __('app.lyd') }}`;

                productsHtml += `<div class="product-card" data-product-id="${product.id}">
                    <div class="product-card-image">
                        <img src="${product.image}" alt="${productName}">
                    </div>
                    <div class="product-card-info">
                        ${product.category ? `<h5>${product.category.name}</h5>` : ''}
                        <h4>${productName}</h4>
                        <div class="product-card-price">
                            ${priceHtml}
                        </div>
                        <button class="add-to-cart-btn-small" data-product-id="${product.id}">
                            {{ __('app.add_to_cart') }}
                        </button>
                    </div>
                </div>`;
            });

            productsHtml += '</div>';
            $('#recommended-products').html(productsHtml);

            // Add event listeners to product cards and add to cart buttons
            $('.product-card').on('click', function() {
                const productId = $(this).data('product-id');
                window.location.href = '{{ route('product/info', '') }}/' + productId;
            });

            $('.add-to-cart-btn-small').on('click', function(e) {
                e.stopPropagation();
                addProductToCart($(this).data('product-id'));
            });
        }

        // Add product to cart
        function addProductToCart(productId) {
            const button = $(`.add-to-cart-btn-small[data-product-id="${productId}"]`);
            button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');

            $.ajax({
                url: '{{ route('cart.store') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    product_id: productId,
                    quantity: 1
                },
                success: function(response) {
                    if (response.success) {
                        // Show success message
                        Swal.fire({
                            title: '{{ __('app.added') }}',
                            text: response.message || '{{ __('app.product_added_to_cart') }}',
                            icon: 'success',
                            timer: 2000,
                            timerProgressBar: true,
                            showConfirmButton: false
                        });

                        // Update button
                        button.html('<i class="fas fa-check"></i> {{ __('app.added') }}')
                            .css('background', '#28a745')
                            .prop('disabled', true);

                        // Update cart count if element exists
                        updateCartCount(response.cart_count);
                    } else {
                        showAlert('error', response.message || '{{ __('app.error_adding_to_cart') }}');
                        button.prop('disabled', false).html('{{ __('app.add_to_cart') }}');
                    }
                },
                error: function(xhr) {
                    const response = xhr.responseJSON;
                    showAlert('error', response.message || '{{ __('app.error_adding_to_cart') }}');
                    button.prop('disabled', false).html('{{ __('app.add_to_cart') }}');
                }
            });
        }

        // Set up chat input functionality
        function setupChatInput() {
            const chatInput = $('#ai-chat-input');
            const sendButton = $('#ai-send-button');

            // Send message on button click
            sendButton.on('click', sendChatMessage);

            // Send message on Enter key
            chatInput.on('keypress', function(e) {
                if (e.which === 13) {
                    sendChatMessage();
                }
            });

            function sendChatMessage() {
                const message = chatInput.val().trim();
                if (message === '') return;

                // Add user message to chat
                const chatMessages = $('#ai-chat-messages');

                chatMessages.append(`<div class="user-message">
                    <div class="user-message-content">
                        ${message}
                    </div>
                </div>`);

                // Show AI typing indicator
                chatMessages.append(`<div class="ai-message loading-message">
                    <div class="ai-avatar">
                        <i class="fas fa-robot"></i>
                    </div>
                    <div class="ai-message-content">
                        <div class="typing-indicator">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </div>
                </div>`);

                // Scroll to bottom
                chatMessages.scrollTop(chatMessages[0].scrollHeight);

                // Clear input
                chatInput.val('');

                // Send to AI endpoint
                $.ajax({
                    url: '{{ route('customer.ai.chat') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        message: message
                    },
                    success: function(response) {
                        // Remove typing indicator
                        $('.loading-message').remove();

                        if (response.success) {
                            // Add AI response
                            chatMessages.append(`<div class="ai-message">
                                <div class="ai-avatar">
                                    <i class="fas fa-robot"></i>
                                </div>
                                <div class="ai-message-content">
                                    <p>${response.message}</p>
                                </div>
                            </div>`);

                            // Update recommendations if provided
                            if (response.recommendations && response.recommendations.length > 0) {
                                displayRecommendations(response.recommendations);
                            }

                            // Scroll to bottom
                            chatMessages.scrollTop(chatMessages[0].scrollHeight);
                        }
                    },
                    error: function(xhr) {
                        // Remove typing indicator
                        $('.loading-message').remove();

                        // Show error message
                        chatMessages.append(`<div class="ai-message">
                            <div class="ai-avatar">
                                <i class="fas fa-robot"></i>
                            </div>
                            <div class="ai-message-content">
                                <p>{{ __('app.error_processing_request') }}</p>
                            </div>
                        </div>`);

                        // Scroll to bottom
                        chatMessages.scrollTop(chatMessages[0].scrollHeight);
                    }
                });
            }
        }

        // Initialize AI chat when page loads
        $(document).ready(function() {
            initAIRecommendationChat();
            setupChatInput();
        });
    </script>

    <!-- Ensure jQuery is loaded and available -->
    <script>
        // Check if jQuery is loaded, if not, load it dynamically
        if (typeof jQuery == 'undefined') {
            console.log('jQuery not found, loading it dynamically...');

            // Create script element
            var script = document.createElement('script');
            script.src = '/app/assets/js/jquery-3.5.1.min.js';
            script.type = 'text/javascript';
            script.onload = function() {
                console.log('jQuery loaded dynamically');
                // Re-initialize all jQuery-dependent functionality
                if (typeof initAIRecommendationChat === 'function') {
                    initAIRecommendationChat();
                }
                if (typeof setupChatInput === 'function') {
                    setupChatInput();
                }
                if (typeof initWishlistFunctionality === 'function') {
                    initWishlistFunctionality();
                }
            };

            // Add script to head
            document.head.appendChild(script);
        } else {
            console.log('jQuery is already loaded');
        }
    </script>
@endsection
