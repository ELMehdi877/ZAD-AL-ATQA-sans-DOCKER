<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-6 py-3 bg-[#04371f] border border-transparent rounded-xl font-bold text-sm text-white uppercase tracking-widest hover:bg-[#054527] focus:bg-[#054527] active:bg-[#032a18] focus:outline-none focus:ring-2 focus:ring-[#04371f] focus:ring-offset-2 transition ease-in-out duration-150 shadow-lg shadow-[#04371f]/20']) }}>
    {{ $slot }}
</button>
