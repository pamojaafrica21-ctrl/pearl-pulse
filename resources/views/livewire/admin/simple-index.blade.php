@php
    use App\Services\ImageUploader;
    $imageUploader = app(ImageUploader::class);
    $normalizedColumns = collect($columns)->map(function ($column) {
        if (is_string($column)) {
            return [
                'key' => $column,
                'label' => ucfirst(str_replace('_', ' ', $column)),
                'type' => match ($column) {
                    'status' => 'status',
                    'approved', 'is_featured', 'is_signature', 'is_multi_country' => 'bool',
                    'cover', 'cover_path' => 'cover',
                    'updated_at', 'created_at' => 'date',
                    'video' => 'video',
                    default => 'text',
                },
            ];
        }

        return array_merge([
            'label' => ucfirst(str_replace('_', ' ', $column['key'] ?? '')),
            'type' => 'text',
        ], $column);
    });
@endphp

<div>
    <div class="flex justify-end mb-6">
        <a href="{{ $createRoute }}" wire:navigate class="btn-primary text-xs">Add {{ $label }}</a>
    </div>
    <div class="bg-white border border-sand-deep/30 overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-cream text-left text-xs tracking-wider uppercase text-muted">
                <tr>
                    @foreach($normalizedColumns as $column)
                        <th class="px-4 py-3 whitespace-nowrap">{{ $column['label'] }}</th>
                    @endforeach
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-sand-deep/20">
                @forelse($rows as $row)
                    <tr wire:key="row-{{ $row->id }}">
                        @foreach($normalizedColumns as $column)
                            @php
                                $key = $column['key'];
                                $type = $column['type'] ?? 'text';
                                $value = data_get($row, $key);
                            @endphp
                            <td class="px-4 py-3 align-middle">
                                @if($type === 'cover')
                                    @if($row->cover_path)
                                        <img src="{{ $imageUploader->thumbUrl($row->cover_path) }}" alt="" class="h-12 w-16 object-cover">
                                    @else
                                        <div class="h-12 w-16 bg-forest/10"></div>
                                    @endif
                                @elseif($type === 'primary')
                                    <div class="font-medium text-forest">{{ $value }}</div>
                                    @if(!empty($column['meta']))
                                        <div class="text-xs text-muted mt-0.5">{{ data_get($row, $column['meta']) }}</div>
                                    @endif
                                @elseif($type === 'status')
                                    <span class="inline-block px-2 py-0.5 text-xs uppercase tracking-wide {{ $value === 'published' ? 'bg-forest/10 text-forest' : 'bg-sand text-muted' }}">
                                        {{ $value }}
                                    </span>
                                    @if(!empty($column['badges']))
                                        @foreach($column['badges'] as $badge)
                                            @if(data_get($row, $badge['key']))
                                                <span class="ml-1 text-xs text-gold">{{ $badge['label'] }}</span>
                                            @endif
                                        @endforeach
                                    @endif
                                @elseif($type === 'bool')
                                    <span class="text-xs uppercase tracking-wide {{ $value ? 'text-forest' : 'text-muted' }}">
                                        {{ $value ? ($column['true_label'] ?? 'Yes') : ($column['false_label'] ?? 'No') }}
                                    </span>
                                @elseif($type === 'date')
                                    <span class="text-muted whitespace-nowrap">{{ $value?->format('d M Y') }}</span>
                                @elseif($type === 'video')
                                    @if(method_exists($row, 'hasVideo') && $row->hasVideo())
                                        <span class="inline-block px-2 py-0.5 text-xs uppercase tracking-wide bg-gold/15 text-gold">Video</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                @elseif($type === 'relation')
                                    {{ data_get($row, $column['relation']) ?: '—' }}
                                @else
                                    @php
                                        $display = is_string($value) ? \Illuminate\Support\Str::limit($value, 80) : ($value ?? '—');
                                    @endphp
                                    {{ $display }}
                                @endif
                            </td>
                        @endforeach
                        <td class="px-4 py-3 text-right space-x-3 whitespace-nowrap">
                            <a href="{{ route($editRoute, $row) }}" wire:navigate class="text-forest hover:underline">Edit</a>
                            <button type="button" wire:click="$set('confirmingDelete', {{ $row->id }})" class="text-red-700 hover:underline">Delete</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ $normalizedColumns->count() + 1 }}" class="px-4 py-8 text-center text-muted">No {{ \Illuminate\Support\Str::plural($label) }} yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-6">{{ $rows->links() }}</div>
    @if($confirmingDelete ?? false)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4">
            <div class="bg-white p-6 max-w-md w-full border border-sand-deep/30">
                <h3 class="font-display text-2xl text-forest">Delete {{ $label }}?</h3>
                <div class="mt-6 flex gap-3 justify-end">
                    <button type="button" wire:click="$set('confirmingDelete', null)" class="px-4 py-2 text-sm border">Cancel</button>
                    <button type="button" wire:click="delete" class="px-4 py-2 text-sm bg-red-700 text-white">Delete</button>
                </div>
            </div>
        </div>
    @endif
</div>
