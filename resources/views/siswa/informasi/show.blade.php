@extends('layouts.student')
@section('title', $a->judul)
@section('heading', 'Informasi')
@section('back', route('siswa.informasi.index'))

@section('content')
<article>
    @if ($a->image_url)
        <img src="{{ $a->image_url }}" alt="Gambar untuk {{ $a->judul }}" class="mb-5 max-h-72 w-full rounded-xl border-2 border-line object-cover">
    @else
        <div class="mb-5 flex h-36 items-center justify-center rounded-xl border-2 border-line bg-soft text-primary"><x-icon name="book-open" :size="56" /></div>
    @endif
    <span class="chip chip-soft">{{ $cats[$a->kategori] ?? $a->kategori }}</span>
    <h1 class="mt-2 text-[24px] leading-tight">{{ $a->judul }}</h1>
    <p class="mt-1 text-xs text-muted">{{ $a->published_at->translatedFormat('d F Y') }} · {{ $a->author->name }}</p>
    <div class="mt-5 space-y-4 text-[15px] leading-relaxed whitespace-pre-line">{{ $a->isi }}</div>
</article>
<button type="button" class="btn btn-outline btn-block mt-6" x-data="{ ok: false, async share() {
        const url = location.href, title = @js($a->judul);
        if (navigator.share) { try { await navigator.share({ title, url }); return; } catch (e) { if (e && e.name === 'AbortError') return; } }
        try { await navigator.clipboard.writeText(url); }
        catch (e) { const t = document.createElement('textarea'); t.value = url; t.style.position = 'fixed'; t.style.opacity = '0'; document.body.appendChild(t); t.select(); try { document.execCommand('copy'); } catch (_) {} t.remove(); }
        this.ok = true; setTimeout(() => this.ok = false, 2000);
    } }" @click="share()"><x-icon name="send" :size="18" /><span x-text="ok ? 'Tautan disalin' : 'Bagikan'">Bagikan</span></button>
@endsection
