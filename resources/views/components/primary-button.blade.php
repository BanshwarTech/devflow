<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-gradient-to-r from-brand-royal to-brand-sky border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:from-brand-blue hover:to-brand-royal active:from-brand-navy active:to-brand-blue focus:outline-none focus:ring-2 focus:ring-brand-sky focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
