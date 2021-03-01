@foreach($categories as $category)
    @if($category->iid != $data->iid)
        <option value="{{ $category->iid }}" {{ $data->parent_id == $category->iid ? 'selected' : ''}}>{{ $category->name }}</option>
    @endif
    @if($category->sub->count())
        <optgroup>
        @include('admin.category.nestable-select', ['categories' => $category->sub, 'data' => $data])
        </optgroup>
    @endif
@endforeach
