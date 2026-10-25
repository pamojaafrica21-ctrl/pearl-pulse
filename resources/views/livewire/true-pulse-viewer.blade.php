<div class="true-pulse-feed" @keydown.escape.window="$wire.close()">
    @if($items->isNotEmpty())
        <div class="true-pulse-feed__head">
            <h2 class="true-pulse-feed__title">Real guests. Real moments.</h2>
            @if(!empty($instagramUrl))
                <a href="{{ $instagramUrl }}" target="_blank" rel="noopener" class="true-pulse-feed__ig-link">
                    <span class="true-pulse-feed__ig-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                            <rect x="3" y="3" width="18" height="18" rx="5"/>
                            <circle cx="12" cy="12" r="4"/>
                            <circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/>
                        </svg>
                    </span>
                    <span>{{ $hashtag }}</span>
                    <span aria-hidden="true">→</span>
                </a>
            @endif
        </div>

        <div class="true-pulse-feed__strip" role="list">
            @foreach($items as $item)
                <button
                    type="button"
                    role="listitem"
                    wire:key="ig-{{ $item->id }}"
                    wire:click="open({{ $item->id }})"
                    class="true-pulse-feed__card"
                    aria-label="View Instagram post{{ $item->caption ? ': '.\Illuminate\Support\Str::limit($item->caption, 60) : '' }}"
                >
                    @if($item->coverUrl())
                        <img src="{{ $item->coverUrl() }}" alt="" loading="lazy" decoding="async" class="true-pulse-feed__img">
                    @else
                        <span class="true-pulse-feed__placeholder"></span>
                    @endif
                    @if($item->isVideo())
                        <span class="true-pulse-feed__play" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
                        </span>
                    @endif
                    <span class="true-pulse-feed__meta">
                        <span class="true-pulse-feed__stat">
                            <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                            {{ $item->formattedLikeCount() }}
                        </span>
                        <span class="true-pulse-feed__stat">
                            <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"/></svg>
                            {{ $item->formattedCommentsCount() }}
                        </span>
                        @if($item->posted_at)
                            <span class="true-pulse-feed__time">{{ $item->relativePostedAt() }}</span>
                        @endif
                    </span>
                </button>
            @endforeach
        </div>
    @else
        <div class="true-pulse-feed__empty">
            <div class="true-pulse-feed__head">
                <h2 class="true-pulse-feed__title">Real guests. Real moments.</h2>
                @if(!empty($instagramUrl))
                    <a href="{{ $instagramUrl }}" target="_blank" rel="noopener" class="true-pulse-feed__ig-link">
                        <span class="true-pulse-feed__ig-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                                <rect x="3" y="3" width="18" height="18" rx="5"/>
                                <circle cx="12" cy="12" r="4"/>
                                <circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/>
                            </svg>
                        </span>
                        <span>{{ $hashtag }}</span>
                        <span aria-hidden="true">→</span>
                    </a>
                @endif
            </div>
            <p class="text-muted mt-6">{{ $configured ? 'Stories are syncing — check back soon, or follow along on Instagram.' : 'True Pulse will appear here once Instagram is connected in Settings.' }}</p>
            @if(!empty($instagramUrl))
                <a href="{{ $instagramUrl }}" target="_blank" rel="noopener" class="home-text-link mt-4">Follow on Instagram →</a>
            @endif
        </div>
    @endif

    @if($selected)
        <div
            class="true-pulse-viewer"
            role="dialog"
            aria-modal="true"
            aria-label="Instagram post"
            wire:click.self="close"
        >
            <div class="true-pulse-viewer__panel">
                <button type="button" class="true-pulse-viewer__close" wire:click="close" aria-label="Close">×</button>

                <div class="true-pulse-viewer__media">
                    @if($selected->isVideo() && $selected->media_url)
                        <video
                            class="true-pulse-viewer__video"
                            controls
                            playsinline
                            autoplay
                            poster="{{ $selected->thumbnail_url }}"
                            src="{{ $selected->media_url }}"
                        ></video>
                    @elseif($selected->coverUrl())
                        <img src="{{ $selected->coverUrl() }}" alt="" class="true-pulse-viewer__image">
                    @endif
                </div>

                <div class="true-pulse-viewer__side">
                    <div class="true-pulse-viewer__counts">
                        <span>
                            <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                            {{ $selected->formattedLikeCount() }} likes
                        </span>
                        <span>
                            <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"/></svg>
                            {{ $selected->formattedCommentsCount() }} comments
                        </span>
                    </div>

                    @if($selected->caption)
                        <p class="true-pulse-viewer__caption">{{ $selected->caption }}</p>
                    @endif

                    <div class="true-pulse-viewer__comments">
                        <p class="true-pulse-viewer__comments-label">Comments</p>
                        @forelse($selected->comments as $comment)
                            <div class="true-pulse-viewer__comment" wire:key="c-{{ $comment->id }}">
                                <p class="true-pulse-viewer__comment-user">{{ $comment->username ?: 'guest' }}</p>
                                <p class="true-pulse-viewer__comment-text">{{ $comment->text }}</p>
                            </div>
                        @empty
                            <p class="true-pulse-viewer__comment-empty">No comments yet.</p>
                        @endforelse
                    </div>

                    <div class="true-pulse-viewer__actions">
                        @if($selected->permalink)
                            <a href="{{ $selected->permalink }}" target="_blank" rel="noopener" class="btn-primary text-xs">Leave a comment</a>
                            <a href="{{ $selected->permalink }}" target="_blank" rel="noopener" class="btn-outline-dark text-xs">Open on Instagram</a>
                            <button
                                type="button"
                                class="true-pulse-viewer__share"
                                x-data
                                @click="
                                    if (navigator.share) {
                                        navigator.share({ url: @js($selected->permalink), title: 'Pearl Pulse Safaris' }).catch(() => {});
                                    } else {
                                        navigator.clipboard.writeText(@js($selected->permalink));
                                        $el.textContent = 'Link copied';
                                        setTimeout(() => $el.textContent = 'Share', 2000);
                                    }
                                "
                            >Share</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
