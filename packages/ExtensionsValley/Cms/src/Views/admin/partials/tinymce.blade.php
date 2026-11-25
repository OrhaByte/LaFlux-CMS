@section('use_cms_editor', '1')
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.3/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    jQuery(document).ready(function () {
        if (typeof tinymce !== 'undefined') {
            tinymce.init({
                selector: 'textarea.cms-editor',
                base_url: 'https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.3',
                suffix: '.min',
                promotion: false,
                branding: false,
                height: 400,
                menubar: true,
                plugins: 'advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table wordcount',
                toolbar: 'undo redo | blocks | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media table | removeformat code fullscreen',
                relative_urls: false,
                remove_script_host: false,
                file_picker_types: 'image',
                file_picker_callback: function (callback, value, meta) {
                    if (meta.filetype === 'image' && typeof window.openMediaPicker === 'function') {
                        window.openMediaPicker('tinymce_cb', callback);
                    }
                },
                readonly: {{ isset($viewmode) ? 'true' : 'false' }}
            });
        }
    });
</script>
@endpush
