@props([
    'class' => '',
    'variant' => 'default'
])

@php
$cardClasses = match($variant) {
    'default' => 'rounded-lg border bg-card text-card-foreground shadow-sm',
    'outline' => 'rounded-lg border border-border bg-transparent text-foreground shadow-sm',
    'ghost' => 'rounded-lg bg-transparent text-foreground',
    'destructive' => 'rounded-lg border border-destructive bg-destructive text-destructive-foreground shadow-sm',
    default => 'rounded-lg border bg-card text-card-foreground shadow-sm'
};
@endphp

<div {{ $attributes->merge(['class' => $cardClasses . ' ' . $class]) }}>
    {{ $slot }}
</div>
