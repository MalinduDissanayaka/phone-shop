@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-slate-600 focus:border-blue-500 focus:ring-blue-500 rounded-xl shadow-sm bg-slate-900/60 text-white placeholder-slate-400 backdrop-blur-sm transition-all duration-300']) }}>
