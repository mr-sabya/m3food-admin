<div class="py-4">
    <h2 class="mb-4">Category Management</h2>

    @if (session()->has('message'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('message') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if (session()->has('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card shadow-sm mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Category List</h5>
            <a href="{{ route('product.categories.create') }}" wire:navigate class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New Category
            </a>
        </div>
        <div class="card-body">
            <div class="row align-items-center mb-3">
                <div class="col-md-6 col-lg-4"> {{-- Adjust column size as needed, e.g., col-lg-3 for smaller search --}}
                    <input type="text" class="form-control" placeholder="Search categories..." wire:model.live.debounce.300ms="search">
                </div>
                <div class="col-md-6 col-lg-8 d-flex justify-content-md-end justify-content-start mt-2 mt-md-0"> {{-- Push to end on medium screens and up, start on small --}}
                    <div class="d-flex align-items-center gap-2"> {{-- Keep per page and text grouped --}}
                        <select wire:model.live="perPage" class="form-select w-auto"> {{-- w-auto makes it fit content, not full width --}}
                            <option value="5">5</option>
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                        </select>
                        <span class="text-nowrap">Per Page</span> {{-- text-nowrap prevents "Per Page" from wrapping --}}
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-striped table-bordered mb-0">
                    <thead>
                        <tr>
                            <th wire:click="sortBy('name')" style="cursor: pointer;">
                                Name
                                @if ($sortField == 'name')
                                <i class="fas fa-{{ $sortDirection == 'asc' ? 'sort-up' : 'sort-down' }}"></i>
                                @endif
                            </th>
                            <th>Icon</th>
                            <th>Image</th>
                            <th>Parent</th>
                            <th wire:click="sortBy('is_active')" style="cursor: pointer;">
                                Active
                                @if ($sortField == 'is_active')
                                <i class="fas fa-{{ $sortDirection == 'asc' ? 'sort-up' : 'sort-down' }}"></i>
                                @endif
                            </th>
                            <th wire:click="sortBy('show_on_homepage')" style="cursor: pointer;">
                                Homepage
                                @if ($sortField == 'show_on_homepage')
                                <i class="fas fa-{{ $sortDirection == 'asc' ? 'sort-up' : 'sort-down' }}"></i>
                                @endif
                            </th>
                            <th wire:click="sortBy('sort_order')" style="cursor: pointer;">
                                Order
                                @if ($sortField == 'sort_order')
                                <i class="fas fa-{{ $sortDirection == 'asc' ? 'sort-up' : 'sort-down' }}"></i>
                                @endif
                            </th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($categories as $category)
                        <tr>
                            <td>
                                <strong>{{ $category->name }}</strong>
                                <br><small class="text-muted">{{ $category->slug }}</small>
                            </td>
                            <td>
                                @if ($category->image_url)
                                <img src="{{ $category->image_url }}" alt="{{ $category->name }}" class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                                @else
                                <span class="text-muted">No Image</span>
                                @endif
                            </td>
                            <td>
                                @if ($category->icon)
                                <img src="{{ $category->icon_url }}" alt="{{ $category->name }}" class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                                @else
                                <span class="text-muted">No Image</span>
                                @endif
                            </td>
                            <td>{{ $category->parent->name ?? '—' }}</td>
                            <td>
                                <i class="fas {{ $category->is_active ? 'fa-check-circle text-success' : 'fa-times-circle text-danger' }}"></i>
                            </td>
                            <td>
                                <i class="fas {{ $category->show_on_homepage ? 'fa-check-circle text-success' : 'fa-times-circle text-danger' }}"></i>
                            </td>
                            <td>{{ $category->sort_order }}</td>
                            <td>
                                <button class="btn btn-sm btn-info" wire:click="editCategory({{ $category->id }})" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger" onclick="confirm('Are you sure you want to delete this category?') || event.stopImmediatePropagation()" wire:click="deleteCategory({{ $category->id }})" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="py-5">
                                <div class="text-center py-5">
                                    <!-- Icon -->
                                    <div class="mb-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="currentColor" class="text-secondary opacity-25" viewBox="0 0 16 16">
                                            <path d="M9.293 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.707A1 1 0 0 0 13.707 4L10 .293A1 1 0 0 0 9.293 0zM9.5 3.5v-2l3 3h-2a1 1 0 0 1-1-1zM11 8H5a.5.5 0 0 1 0-1h6a.5.5 0 0 1 0 1zm0 2H5a.5.5 0 0 1 0-1h6a.5.5 0 0 1 0 1zm0 2H5a.5.5 0 0 1 0-1h6a.5.5 0 0 1 0 1z" />
                                        </svg>
                                    </div>
                                    <!-- Text Content -->
                                    <h4 class="fw-bold text-dark">No Categories Found</h4>
                                    <p class="text-muted mx-auto" style="max-width: 400px;">
                                        It looks like you haven't added any categories yet. Start organizing your products by creating your first category.
                                    </p>
                                    <!-- CTA Button -->
                                    <a href="{{ route('product.categories.create') }}" class="btn btn-primary shadow-sm mt-3">
                                        <i class="fas fa-plus me-1"></i> Add New Category
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $categories->links('pagination::bootstrap-5') }}
        </div>
    </div>


</div>