@props([
    'title' => '',
    'description' => '',
    'icon' => null,
    'actions' => null,
    'breadcrumbs' => null,
    'currentPage' => ''
])

<div class="min-h-screen bg-background">
    <!-- Page Header -->
    <div class="border-b bg-background/95 backdrop-blur supports-[backdrop-filter]:bg-background/60">
        <div class="container flex h-16 items-center">
            <div class="mr-4 hidden md:flex">
                @if($breadcrumbs)
                    <nav class="flex items-center space-x-1 text-sm text-muted-foreground">
                        {{ $breadcrumbs }}
                    </nav>
                @endif
            </div>
            <div class="flex flex-1 items-center justify-between space-x-2 md:justify-end">
                <div class="w-full flex-1 md:w-auto md:flex-none">
                    <div class="flex items-center space-x-4">
                        @if($icon)
                            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-primary/10">
                                {!! $icon !!}
                            </div>
                        @endif
                        <div>
                            @if($title)
                                <h1 class="text-2xl font-bold tracking-tight">{{ $title }}</h1>
                            @endif
                            @if($description)
                                <p class="text-sm text-muted-foreground">{{ $description }}</p>
                            @endif
                        </div>
                    </div>
                </div>
                @if($actions)
                    <div class="flex items-center space-x-2">
                        {{ $actions }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Page Content -->
    <div class="container py-6">
        {{ $slot }}
    </div>
</div>
