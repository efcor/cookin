<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <meta name="csrf_token" content="{{ csrf_token() }}" />
    <title>Cookin' - Recipe Helper</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind and app css -->
    @vite('resources/css/app.css')

    <style>
        /* Small UI niceties */
        body {
            background: linear-gradient(180deg, #fbfbfc 0%, #ffffff 100%);
        }
    </style>
</head>

<body class="min-h-screen text-slate-700 antialiased">

    <div class="max-w-6xl mx-auto px-6 py-12">
        <header class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-coral-100 rounded-2xl flex items-center justify-center shadow-sm">
                    <!-- simple logo mark -->
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" class="text-coral-500">
                        <path d="M12 3l7 7-7 7-7-7 7-7z" fill="currentColor" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-lg md:text-2xl font-semibold">Cookin'</h1>
                    <p class="hidden md:block text-sm text-slate-500">Pick ingredients and generate a delicious recipe instantly.</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a id="saveBtn" href="/" class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-coral-500 text-white text-sm shadow hover:opacity-95">
                    Recipe Assistant
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg>
                </a>
            </div>
        </header>

        <main class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Pane -->
            <section class="lg:col-span-1 bg-white rounded-xl shadow p-6 flex flex-col">
                <div class="flex items-start justify-between">
                    <h3 class="text-lg font-medium">My Recipes</h3>
                </div>

                <div class="my-4 flex-1 flex flex-col gap-4 text-slate-600">
                    Here are recipes we've generated for you:
                </div>

                @if (count($mine))
                    <ul>
                        @foreach($mine as $recipe)
                            <li>
                                <a href="/?r={{ $recipe->id }}" class="text-blue-500">
                                    {{ json_decode($recipe->output, true)['title'] }}
                                    <span class="text-slate-500 text-xs">{{ $recipe->created_at->setTimezone('America/New_York')->format('M j\, g:i a') }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="text-slate-500">Recipes we generate for you will appear here.</div>
                @endif
            </section>

            <!-- Right Pane -->
            <section class="lg:col-span-1 bg-white rounded-xl shadow p-6 flex flex-col">
                <div class="flex items-start justify-between">
                    <h3 class="text-lg font-medium">Other Users' Recipes</h3>
                </div>

                <div class="my-4 flex-1 flex flex-col gap-4 text-slate-600">
                    Check out some of the recipes we've generated for others:
                </div>

                @if (count($others))
                    <ul>
                        @foreach($others as $recipe)
                            <li>
                                <a href="/?r={{ $recipe->id }}" class="text-blue-500">
                                    {{ json_decode($recipe->output, true)['title'] }}
                                    <span class="text-slate-500 text-xs">{{ $recipe->created_at->setTimezone('America/New_York')->format('M j\, g:i a') }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="text-slate-500">Recipes we generate for you will appear here.</div>
                @endif
            </section>
        </main>

        <footer class="mt-10 text-sm text-slate-400 text-center">
            <!-- <span class="mr-1">Username: {{ session()->get('username') }}</span> -->
            <!-- | -->
            <div class="inline-flex text-blue-500">
                <a href="https://forms.gle/cLaJ37KcH9mLRFJT8" target="_blank" class="text-blue-500 mr-1">Feedback Survey</a>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="pt-0.5 size-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                </svg>
            </div>
            |
            <div class="inline-flex text-blue-500">
                <a href="https://github.com/efcor/cookin" target="_blank" class="text-blue-500 mr-1">View on GitHub</a>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="pt-0.5 size-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                </svg>
            </div>
        </footer>
    </div>
</body>

</html>
