<div class="glass-panel rounded-2xl p-6 overflow-x-auto">
    <div class="font-mono text-sm text-sky-400 leading-relaxed whitespace-pre" style="font-family: 'JetBrains Mono', monospace;">
@php
    function renderTree($items, $prefix = '') {
        $output = '';
        $count = count($items);
        
        foreach ($items as $index => $item) {
            $isLastItem = ($index === $count - 1);
            $connector = $isLastItem ? '└── ' : '├── ';
            
            // Si es una Categoría (Carpeta)
            if ($item instanceof \App\Models\Categoria) {
                $output .= $prefix . $connector . '<span class="text-white font-bold">' . $item->nombre . '</span>' . "\n";
                
                $newPrefix = $prefix . ($isLastItem ? '    ' : '│   ');
                
                // Combinar hijos y documentos para procesarlos juntos
                $children = $item->childrenRecursive ?? collect();
                $docs = $item->documentos ?? collect();
                $merged = $children->concat($docs);
                
                if ($merged->count() > 0) {
                    $output .= renderTree($merged, $newPrefix);
                }
            } 
            // Si es un Documento (Archivo)
            else {
                $output .= $prefix . $connector . '<span class="text-slate-400">' . $item->titulo . '</span>' . "\n";
            }
        }
        return $output;
    }
@endphp
{!! renderTree($tree) !!}
    </div>
</div>
