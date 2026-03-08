<?php

namespace App\Livewire\Page;

use App\Models\Page;
use App\Models\Theme;
use App\Models\Header;
use App\Models\Footer;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class Manage extends Component
{
    use WithFileUploads;

    public $pageId;
    public $isEditing = false;
    public $pageTitle = 'Create New Page';

    // Model Fields
    public $title, $slug, $page_type = 'landing_page', $status = 'draft';
    public $theme_id, $header_id, $footer_id, $is_header_visible = true, $is_footer_visible = true;
    public $meta_title, $meta_description, $meta_keywords, $og_image, $temp_og_image;
    public $main_product_id, $show_checkout_form = false, $checkout_form_title;
    public $show_timer = false, $timer_label, $timer_ends_at;
    public $content = [];

    public $iteration = 0;
    public $image_tmp;

    public function mount($pageId = null)
    {
        if ($pageId) {
            $page = Page::findOrFail($pageId);
            $this->pageId = $page->id;
            $this->isEditing = true;
            $this->fill($page->toArray());
            $this->timer_ends_at = $page->timer_ends_at ? $page->timer_ends_at->format('Y-m-d\TH:i') : null;
            $this->pageTitle = 'Edit Page: ' . $page->title;
        } else {
            $this->addRow();
        }
    }

    // --- Builder Logic ---
    public function addRow()
    {
        $this->content[] = [
            'id' => uniqid(),
            'style' => [
                'bg_color' => '#ffffff',
                'padding_y' => '50', // px
                'is_container' => true, // true = container, false = full width
            ],
            'columns' => []
        ];
    }

    public function removeRow($rowIndex)
    {
        unset($this->content[$rowIndex]);
        $this->content = array_values($this->content);
    }

    public function addColumn($rowIndex)
    {
        $this->content[$rowIndex]['columns'][] = [
            'width' => 'col-md-12',
            'elements' => []
        ];
    }

    public function removeColumn($rowIndex, $colIndex)
    {
        unset($this->content[$rowIndex]['columns'][$colIndex]);
        $this->content[$rowIndex]['columns'] = array_values($this->content[$rowIndex]['columns']);
    }

    public function addElement($rowIndex, $colIndex, $type)
    {
        $defaultStyle = [
            'bg_color' => 'transparent',
            'text_color' => '#000000',
            'font_size' => '18',
            'font_weight' => 'normal',
            'text_align' => 'text-center',
            'width' => '100',
            'border_color' => '#dee2e6',
            'border_width' => '0',
            'border_style' => 'solid',
            'border_radius' => '0',
            'padding' => '0',
            'shadow' => 'none',
            'margin_x' => 'mx-auto'
        ];

        $defaultData = match ($type) {
            'text'  => [
                'content' => '',
                'url' => '', // New: Link URL
                'style' => $defaultStyle
            ],
            'image' => [
                'path' => '',
                'alt' => '',
                'url' => '', // New: Link URL
                'style' => $defaultStyle // New: Styles for images
            ],
            'video' => ['id' => '', 'width' => '100'],
            default => []
        };

        $this->content[$rowIndex]['columns'][$colIndex]['elements'][] = [
            'type' => $type,
            'data' => $defaultData
        ];
    }


    public function removeElement($rowIndex, $colIndex, $elIndex)
    {
        unset($this->content[$rowIndex]['columns'][$colIndex]['elements'][$elIndex]);
        $this->content[$rowIndex]['columns'][$colIndex]['elements'] = array_values($this->content[$rowIndex]['columns'][$colIndex]['elements']);
    }

    public function uploadElementImage($rowIndex, $colIndex, $elIndex, $file)
    {
        $path = $file->store('pages/builder', 'public');
        $this->content[$rowIndex]['columns'][$colIndex]['elements'][$elIndex]['data']['path'] = $path;
        $this->iteration++;
    }

    public function updatedTitle($value)
    {
        if (!$this->isEditing) $this->slug = Str::slug($value);
    }

    public function savePage()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'slug' => ['required', Rule::unique('pages')->ignore($this->pageId)],
            'theme_id' => 'required|exists:themes,id',
        ]);

        $data = [
            'title' => $this->title,
            'slug' => $this->slug,
            'page_type' => $this->page_type,
            'status' => $this->status,
            'theme_id' => $this->theme_id,
            'header_id' => $this->header_id ?: null,
            'footer_id' => $this->footer_id ?: null,
            'is_header_visible' => $this->is_header_visible,
            'is_footer_visible' => $this->is_footer_visible,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
            'meta_keywords' => $this->meta_keywords,
            'main_product_id' => $this->main_product_id ?: null,
            'show_checkout_form' => $this->show_checkout_form,
            'checkout_form_title' => $this->checkout_form_title,
            'show_timer' => $this->show_timer,
            'timer_label' => $this->timer_label,
            'timer_ends_at' => $this->timer_ends_at,
            'content' => $this->content,
            'published_at' => ($this->status === 'published') ? now() : null,
        ];

        if ($this->temp_og_image) {
            $data['og_image'] = $this->temp_og_image->store('seo', 'public');
        }

        Page::updateOrCreate(['id' => $this->pageId], $data);

        session()->flash('message', 'Page saved successfully!');
        return redirect()->route('users.pages.index');
    }

    public function render()
    {
        return view('livewire.page.manage', [
            'themes' => Theme::where('type', $this->page_type)->get(),
            'headers' => Header::all(),
            'footers' => Footer::all(),
            'products' => Product::all(),
        ]);
    }
}
