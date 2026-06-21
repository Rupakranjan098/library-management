<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Barcodes - Library Management</title>
    <style>
        body {
            font-family: system-ui, -apple-system, sans-serif;
            background-color: #0f172a;
            color: #f8fafc;
            margin: 0;
            padding: 0;
        }
        .header-bar {
            background-color: #1e293b;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #334155;
        }
        .btn {
            background-color: #4f46e5;
            color: white;
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 0.5rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            font-size: 0.875rem;
            transition: background-color 0.2s;
        }
        .btn:hover {
            background-color: #4338ca;
        }
        .btn-secondary {
            background-color: #334155;
            color: #cbd5e1;
            margin-right: 0.5rem;
        }
        .btn-secondary:hover {
            background-color: #475569;
            color: white;
        }
        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        .grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            background-color: white;
            padding: 15px;
            border-radius: 0.5rem;
        }
        .label-cell {
            border: 1px dashed #cbd5e1;
            padding: 12px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background-color: white;
            color: black;
            height: 100px;
            box-sizing: border-box;
            text-align: center;
            border-radius: 4px;
        }
        .book-title {
            font-size: 10px;
            font-weight: bold;
            max-width: 100%;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin-bottom: 6px;
        }
        .barcode-img {
            display: flex;
            justify-content: center;
            margin-bottom: 4px;
        }
        .barcode-id {
            font-family: monospace;
            font-size: 10px;
            font-weight: bold;
            letter-spacing: 1.5px;
            margin-top: 2px;
        }
        /* Laravel Pagination Styles override */
        .pagination-container {
            margin-top: 2rem;
            display: flex;
            justify-content: center;
        }
        .pagination-container nav {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1rem;
            width: 100%;
        }
        /* Mobile block - hide it */
        .pagination-container nav > div:first-child {
            display: none !important;
        }
        /* Desktop block - style it */
        .pagination-container nav > div:last-child {
            display: flex !important;
            flex-direction: column;
            align-items: center;
            gap: 0.75rem;
            width: 100%;
        }
        .pagination-container nav > div:last-child > div:first-child p {
            font-size: 0.875rem;
            color: #94a3b8;
            margin: 0;
            text-align: center;
        }
        .pagination-container nav > div:last-child > div:first-child p span {
            font-weight: 600;
            color: #cbd5e1;
        }
        .pagination-container .pagination {
            display: inline-flex;
            list-style: none;
            padding: 0;
            margin: 0;
            border-radius: 0.5rem;
            overflow: hidden;
            border: 1px solid #334155;
            background-color: #1e293b;
        }
        .pagination-container .page-item {
            display: inline;
        }
        .pagination-container .page-link,
        .pagination-container .page-item span {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0.5rem 1rem;
            color: #cbd5e1;
            background-color: transparent;
            border: none;
            border-right: 1px solid #334155;
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 600;
            transition: all 0.2s;
            cursor: pointer;
        }
        .pagination-container .page-item:last-child .page-link,
        .pagination-container .page-item:last-child span {
            border-right: none;
        }
        .pagination-container .page-item.active span,
        .pagination-container .page-item.active .page-link {
            background-color: #4f46e5;
            color: white;
        }
        .pagination-container .page-item.disabled span,
        .pagination-container .page-item.disabled .page-link {
            color: #475569;
            pointer-events: none;
            background-color: #0f172a;
        }
        .pagination-container .page-link:hover:not(.disabled) {
            background-color: #334155;
            color: white;
        }
        .pagination-container svg {
            width: 1rem !important;
            height: 1rem !important;
            display: inline-block;
            vertical-align: middle;
            fill: currentColor;
        }

        @media print {
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            body {
                background-color: white;
                color: black;
            }
            .no-print {
                display: none !important;
            }
            .container {
                margin: 0;
                padding: 0;
                max-width: 100%;
            }
            .grid {
                grid-template-columns: repeat(4, 1fr);
                gap: 0;
                padding: 0;
                background-color: transparent;
            }
            .label-cell {
                border: 1px solid #e2e8f0;
                border-radius: 0;
                page-break-inside: avoid;
                break-inside: avoid;
                height: 105px;
            }
        }
    </style>
