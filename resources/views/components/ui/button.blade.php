@props([
    'variant' => 'default',
    'size' => 'default',
    'class' => ''
])

@php
$baseClasses = 'inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50';

$variantClasses = match($variant) {
    'default' => 'bg-primary text-primary-foreground hover:bg-primary/90',
    'destructive' => 'bg-destructive text-destructive-foreground hover:bg-destructive/90',
    'outline' => 'border border-input bg-background hover:bg-muted hover:text-muted-foreground',
    'secondary' => 'bg-secondary text-secondary-foreground hover:bg-secondary/80',
    'ghost' => 'hover:bg-muted hover:text-muted-foreground',
    'link' => 'text-primary underline-offset-4 hover:underline',
    default => 'bg-primary text-primary-foreground hover:bg-primary/90'
};

$sizeClasses = match($size) {
    'default' => 'h-10 px-4 py-2',
    'sm' => 'h-9 rounded-md px-3',
    'lg' => 'h-11 rounded-md px-8',
    'icon' => 'h-10 w-10',
    default => 'h-10 px-4 py-2'
};
@endphp

<{{ $tag ?? 'button' }} {{ $attributes->merge(['class' => $baseClasses . ' ' . $variantClasses . ' ' . $sizeClasses . ' ' . $class]) }}>
    {{ $slot }}
</{{ $tag ?? 'button' }}>
