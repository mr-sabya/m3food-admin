<div wire:ignore>
    <div id="{{ $quillId }}" style="height: {{ $height ?? '400px' }};"></div>
</div>

@script
<script>
    const colors = [
        '#000000', '#e60000', '#ff9900', '#ffff00', '#008a00', '#0066cc', '#9933ff',
        '#ffffff', '#facccc', '#ffebcc', '#ffffcc', '#cce8cc', '#cce0f5', '#ebd6ff',
        '#bbbbbb', '#f06666', '#ffc266', '#ffff66', '#66b966', '#66a3e0', '#c285ff',
        '#888888', '#a10000', '#b26b00', '#b2b200', '#006100', '#0047b2', '#6b24b2',
        '#444444', '#5c0000', '#663d00', '#666600', '#003700', '#002966', '#3d1466'
    ];

    const toolbarOptions = [
        [{
            'font': []
        }],
        [{
            'header': [1, 2, 3, 4, 5, 6, false]
        }],
        [{
            'size': ['small', false, 'large', 'huge']
        }],

        ['bold', 'italic', 'underline', 'strike'],

        ['link', 'image'],

        [{
            'list': 'ordered'
        }, {
            'list': 'bullet'
        }, {
            'list': 'check'
        }],

        ['color', 'background'], // use picker instead

        [{
            'align': []
        }],

        ['blockquote', 'code-block'],

        ['clean']
    ];

    const quill = new Quill('#' + @js($quillId), {
        theme: @js($theme),
        modules: {
            syntax: true,
            toolbar: {
                container: toolbarOptions,
                handlers: {
                    color: function() {
                        pickColor('color');
                    },
                    background: function() {
                        pickColor('background');
                    }
                }
            }
        }
    });

    // Load existing value
    quill.clipboard.dangerouslyPasteHTML($wire.get('value'));

    // Update Livewire
    quill.on('text-change', function() {
        let value = quill.root.innerHTML;
        @this.set('value', value);
    });

    function pickColor(format) {

        let input = document.createElement('input');
        input.setAttribute('type', 'color');

        input.addEventListener('input', function() {
            quill.format(format, input.value);
        });

        input.click();
    }
</script>
@endscript