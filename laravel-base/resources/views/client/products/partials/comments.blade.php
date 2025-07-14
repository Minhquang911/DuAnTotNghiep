<div id="comments-container" data-product-slug="{{ isset($product) ? $product->slug : '' }}">
@forelse($comments as $index => $comment)
    <div class="review-items mb-4">
        <div class="review-wrap-area d-flex gap-4">
            <div class="review-thumb">
                <img src="{{ $comment->user->avatar ?? asset('dist/assets/img/avatar' . ($index + 1) . '.png') }}"
                    class="rounded-circle" width="48" height="48"
                    alt="{{ $comment->user->name }}">
            </div>
            <div class="review-content">
                <div
                    class="head-area d-flex flex-wrap gap-2 align-items-center justify-content-between">
                    <div class="cont">
                        <h5><a href="news-details.html">{{ $comment->user->name }}</a></h5>
                        <span>
                            <i class="far fa-clock me-1"></i>
                            {{ $comment->created_at->format('d/m/Y H:i') }}
                        </span>
                    </div>
                </div>
                <p class="mt-30 mb-4">
                    {{ $comment->content }}
                </p>
            </div>
        </div>
    </div>
@empty
    <div class="text-center py-4">
        <div class="text-muted">
            <i class="fas fa-comments fa-2x mb-3"></i>
            <p class="mb-0">Chưa có bình luận nào.</p>
        </div>
    </div>
@endforelse

@if($comments->hasPages())
    <div class="comments-pagination pagination-container">
        {{ $comments->links('pagination::bootstrap-5') }}
    </div>
@endif 
</div> 