<div class="page-builder-root"
    style="--primary-color: {{ $page->theme->settings['primary_color'] ?? '#0d6efd' }}; 
            --secondary-color: {{ $page->theme->settings['secondary_color'] ?? '#6c757d' }};">

    {{-- 1. DYNAMIC HEADER --}}
    @if($page->is_header_visible && $page->header)
    @include('partials.headers.' . Str::slug($page->header->title))
    @endif

    {{-- 2. DYNAMIC TIMER (If enabled in Admin) --}}
    @if($page->hasActiveTimer())
    <div x-data="timer('{{ $page->timer_ends_at->format('Y-m-d H:i:s') }}')"
        class="bg-danger text-white text-center py-3 sticky-top shadow-sm">
        <div class="container">
            <p class="mb-0 h5">
                <i class="fas fa-bolt me-2 text-warning"></i>
                {{ $page->timer_label ?? 'Limited Time Offer' }}:
                <span x-text="time" class="fw-bold"></span>
            </p>
        </div>
    </div>
    @endif

    {{-- 3. THE NESTED CONTENT RENDERER --}}
    <main>
        @foreach($page->content ?? [] as $row)
        {{-- ROW LAYER --}}
        <section class="py-5">
            <div class="container">
                <div class="row g-4">

                    @foreach($row['columns'] as $column)
                    {{-- COLUMN LAYER (Bootstrap dynamic cols) --}}
                    <div class="{{ $column['width'] ?? 'col-md-12' }}">

                        @foreach($column['elements'] as $element)
                        {{-- ELEMENT LAYER --}}
                        <div class="element-wrapper mb-4">

                            @if($element['type'] === 'text')
                            <div class="page-content-text">
                                {!! $element['data']['content'] !!}
                            </div>
                            @elseif($element['type'] === 'video')
                            <div class="ratio ratio-16x9 rounded-4 shadow-sm overflow-hidden">
                                <iframe src="https://www.youtube.com/embed/{{ $element['data']['id'] }}"
                                    allowfullscreen></iframe>
                            </div>
                            @elseif($element['type'] === 'image')
                            <img src="{{ asset('storage/' . $element['data']['path']) }}"
                                class="img-fluid rounded-4 shadow-sm" alt="Page Image">
                            @endif

                        </div>
                        @endforeach

                    </div>
                    @endforeach

                </div>
            </div>
        </section>
        @endforeach
    </main>

    {{-- 4. DYNAMIC FOOTER --}}
    @if($page->is_footer_visible && $page->footer)
    @include('partials.footers.' . Str::slug($page->footer->title))
    @endif

    <style>
        /* Use the CSS variables from the Theme Model */
        .btn-primary-custom {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }

        .text-primary-custom {
            color: var(--primary-color);
        }

        .page-content-text h1,
        .page-content-text h2 {
            color: var(--primary-color);
            font-weight: 800;
            margin-bottom: 1.5rem;
        }
    </style>
</div>

{{-- Timer Script for Alpine.js --}}
<script>
    function timer(expiry) {
        return {
            expiry: new Date(expiry).getTime(),
            time: '',
            init() {
                let interval = setInterval(() => {
                    let now = new Date().getTime();
                    let diff = this.expiry - now;
                    if (diff < 0) {
                        clearInterval(interval);
                        this.time = "Offer Expired";
                        return;
                    }
                    let h = Math.floor(diff / (1000 * 60 * 60));
                    let m = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                    let s = Math.floor((diff % (1000 * 60)) / 1000);
                    this.time = `${h}h ${m}m ${s}s`;
                }, 1000);
            }
        }
    }
</script>