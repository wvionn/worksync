@extends('admin.layouts.app')

@section('page_title', 'Notifications')

@section('content')
    <div class="space-y-6">
        <header class="flex flex-col gap-3 rounded-3xl border border-white/80 bg-white p-6 shadow-sm sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="font-['Manrope'] text-3xl font-extrabold text-slate-900">Notifications</h1>
                <p class="mt-1 text-sm text-slate-500">Activity feed untuk project, tasks, dan perubahan akun.</p>
            </div>

            <form method="POST" action="{{ route('admin.notifications.read-all') }}">
                @csrf
                <button type="submit" class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">
                    Mark All as Read ({{ $unreadCount }})
                </button>
            </form>
        </header>

        <section class="space-y-3 rounded-3xl border border-white/80 bg-white p-5 shadow-sm sm:p-6">
            @forelse ($activities as $activity)
                <article class="rounded-2xl border px-4 py-3 {{ $activity->is_read ? 'border-slate-100 bg-slate-50/70' : 'border-blue-100 bg-blue-50/60' }}">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                        <div>
                            <div class="flex items-center gap-2">
                                <h2 class="text-base font-bold text-slate-900">{{ $activity->title }}</h2>
                                <span class="rounded-full bg-white px-2 py-0.5 text-[10px] font-bold uppercase tracking-[0.12em] text-slate-500">
                                    {{ $activity->category }}
                                </span>
                                @if (! $activity->is_read)
                                    <span class="rounded-full bg-blue-600 px-2 py-0.5 text-[10px] font-bold uppercase tracking-[0.12em] text-white">New</span>
                                @endif
                            </div>

                            <p class="mt-1 text-sm text-slate-600">{{ $activity->description }}</p>
                            <p class="mt-2 text-xs font-semibold text-slate-500">
                                by {{ $activity->user?->name ?? 'System' }} · {{ ($activity->occurred_at ?? $activity->created_at)->diffForHumans() }}
                            </p>
                        </div>

                        <div class="flex gap-2 sm:shrink-0">
                            @if ($activity->link)
                                <a href="{{ $activity->link }}" class="rounded-xl border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-100">Open</a>
                            @endif

                            @if (! $activity->is_read)
                                <form method="POST" action="{{ route('admin.notifications.read', $activity) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="rounded-xl border border-blue-200 bg-blue-100 px-3 py-1.5 text-xs font-semibold text-blue-700 hover:bg-blue-200">
                                        Mark Read
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </article>
            @empty
                <p class="rounded-2xl border border-dashed border-slate-200 bg-slate-50 p-5 text-sm text-slate-500">
                    Belum ada notifikasi.
                </p>
            @endforelse

            <div>
                {{ $activities->links() }}
            </div>
        </section>
    </div>
@endsection
