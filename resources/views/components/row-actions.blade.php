@props(['edit', 'destroy', 'view' => null, 'confirm' => 'Are you sure you want to delete this item?'])

<div class="flex justify-end items-center space-x-3">
    @if ($view)
        <a href="{{ $view }}" target="_blank" class="text-gray-500 hover:text-gray-800" title="View on website"><i class="fas fa-eye"></i></a>
    @endif
    <a href="{{ $edit }}" class="text-indigo-600 hover:text-indigo-900" title="Edit"><i class="fas fa-edit"></i></a>
    <form action="{{ $destroy }}" method="POST" class="inline-block">
        @csrf
        @method('DELETE')
        <button type="submit" class="text-red-600 hover:text-red-900" title="Delete" onclick="return confirm(@js($confirm))"><i class="fas fa-trash"></i></button>
    </form>
</div>
