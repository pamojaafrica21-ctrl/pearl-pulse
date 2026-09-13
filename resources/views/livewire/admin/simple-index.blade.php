<div>
    <div class="flex justify-end mb-6">
        <a href="{{ $createRoute }}" wire:navigate class="btn-primary text-xs">Add {{ $label }}</a>
    </div>
    <div class="bg-white border border-sand-deep/30 overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-cream text-left text-xs tracking-wider uppercase text-muted">
                <tr>
                    @foreach($columns as $column)
                        <th class="px-4 py-3">{{ ucfirst(str_replace('_', ' ', $column)) }}</th>
                    @endforeach
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-sand-deep/20">
                @foreach($rows as $row)
                    <tr>
                        @foreach($columns as $column)
                            <td class="px-4 py-3">
                                @if($column === 'approved')
                                    {{ $row->approved ? 'Yes' : 'No' }}
                                @elseif($column === 'title')
                                    {{ $row->title }}
                                @elseif($column === 'guest_name')
                                    {{ $row->guest_name }}
                                @elseif($column === 'question')
                                    {{ $row->question }}
                                @else
                                    {{ $row->{$column} }}
                                @endif
                            </td>
                        @endforeach
                        <td class="px-4 py-3 text-right space-x-3">
                            <a href="{{ route($editRoute, $row) }}" wire:navigate class="text-forest hover:underline">Edit</a>
                            <button type="button" wire:click="$set('confirmingDelete', {{ $row->id }})" class="text-red-700 hover:underline">Delete</button>
                        </td>
                    </tr>
                @endforeach
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
