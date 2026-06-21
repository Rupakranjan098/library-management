<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Barcodes PDF - Library Management</title>
    <style>
        body {
            font-family: sans-serif;
            margin: 0;
            padding: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        td {
            width: {{ 100 / $cols }}%;
            padding: 10px;
            border: 1px dashed #cbd5e1;
            text-align: center;
            vertical-align: middle;
            box-sizing: border-box;
            height: {{ $height }}px;
        }
        .book-title {
            font-size: {{ $fontSize }}px;
            font-weight: bold;
            margin-bottom: 4px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .barcode-img {
            margin-bottom: 4px;
            display: inline-block;
        }
        .barcode-id {
            font-family: monospace;
            font-size: {{ $fontSize }}px;
            font-weight: bold;
            letter-spacing: 1px;
        }
    </style>
</head>
<body>
    <table>
        @foreach($books->chunk($cols) as $chunk)
            <tr>
                @foreach($chunk as $book)
                    <td>
                        <div class="book-title">{{ $book->title }}</div>
                        <div class="barcode-img">
                            {!! DNS1D::getBarcodeHTML($book->barcode_id, 'C128', 1.0, 30) !!}
                        </div>
                        <div class="barcode-id">{{ $book->barcode_id }}</div>
                    </td>
                @endforeach
                {{-- Fill empty cells in the last row if chunk is not full --}}
                @if($chunk->count() < $cols)
                    @for($i = 0; $i < ($cols - $chunk->count()); $i++)
                        <td></td>
                    @endfor
                @endif
            </tr>
        @endforeach
    </table>
</body>
</html>
