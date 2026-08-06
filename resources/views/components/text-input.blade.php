@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 focus:border-brand-royal focus:ring-brand-royal rounded-md shadow-sm']) }}>
