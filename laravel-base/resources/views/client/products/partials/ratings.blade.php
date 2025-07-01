@forelse($ratings as $index => $rating)
    <div class="review-items mb-4">
        <div class="review-wrap-area d-flex gap-4">
            <div class="review-thumb">
                <img src="{{ $rating->user->avatar ?? asset('dist/assets/img/avatar' . ($index + 1) . '.png') }}"
                    class="rounded-circle" width="48" height="48"
                    alt="{{ $rating->user->name }}">
            </div>
            <div class="review-content">
                <div
                    class="head-area d-flex flex-wrap gap-2 align-items-center justify-content-between">
                    <div class="cont">
                        <h5><a href="news-details.html">{{ $rating->user->name }}</a></h5>
                        <span>
                            <i class="far fa-clock me-1"></i>
                            {{ $rating->created_at->format('d/m/Y H:i') }}
                        </span>
                    </div>
                    <div class="star">
                        @for ($i = 1; $i <= 5; $i++)
                            <i
                                class="fa-star {{ $i <= $rating->rating ? 'fa-solid' : 'fa-regular' }}"></i>
                        @endfor
                    </div>
                </div>
                <p class="mt-30">
                    {{ $rating->comment }}
                </p>
                @if ($rating->reply)
                    <div class="mt-1 text-info">
                        <i class="fas fa-reply me-1"></i>
                        <small>{{ Str::limit($rating->reply, 100) }}</small>
                    </div>
                @endif
            </div>
        </div>
    </div>
@empty
    <div class="text-center py-4">
        <div class="text-muted">
            <i class="fas fa-star fa-2x mb-3"></i>
            <p class="mb-0">Chưa có đánh giá nào.</p>
        </div>
    </div>
@endforelse

@if($ratings->hasPages())
    <div class="ratings-pagination pagination-container">
        {{ $ratings->links('pagination::bootstrap-5') }}
    </div>
@endif 