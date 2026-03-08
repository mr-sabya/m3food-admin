<div class="py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Page Management</h2>
        <a href="{{ route('page.create') }}" wire:navigate class="btn btn-primary">
            <i class="fas fa-plus"></i> Create New Page
        </a>
    </div>

    @if (session()->has('message'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('message') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-4">
                    <input type="text" class="form-control" placeholder="Search pages..." wire:model.live.debounce.300ms="search">
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-striped border">
                    <thead>
                        <tr>
                            <th wire:click="sortBy('title')" style="cursor:pointer">Title</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Theme</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pages as $page)
                        <tr>
                            <td>
                                <strong>{{ $page->title }}</strong><br>
                                <small class="text-muted">/{{ $page->slug }}</small>
                            </td>
                            <td><span class="badge bg-info">{{ $page->page_type }}</span></td>
                            <td>
                                <span class="badge {{ $page->status === 'published' ? 'bg-success' : 'bg-warning' }}">
                                    {{ ucfirst($page->status) }}
                                </span>
                            </td>
                            <td>{{ $page->theme->title ?? 'N/A' }}</td>
                            <td>
                                <a href="{{ route('page.edit', $page->id) }}" wire:navigate class="btn btn-sm btn-info">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button class="btn btn-sm btn-danger"
                                    onclick="confirm('Delete this page?') || event.stopImmediatePropagation()"
                                    wire:click="deletePage({{ $page->id }})">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <a href="/{{ $page->slug }}" target="_blank" class="btn btn-sm btn-secondary">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $pages->links() }}
            </div>
        </div>
    </div>
</div>