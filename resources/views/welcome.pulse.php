<ion-layout title="Welcome to Ion Framework">
    <ion-slot name="header">
        <div class="flex items-center gap-3">
            <div class="h-8 w-8 rounded-full bg-primary text-white flex items-center justify-center font-bold">
                ION
            </div>
            <h1 class="text-3xl font-semibold text-foreground">Framework</h1>
        </div>
    </ion-slot>

    <div class="mt-6 rounded-lg border border-border bg-muted/40 p-6 shadow-sm">
        <p class="text-lg text-foreground mb-4">
            🎉 Your Ion application is up and running.
        </p>

        <ul class="list-disc pl-6 space-y-2 text-muted-foreground text-sm">
            <li>Edit routes in <code class="bg-accent px-1 py-0.5 rounded text-foreground">resources/routes/app.php</code></li>
            <li>Create views in <code class="bg-accent px-1 py-0.5 rounded text-foreground">resources/views/</code></li>
            <li>Build UI with <code class="bg-accent px-1 py-0.5 rounded text-foreground">&lt;ion-layout&gt;</code> and slots</li>
            <li>Customize config in <code class="bg-accent px-1 py-0.5 rounded text-foreground">config/</code> and <code class="bg-accent px-1 py-0.5 rounded text-foreground">.env</code></li>
        </ul>

        <div class="mt-6">
            <a href="https://your-ion-docs.local" class="inline-flex items-center gap-2 px-4 py-2 rounded bg-primary text-white hover:bg-primary/90 transition text-sm font-medium">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 20h9M12 4h9M4 8h16M4 16h16" />
                </svg>
                View Documentation
            </a>
        </div>
    </div>
</ion-layout>
