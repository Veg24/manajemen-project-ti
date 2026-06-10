@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 dark:border-gray-500 dark:bg-gray-900 dark:text-gray-100 focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-2 focus:ring-indigo-500/20 dark:focus:ring-indigo-400/20 rounded-md shadow-sm transition duration-150']) }}>
