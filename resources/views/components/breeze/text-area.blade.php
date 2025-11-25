@props(['disabled' => false, 'rte' => false])

<textarea @disabled($disabled)
    {{ $attributes->merge(['class' => 'editor border border-gray-400 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-xs px-2 py-1 resize-none', 'rows' => '10', 'placeholder' => 'Large text']) }}></textarea>

@if ($rte)
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/5.7.1/tinymce.min.js"
        integrity="sha512-RnlQJaTEHoOCt5dUTV0Oi0vOBMI9PjCU7m+VHoJ4xmhuUNcwnB5Iox1es+skLril1C3gHTLbeRepHs1RpSCLoQ=="
        crossorigin="anonymous"></script>

    <script>
        var editor_config = {
            relative_urls: false,
            path_absolute: "{{ config('app.url') }}",
            selector: '.editor',
            menubar: false,
            plugins: [
                'advlist autolink lists link image charmap print preview anchor textcolor',
                'searchreplace visualblocks fullscreen',
                'contextmenu paste help wordcount code'
            ],
            toolbar: ' undo redo |  bold italic | link | alignleft aligncenter alignright alignjustify | numlist bullist | outdent indent | removeformat | code | help',
        }
        tinymce.init(editor_config);
    </script>
@endif
