<div class="relative">
    <label for="{{ $id ?? 'barcode_id' }}" class="block text-sm font-medium text-slate-300 mb-2">{{ $label ?? 'Scan Book Barcode' }}</label>
    <input type="text" 
           name="{{ $name ?? 'barcode_id' }}" 
           id="{{ $id ?? 'barcode_id' }}" 
           required 
           autofocus 
           placeholder="{{ $placeholder ?? 'Scan barcode (e.g. LIB-000001)...' }}" 
           list="books-list"
           class="w-full bg-slate-800/50 border border-slate-700 rounded-xl py-3 px-4 text-sm text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" 
           autocomplete="off" 
           value="{{ $value ?? '' }}">
    
    <datalist id="books-list">
        @foreach(\App\Models\BookCopy::with('book')->whereNotNull('book_id')->get() as $copy)
            @if($copy->book)
                <option value="{{ $copy->barcode_id }}">{{ $copy->book->title }} ({{ $copy->barcode_id }})</option>
            @endif
        @endforeach
    </datalist>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const input = document.getElementById('{{ $id ?? "barcode_id" }}');
        if (input) {
            input.focus();
            
            // Ensure focus is kept or restored on click outside
            document.addEventListener('click', function(e) {
                if (e.target !== input && e.target.tagName !== 'BUTTON' && e.target.tagName !== 'A' && e.target.tagName !== 'SELECT' && e.target.tagName !== 'INPUT') {
                    input.focus();
                }
            });

            let debounceTimer = null;
            input.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    clearTimeout(debounceTimer);
                    
                    debounceTimer = setTimeout(() => {
                        const form = input.closest('form');
                        if (form) {
                            form.submit();
                        }
                    }, 150); // 150ms debounce to filter partial scans
                }
            });
        }
    });
</script>
