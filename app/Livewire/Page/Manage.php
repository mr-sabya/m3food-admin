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

class Manage extends Component
{
    use WithFileUploads;

    public $pageId;
    public $isEditing = false;
    public $pageTitle = 'Create New Page';

    public $title, $slug, $page_type = 'landing_page', $status = 'draft';
    public $theme_id, $header_id, $footer_id;
    public $is_header_visible = true, $is_footer_visible = true;

    public $meta_title, $meta_description, $meta_keywords;
    public $og_image, $temp_og_image;

    public $main_product_id, $show_checkout_form = false, $checkout_form_title;
    public $show_timer = false, $timer_label, $timer_ends_at;

    public $content = [];
    public $image_tmp;
    public $iteration = 0;

    public function mount($pageId = null)
    {
        if ($pageId) {
            $page = Page::findOrFail($pageId);
            $this->pageId = $page->id;
            $this->isEditing = true;
            $this->fill($page->toArray());
            $this->content = $page->content ?? [];
            $this->pageTitle = 'Edit Page: ' . $page->title;
            if ($page->timer_ends_at) {
                $this->timer_ends_at = $page->timer_ends_at->format('Y-m-d\TH:i');
            }
        }

        if (!$this->content) {
            $this->addRow();
        }

        $this->normalizeContent();
    }

    // Ensure all nested arrays exist to prevent undefined key errors
    private function normalizeContent()
    {
        foreach ($this->content as $r => $row) {
            $this->content[$r]['style'] = $row['style'] ?? ['bg_color' => '#fff', 'padding_y' => '50', 'is_container' => true];
            $this->content[$r]['columns'] = $row['columns'] ?? [['width' => 'col-md-12', 'elements' => []]];

            foreach ($this->content[$r]['columns'] as $c => $col) {
                $this->content[$r]['columns'][$c]['elements'] = $col['elements'] ?? [];
                foreach ($this->content[$r]['columns'][$c]['elements'] as $e => $el) {
                    $this->content[$r]['columns'][$c]['elements'][$e]['data'] = $el['data'] ?? [];
                    if ($el['type'] === 'text') {
                        $this->content[$r]['columns'][$c]['elements'][$e]['data']['content'] = $el['data']['content'] ?? '';
                        $this->content[$r]['columns'][$c]['elements'][$e]['data']['style'] = $el['data']['style'] ?? [];
                    }
                    if ($el['type'] === 'image') {
                        $this->content[$r]['columns'][$c]['elements'][$e]['data']['path'] = $el['data']['path'] ?? '';
                        $this->content[$r]['columns'][$c]['elements'][$e]['data']['style'] = $el['data']['style'] ?? [];
                    }
                    if ($el['type'] === 'video') {
                        $this->content[$r]['columns'][$c]['elements'][$e]['data']['id'] = $el['data']['id'] ?? '';
                    }
                }
            }
        }
    }

    // ----------------------------
    // Builder
    // ----------------------------
    public function addRow()
    {
        $this->content[] = [
            'id' => uniqid(),
            'style' => ['bg_color' => '#ffffff', 'padding_y' => '50', 'is_container' => true],
            'columns' => [['width' => 'col-md-12', 'elements' => []]]
        ];
        $this->normalizeContent();
    }

    public function removeRow($rowIndex)
    {
        unset($this->content[$rowIndex]);
        $this->content = array_values($this->content);
        $this->normalizeContent();
    }
    public function addColumn($rowIndex)
    {
        $this->content[$rowIndex]['columns'][] = ['width' => 'col-md-12', 'elements' => []];
        $this->normalizeContent();
    }
    public function removeColumn($rowIndex, $colIndex)
    {
        unset($this->content[$rowIndex]['columns'][$colIndex]);
        $this->content[$rowIndex]['columns'] = array_values($this->content[$rowIndex]['columns']);
        $this->normalizeContent();
    }

    public function addElement($rowIndex, $colIndex, $type)
    {
        $defaultStyle = ['bg_color' => '#fff', 'text_color' => '#000', 'font_size' => '18', 'font_weight' => 'normal', 'text_align' => 'text-center', 'width' => '100', 'border_color' => '#dee2e6', 'border_width' => '0', 'border_style' => 'solid', 'border_radius' => '10', 'padding' => '20', 'shadow' => 'none', 'margin_x' => 'mx-auto'];
        if ($type === 'text') $data = ['content' => '', 'url' => '', 'style' => $defaultStyle];
        elseif ($type === 'image') $data = ['path' => '', 'alt' => '', 'style' => ['width' => '100', 'border_radius' => '0', 'shadow' => 'none', 'border_width' => '0']];
        else $data = ['id' => ''];
        $this->content[$rowIndex]['columns'][$colIndex]['elements'][] = ['type' => $type, 'data' => $data];
        $this->normalizeContent();
    }

    public function removeElement($rowIndex, $colIndex, $elIndex)
    {
        unset($this->content[$rowIndex]['columns'][$colIndex]['elements'][$elIndex]);
        $this->content[$rowIndex]['columns'][$colIndex]['elements'] = array_values($this->content[$rowIndex]['columns'][$colIndex]['elements']);
        $this->normalizeContent();
    }

    public function uploadElementImage($rowIndex, $colIndex, $elIndex)
    {
        $path = $this->image_tmp->store('pages/builder', 'public');
        $this->content[$rowIndex]['columns'][$colIndex]['elements'][$elIndex]['data']['path'] = $path;
        $this->image_tmp = null;
        $this->iteration++;
        $this->normalizeContent();
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
            'theme_id' => 'required|exists:themes,id'
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
            'published_at' => $this->status === 'published' ? now() : null,
        ];
        if ($this->temp_og_image) $data['og_image'] = $this->temp_og_image->store('seo', 'public');
        Page::updateOrCreate(['id' => $this->pageId], $data);
        session()->flash('message', 'Page saved successfully!');
        return redirect()->route('page.index');
    }

    public function render()
    {
        $this->normalizeContent();
        return view('livewire.page.manage', [
            'themes' => Theme::where('type', $this->page_type)->get(),
            'headers' => Header::all(),
            'footers' => Footer::all(),
            'products' => Product::all(),
        ]);
    }
}
