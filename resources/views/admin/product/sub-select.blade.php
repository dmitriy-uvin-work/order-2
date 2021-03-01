@if(count($categories) > 0)
    <ul class="ul">
    @foreach($categories as $category)
        <li class="{{ $category->children_count > 0 ? 'optgroup' : '' }}">
            <label><input type="checkbox" name="categories[]" value="{{ $category->id }}" {{ in_array($category->id, $categoryIds) ? 'checked' : '' }}>{{ $category->name }}</label>
            @if($category->children_count > 0)
                @include('admin.product.sub-select', ['categories' => $category->sub, 'categoryIds' => $categoryIds, 'level'=>0])
            @endif
        </li>
    @endforeach
    </ul>
@endif
