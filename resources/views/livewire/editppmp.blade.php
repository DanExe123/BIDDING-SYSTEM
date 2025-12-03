<div x-data="{ open: false }" @ppmp-updated.window="open = false">
    <!-- Button trigger -->
    <button @click="open = true" class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600">
        Edit
    </button>

    <!-- Modal -->
    <div x-show="open" x-transition class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 overflow-y-auto p-4">
        <div class="bg-white w-full max-w-4xl rounded shadow-lg relative overflow-y-auto max-h-[90vh]" @click.away="open = false">
            <button @click="open = false" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">&times;</button>

            <form wire:submit.prevent="save" enctype="multipart/form-data" class="mt-6">
                <div class="flex justify-center items-center w-full">
                    <div class="bg-white  w-full">
                        <div class=" p-4">
                            <h2 class="text-gray-800 py-2 font-bold">Edit Purchase Request</h2>

                            <div class="px-6 py-4 space-y-4 text-sm text-gray-700">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block font-medium mb-1">Project Title*</label>
                                        <input type="text" wire:model.defer="project_title"
                                            placeholder="Project Title"
                                            class="w-full border rounded px-3 py-2" />
                                        @error('project_title')
                                            <span class="text-red-500">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block font-medium mb-1">Project Type*</label>
                                        <input type="text" wire:model.defer="project_type"
                                            placeholder="Education, Infrastructure..."
                                            class="w-full border rounded px-3 py-2" />
                                        @error('project_type')
                                            <span class="text-red-500">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div wire:poll>
                                        <label class="block font-medium mb-1">ABC (Approved Budget)*</label>
                                        <input type="number" wire:model="abc" readonly
                                            class="w-full border rounded px-3 py-2 " />
                                        @error('abc')
                                            <span class="text-red-500">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block font-medium mb-1">Implementing Unit*</label>
                                        <input type="text" wire:model.defer="implementing_unit"
                                            placeholder="Office/Department"
                                            class="w-full border rounded px-3 py-2" />
                                        @error('implementing_unit')
                                            <span class="text-red-500">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Procurement Items --}}
                                <div wire:poll class="mt-6">
                                    <label class="block font-medium mb-2">Procurement Items</label>
                                    <table class="w-full table-auto border border-gray-300 text-sm">
                                        <thead class="bg-gray-100">
                                            <tr>
                                                <th class="border p-2">Description</th>
                                                <th class="border p-2">Qty</th>
                                                <th class="border p-2">Unit</th>
                                                <th class="border p-2">Estimated Unit Cost</th>
                                                <th class="border p-2">Total</th>
                                                <th class="border p-2">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($items as $index => $item)
                                                <tr>
                                                    <td class="border p-2">
                                                        <input type="text"
                                                            wire:model.defer="items.{{ $index }}.description"
                                                            class="w-full border rounded px-2 py-1" />
                                                        @error("items.$index.description")
                                                            <span class="text-red-500">{{ $message }}</span>
                                                        @enderror
                                                    </td>
                                                    <td class="border p-2">
                                                        <input type="number" min="1"
                                                            wire:model.defer="items.{{ $index }}.qty"
                                                            class="w-full border rounded px-2 py-1" />
                                                    </td>
                                                    <td class="border p-2">
                                                        <select wire:model.defer="items.{{ $index }}.unit"
                                                                class="w-full border rounded px-2 py-1">
                                                            <option value="">-- Select Unit --</option>
                                                            <option value="pc">pc (piece)</option>
                                                            <option value="pcs">pcs (pieces)</option>
                                                            <option value="unit">unit</option>
                                                            <option value="lot">lot</option>
                                                            <option value="box">box</option>
                                                            <option value="set">set</option>
                                                            <option value="pack">pack</option>
                                                            <option value="bottle">bottle</option>
                                                            <option value="roll">roll</option>
                                                            <option value="tube">tube</option>
                                                            <option value="pair">pair</option>
                                                            <option value="ream">ream</option>
                                                            <option value="kg">kg (kilogram)</option>
                                                            <option value="g">g (gram)</option>
                                                            <option value="l">l (liter)</option>
                                                            <option value="ml">ml (milliliter)</option>
                                                            <option value="meter">meter</option>
                                                            <option value="m">m (meter)</option>
                                                            <option value="ft">ft (feet)</option>
                                                            <option value="gal">gal (gallon)</option>
                                                            <option value="carton">carton</option>
                                                            <option value="bundle">bundle</option>
                                                            <option value="dozen">dozen</option>
                                                            <option value="can">can</option>
                                                            <option value="jar">jar</option>
                                                            <option value="sack">sack</option>
                                                            <option value="bag">bag</option>
                                                            <option value="pail">pail</option>
                                                            <option value="drum">drum</option>
                                                        </select>
                                                    </td>
                                                    <td class="border p-2">
                                                        <input type="number" step="0.01"
                                                            wire:model.defer="items.{{ $index }}.unitCost"
                                                            class="w-full border rounded px-2 py-1" />
                                                    </td>
                                                    <td class="border p-2">
                                                        <input type="number" readonly
                                                            value="{{ (float) ($item['qty'] ?? 0) * (float) ($item['unitCost'] ?? 0) }}"
                                                            class="w-full border rounded px-2 py-1 bg-gray-100" />
                                                    </td>
                                                    <td class="border p-2 text-center">
                                                        <button type="button"
                                                            wire:click="removeItem({{ $index }})"
                                                            class="bg-red-500 text-white px-2 py-1 rounded">X</button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <div class="mt-3">
                                        <button type="button" wire:click="addItem"
                                            class="bg-green-500 text-white px-4 py-1 rounded hover:bg-green-600">+ Add Item</button>
                                    </div>
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block font-medium mb-1">Project Description*</label>
                                    <textarea wire:model.defer="description" class="w-full border rounded px-3 py-2" rows="4"></textarea>
                                    @error('description')
                                        <span class="text-red-500">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Attachments --}}
                                <div class="border-dashed border-gray-300 border-2 p-4 rounded-md mt-6">
                                    <label class="block font-medium mb-2">Attachments</label>
                                    <input type="file" wire:model="attachments" multiple
                                        class="w-full border rounded px-3 py-2 mb-3" />
                                    @error('attachments.*')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror

                                    {{-- Existing Attachments --}}
                                    @if($existingAttachments && count($existingAttachments) > 0)
                                        <ul class="mt-2 space-y-1 text-sm text-gray-700 w-1/2 mx-auto">
                                            @foreach($existingAttachments as $index => $filePath)
                                                @php
                                                    $fileName = $existingAttachmentNames[$index] ?? basename($filePath);
                                                @endphp
                                                <li class="flex items-center justify-between bg-gray-100 px-2 py-1 rounded">
                                                    <a href="{{ asset('storage/' . $filePath) }}" target="_blank" class="truncate text-blue-600 hover:underline">
                                                        {{ $fileName }}
                                                    </a>
                                                    <button type="button"
                                                             wire:click="removeExistingAttachment({{ $index }})"
                                                            class="text-red-500 hover:text-red-700 font-bold px-2 py-0.5 rounded">
                                                        &times;
                                                    </button>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif

                                    {{-- Newly uploaded attachments --}}
                                    @if($attachments && count($attachments) > 0)
                                        <ul class="mt-2 space-y-1 text-sm text-gray-700 w-1/2 mx-auto">
                                            @foreach($attachments as $index => $file)
                                                <li class="flex items-center justify-between bg-gray-100 px-2 py-1 rounded">
                                                    <span class="truncate">{{ $file->getClientOriginalName() }}</span>
                                                    <button type="button"
                                                            wire:click="removeAttachment({{ $index }})"
                                                            class="text-red-500 hover:text-red-700 font-bold px-2 py-0.5 rounded">
                                                        &times;
                                                    </button>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif

                                </div>
                            </div>

                            {{-- Submit --}}
                            <div class="px-6 py-4 flex justify-end items-center border-t border-gray-300 bg-[#F9FAFB]">
                                <button type="submit"
                                    class="px-4 py-2 text-sm bg-blue-600 text-white rounded hover:bg-blue-700">
                                    Update PPMP
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
