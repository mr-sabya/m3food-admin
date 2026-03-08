<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <a href="{{ route('home') }}" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{ url('assets/backend/images/logo-sm.png') }}" alt="" height="26">
            </span>
            <span class="logo-lg">
                <img src="{{ isset($settings['dark_logo']) ? asset('storage/' . $settings['dark_logo']) : url('assets/backend/images/logo-dark.png') }}" alt="" height="26">
            </span>
        </a>
        <a href="{{ route('home') }}" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{ url('assets/backend/images/logo-sm.png') }}" alt="" height="26">
            </span>
            <span class="logo-lg">
                <img src="{{ isset($settings['white_logo']) ? asset('storage/' . $settings['white_logo']) : url('assets/backend/images/logo-light.png') }}" alt="" height="26">
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover" id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">
            <div id="two-column-menu"></div>
            <ul class="navbar-nav" id="navbar-nav">

                <li class="menu-title"><span data-key="t-menu">Menu</span></li>

                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="{{ route('home') }}" class="nav-link menu-link {{ Route::is('home') ? 'active' : '' }}" wire:navigate>
                        <i class="bi bi-speedometer2"></i> <span data-key="t-dashboard">Dashboard</span>
                    </a>
                </li>

                <li class="menu-title"><i class="ri-more-fill"></i> <span data-key="t-management">Management</span></li>

                <!-- Product Catalog -->
                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs('product.*') ? 'active' : 'collapsed' }}" href="#productManage" data-bs-toggle="collapse" role="button" aria-expanded="{{ request()->routeIs('product.*') ? 'true' : 'false' }}" aria-controls="productManage">
                        <i class="bi bi-box-seam"></i> <span data-key="t-products">Product Catalog</span>
                    </a>
                    <div class="collapse menu-dropdown {{ request()->routeIs('product.*') ? 'show' : '' }}" id="productManage">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('product.products.index') }}" class="nav-link {{ request()->routeIs('product.products.*') ? 'active' : '' }}" wire:navigate> Products </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('product.categories.index') }}" class="nav-link {{ request()->routeIs('product.categories.*') ? 'active' : '' }}" wire:navigate> Categories </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('product.brands.index') }}" class="nav-link {{ request()->routeIs('product.brands.*') ? 'active' : '' }}" wire:navigate> Brands </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('product.coupons.index') }}" class="nav-link {{ request()->routeIs('product.coupons.*') ? 'active' : '' }}" wire:navigate> Coupons </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('product.tags.index') }}" class="nav-link {{ request()->routeIs('product.tags.*') ? 'active' : '' }}" wire:navigate> Tags </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('product.reviews.index') }}" class="nav-link {{ request()->routeIs('product.reviews.*') ? 'active' : '' }}" wire:navigate> Reviews </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Attributes -->
                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs('attribute.*') ? 'active' : 'collapsed' }}" href="#attributeManage" data-bs-toggle="collapse" role="button" aria-expanded="{{ request()->routeIs('attribute.*') ? 'true' : 'false' }}" aria-controls="attributeManage">
                        <i class="bi bi-tags"></i> <span data-key="t-attributes">Attributes</span>
                    </a>
                    <div class="collapse menu-dropdown {{ request()->routeIs('attribute.*') ? 'show' : '' }}" id="attributeManage">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('attribute.attributes.index') }}" class="nav-link {{ request()->routeIs('attribute.attributes.index') ? 'active' : '' }}" wire:navigate> Attributes </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('attribute.attribute-values.index') }}" class="nav-link {{ request()->routeIs('attribute.attribute-values.index') ? 'active' : '' }}" wire:navigate> Attribute Values </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('attribute.attribute-sets.index') }}" class="nav-link {{ request()->routeIs('attribute.attribute-sets.index') ? 'active' : '' }}" wire:navigate> Attribute Sets </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Orders, Deals, Collections -->
                <li class="nav-item">
                    <a href="{{ route('order.index') }}" class="nav-link menu-link {{ request()->routeIs('order.index') ? 'active' : '' }}" wire:navigate>
                        <i class="bi bi-cart3"></i> <span data-key="t-orders">Orders</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('deal.index') }}" class="nav-link menu-link {{ request()->routeIs('deal.index') ? 'active' : '' }}" wire:navigate>
                        <i class="bi bi-lightning-charge"></i> <span data-key="t-deals">Deals</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('collection.index') }}" class="nav-link menu-link {{ request()->routeIs('collection.index') ? 'active' : '' }}" wire:navigate>
                        <i class="bi bi-collection"></i> <span data-key="t-collections">Collections</span>
                    </a>
                </li>

                <!-- Users -->
                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs('users.*') ? 'active' : 'collapsed' }}" href="#userManage" data-bs-toggle="collapse" role="button" aria-expanded="{{ request()->routeIs('users.*') ? 'true' : 'false' }}">
                        <i class="bi bi-people"></i> <span data-key="t-users">Users</span>
                    </a>
                    <div class="collapse menu-dropdown {{ request()->routeIs('users.*') ? 'show' : '' }}" id="userManage">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('users.customers.index') }}" class="nav-link {{ request()->routeIs('users.customers.*') ? 'active' : '' }}" wire:navigate> Customers </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Blog -->
                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs('blog.*') ? 'active' : 'collapsed' }}" href="#blogManage" data-bs-toggle="collapse" role="button" aria-expanded="{{ request()->routeIs('blog.*') ? 'true' : 'false' }}">
                        <i class="bi bi-journal-text"></i> <span data-key="t-blog">Blog</span>
                    </a>
                    <div class="collapse menu-dropdown {{ request()->routeIs('blog.*') ? 'show' : '' }}" id="blogManage">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('blog.category.index') }}" class="nav-link {{ request()->routeIs('blog.category.index') ? 'active' : '' }}" wire:navigate> Category </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('blog.tag.index') }}" class="nav-link {{ request()->routeIs('blog.tag.index') ? 'active' : '' }}" wire:navigate> Tag </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('blog.post.index') }}" class="nav-link {{ request()->routeIs('blog.post.index') ? 'active' : '' }}" wire:navigate> Post </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Locations -->
                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs('locations.*') ? 'active' : 'collapsed' }}" href="#locationManage" data-bs-toggle="collapse" role="button">
                        <i class="bi bi-geo-alt"></i> <span data-key="t-locations">Locations</span>
                    </a>
                    <div class="collapse menu-dropdown {{ request()->routeIs('locations.*') ? 'show' : '' }}" id="locationManage">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('locations.countries') }}" class="nav-link {{ request()->routeIs('locations.countries') ? 'active' : '' }}" wire:navigate> Countries </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('locations.states') }}" class="nav-link {{ request()->routeIs('locations.states') ? 'active' : '' }}" wire:navigate> States </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('locations.cities') }}" class="nav-link {{ request()->routeIs('locations.cities') ? 'active' : '' }}" wire:navigate> Cities </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Shipping & Payment -->
                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs('shipping-method.*', 'payment-method.*') ? 'active' : 'collapsed' }}" href="#shippingPaymentManage" data-bs-toggle="collapse" role="button">
                        <i class="bi bi-truck"></i> <span data-key="t-shipping">Shipping & Payment</span>
                    </a>
                    <div class="collapse menu-dropdown {{ request()->routeIs('shipping-method.*', 'payment-method.*') ? 'show' : '' }}" id="shippingPaymentManage">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('shipping-method.index') }}" class="nav-link {{ request()->routeIs('shipping-method.index') ? 'active' : '' }}" wire:navigate> Shipping Methods </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('payment-method.index') }}" class="nav-link {{ request()->routeIs('payment-method.index') ? 'active' : '' }}" wire:navigate> Payment Methods </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="menu-title"><i class="ri-more-fill"></i> <span data-key="t-system">System</span></li>

                <!-- Logistics / Store Management -->
                <li class="nav-item">
                    <a class="nav-link menu-link {{ (request()->routeIs('warehouse.*') || request()->routeIs('delivery-partner.*')) ? 'active' : 'collapsed' }}"
                        href="#sidebarLogistics"
                        data-bs-toggle="collapse"
                        role="button"
                        aria-expanded="{{ (request()->routeIs('warehouse.*') || request()->routeIs('delivery-partner.*')) ? 'true' : 'false' }}">
                        <i class="bi bi-truck"></i> <span data-key="t-logistics">Logistics</span>
                    </a>
                    <div class="collapse menu-dropdown {{ (request()->routeIs('warehouse.*') || request()->routeIs('delivery-partner.*')) ? 'show' : '' }}" id="sidebarLogistics">
                        <ul class="nav nav-sm flex-column">
                            <!-- Warehouses -->
                            <li class="nav-item">
                                <a href="{{ route('warehouse.index') }}"
                                    class="nav-link {{ request()->routeIs('warehouse.*') ? 'active' : '' }}"
                                    wire:navigate>
                                    Warehouses
                                </a>
                            </li>
                            <!-- Delivery Partners -->
                            <li class="nav-item">
                                <a href="{{ route('delivery-partner.index') }}"
                                    class="nav-link {{ request()->routeIs('delivery-partner.*') ? 'active' : '' }}"
                                    wire:navigate>
                                    Delivery Partners
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Template Management -->
                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs('template.*') ? 'active' : 'collapsed' }}"
                        href="#sidebarTemplates"
                        data-bs-toggle="collapse"
                        role="button"
                        aria-expanded="{{ request()->routeIs('template.*') ? 'true' : 'false' }}"
                        aria-controls="sidebarTemplates">
                        <i class="bi bi-window-stack"></i> <span data-key="t-templates">Design Templates</span>
                    </a>
                    <div class="collapse menu-dropdown {{ request()->routeIs('template.*') ? 'show' : '' }}" id="sidebarTemplates">
                        <ul class="nav nav-sm flex-column">
                            <!-- Themes -->
                            <li class="nav-item">
                                <a href="{{ route('template.theme') }}"
                                    class="nav-link {{ request()->routeIs('template.theme') ? 'active' : '' }}"
                                    wire:navigate>
                                    Themes
                                </a>
                            </li>
                            <!-- Headers -->
                            <li class="nav-item">
                                <a href="{{ route('template.header') }}"
                                    class="nav-link {{ request()->routeIs('template.header') ? 'active' : '' }}"
                                    wire:navigate>
                                    Headers
                                </a>
                            </li>
                            <!-- Footers -->
                            <li class="nav-item">
                                <a href="{{ route('template.footer') }}"
                                    class="nav-link {{ request()->routeIs('template.footer') ? 'active' : '' }}"
                                    wire:navigate>
                                    Footers
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Page Management -->
                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs('page.*') ? 'active' : 'collapsed' }}"
                        href="#sidebarPages"
                        data-bs-toggle="collapse"
                        role="button"
                        aria-expanded="{{ request()->routeIs('page.*') ? 'true' : 'false' }}"
                        aria-controls="sidebarPages">
                        <i class="bi bi-file-earmark-text"></i> <span data-key="t-pages">Pages</span>
                    </a>
                    <div class="collapse menu-dropdown {{ request()->routeIs('page.*') ? 'show' : '' }}" id="sidebarPages">
                        <ul class="nav nav-sm flex-column">
                            <!-- All Pages -->
                            <li class="nav-item">
                                <a href="{{ route('page.index') }}"
                                    class="nav-link {{ request()->routeIs('page.index') || request()->routeIs('page.show') ? 'active' : '' }}"
                                    wire:navigate>
                                    All Pages
                                </a>
                            </li>
                            <!-- Create New Page -->
                            <li class="nav-item">
                                <a href="{{ route('page.create') }}"
                                    class="nav-link {{ request()->routeIs('page.create') ? 'active' : '' }}"
                                    wire:navigate>
                                    Add New Page
                                </a>
                            </li>

                            <!-- Hidden Edit State (Ensures parent stays open during editing) -->
                            @if(request()->routeIs('page.edit'))
                            <li class="nav-item d-none">
                                <a href="#" class="nav-link active">Editing Page</a>
                            </li>
                            @endif
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a href="{{ route('settings.index') }}" class="nav-link menu-link {{ request()->routeIs('settings.index') ? 'active' : '' }}" wire:navigate>
                        <i class="bi bi-gear"></i> <span data-key="t-settings">Settings</span>
                    </a>
                </li>

            </ul>
        </div>
    </div>
    <div class="sidebar-background"></div>
</div>