@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-slate-200 focus:border-[#04371f] focus:ring-[#04371f] rounded-xl shadow-sm text-sm py-3 px-4 transition-all bg-slate-50/50 focus:bg-white']) }}>
