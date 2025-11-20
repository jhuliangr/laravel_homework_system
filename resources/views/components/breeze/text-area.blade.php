@props(['disabled' => false])

<textarea @disabled($disabled) {{ $attributes->merge(['class' => 'border border-gray-400 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-xs px-2 py-1 resize-none', 'rows' => '10', 'placeholder'=>'Large text'] ) }}></textarea>