</head>
<body>
    <div class="header-bar no-print">
        <div>
            <h1 style="margin: 0; font-size: 1.25rem; font-weight: 700;">Print Book Barcodes</h1>
            <p style="margin: 0.25rem 0 0 0; font-size: 0.875rem; color: #94a3b8;">
                Showing {{ $books->count() }} barcodes of {{ $books->total() }} total. Ready for printing.
            </p>
        </div>
        <div style="display: flex; align-items: center;">
            <!-- Layout Configuration Controls -->
            <div style="display: flex; gap: 1rem; align-items: center; background: #334155; padding: 0.5rem 1rem; border-radius: 0.5rem; margin-right: 1rem;">
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <label for="config-cols" style="font-size: 0.75rem; color: #cbd5e1; font-weight: 600;">Columns:</label>
                    <select id="config-cols" onchange="updateLayout()" style="background: #0f172a; border: 1px solid #475569; color: white; border-radius: 0.25rem; padding: 0.25rem; font-size: 0.875rem;">
                        <option value="1">1 Column</option>
                        <option value="2">2 Columns</option>
                        <option value="3">3 Columns</option>
                        <option value="4" selected>4 Columns</option>
                        <option value="5">5 Columns</option>
                        <option value="6">6 Columns</option>
                    </select>
                </div>
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <label for="config-height" style="font-size: 0.75rem; color: #cbd5e1; font-weight: 600;">Height (px):</label>
                    <input type="number" id="config-height" value="100" min="60" max="250" oninput="updateLayout()" style="width: 55px; background: #0f172a; border: 1px solid #475569; color: white; border-radius: 0.25rem; padding: 0.25rem; font-size: 0.875rem; text-align: center;">
                </div>
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <label for="config-font-size" style="font-size: 0.75rem; color: #cbd5e1; font-weight: 600;">Font (px):</label>
                    <input type="number" id="config-font-size" value="10" min="6" max="24" oninput="updateLayout()" style="width: 50px; background: #0f172a; border: 1px solid #475569; color: white; border-radius: 0.25rem; padding: 0.25rem; font-size: 0.875rem; text-align: center;">
                </div>
            </div>

            <a href="{{ route('books.index') }}" class="btn btn-secondary">Back to Books</a>
            <a id="pdf-download-link" href="{{ request()->fullUrlWithQuery(['download' => 'pdf', 'cols' => 4, 'height' => 100, 'font_size' => 10]) }}" class="btn bg-rose-600 hover:bg-rose-700 mr-2" style="background-color: #e11d48; margin-right: 0.5rem;">
                <svg style="width: 1.25rem; height: 1.25rem; margin-right: 0.5rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Download PDF
            </a>
            <button onclick="triggerPrint()" class="btn">
                <svg style="width: 1.25rem; height: 1.25rem; margin-right: 0.5rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Print Barcodes
            </button>
        </div>
    </div>

    <div class="container">
        <div class="grid">
            @foreach($books as $book)
                <div class="label-cell">
                    <div class="book-title">{{ $book->title }}</div>
                    <div class="barcode-img">
                        {!! DNS1D::getBarcodeHTML($book->barcode_id, 'C128', 1.2, 35) !!}
                    </div>
                    <div class="barcode-id">{{ $book->barcode_id }}</div>
                </div>
            @endforeach
        </div>

        @if($books->hasPages())
            <div class="pagination-container no-print">
                {{ $books->links() }}
            </div>
        @endif
    </div>

    <script>
        function updateLayout() {
            const cols = document.getElementById('config-cols').value;
            const height = document.getElementById('config-height').value;
            const fontSize = document.getElementById('config-font-size').value;
            
            // Update grid cols
            const grid = document.querySelector('.grid');
            if (grid) {
                grid.style.gridTemplateColumns = `repeat(${cols}, 1fr)`;
            }
            
            // Update labels styles
            document.querySelectorAll('.label-cell').forEach(cell => {
                cell.style.height = `${height}px`;
            });
            
            document.querySelectorAll('.book-title').forEach(title => {
                title.style.fontSize = `${fontSize}px`;
            });
            
            document.querySelectorAll('.barcode-id').forEach(id => {
                id.style.fontSize = `${fontSize}px`;
            });

            // Update Print style block for print media columns
            let styleBlock = document.getElementById('dynamic-print-styles');
            if (!styleBlock) {
                styleBlock = document.createElement('style');
                styleBlock.id = 'dynamic-print-styles';
                document.head.appendChild(styleBlock);
            }
            styleBlock.innerHTML = `
                @media print {
                    * {
                        -webkit-print-color-adjust: exact !important;
                        print-color-adjust: exact !important;
                    }
                    .grid {
                        grid-template-columns: repeat(${cols}, 1fr) !important;
                        gap: 0 !important;
                        padding: 0 !important;
                    }
                    .label-cell {
                        height: ${height}px !important;
                        border: 1px solid #cbd5e1 !important;
                        border-radius: 0 !important;
                        page-break-inside: avoid !important;
                        break-inside: avoid !important;
                    }
                    .book-title {
                        font-size: ${fontSize}px !important;
                    }
                    .barcode-id {
                        font-size: ${fontSize}px !important;
                    }
                }
            `;
            
            // Update PDF link href
            const pdfLink = document.getElementById('pdf-download-link');
            if (pdfLink) {
                const url = new URL(pdfLink.href);
                url.searchParams.set('cols', cols);
                url.searchParams.set('height', height);
                url.searchParams.set('font_size', fontSize);
                pdfLink.href = url.toString();
            }
        }

        async function triggerPrint() {
            window.print();
            
            // Call AJAX to mark these books as printed
            try {
                const response = await fetch("{{ route('books.mark-printed') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        ids: @json($books->pluck('id')->toArray())
                    })
                });
                const data = await response.json();
                console.log('Marked printed status:', data);
            } catch (e) {
                console.error('Error marking as printed:', e);
            }
        }

        // Initialize layout on load
        window.addEventListener('DOMContentLoaded', updateLayout);
    </script>
</body>
</html>
