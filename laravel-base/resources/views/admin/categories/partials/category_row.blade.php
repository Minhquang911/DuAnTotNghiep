@php
    $indent = $level * 24;
@endphp
<tr @if($level > 0) style="background-color: #f8f9fa;" @endif>
    <td>{{ $category->id }}</td>
    <td>
        <span style="margin-left: {{ $indent }}px">
            @if($level == 0)
                <i class="fas fa-folder-open text-primary me-1"></i>
            @else
                <i class="fas fa-level-up-alt text-secondary me-1" style="transform: rotate(-90deg);"></i>
            @endif
            {{ $category->name }}
        </span>
    </td>
    <td class="text-muted">{{ $category->slug }}</td>
    <td>
        @if($category->parent)
            <span class="badge bg-info">{{ $category->parent->name }}</span>
        @else
            <span class="badge bg-secondary">(Gốc)</span>
        @endif
    </td>
    <td>{{ $category->description }}</td>
    <td class="text-center">
        <div class="form-check form-switch">
            <input class="form-check-input category-status-toggle" type="checkbox"
                data-category-id="{{ $category->id }}"
                {{ $category->is_active ? 'checked' : '' }}
                data-bs-toggle="tooltip"
                title="{{ $category->is_active ? 'Hoạt động' : 'Khóa' }}">
        </div>
    </td>
    <td class="text-center">
        <a href="{{ route('admin.categories.edit', $category->id) }}" 
            class="btn btn-sm btn-outline-primary"
            data-bs-toggle="tooltip" 
            title="Chỉnh sửa">
             <i class="fas fa-edit"></i>
         </a>
         <form action="{{ route('admin.categories.destroy', $category->id) }}"
            method="POST" class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit" 
                class="btn btn-sm btn-outline-danger"
                onclick="return confirm('Bạn có chắc chắn muốn xóa danh mục này?')"
                data-bs-toggle="tooltip" 
                title="Xóa">
                <i class="fas fa-trash"></i>
            </button>
        </form>
    </td>
</tr>
@if($category->children && $category->children->count())
    @foreach($category->children as $child)
        @include('admin.categories.partials.category_row', ['category' => $child, 'level' => $level + 1])
    @endforeach
@endif