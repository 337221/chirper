<x-app-layout>
    <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
        <form method="POST" action="{{ route('chirps.store') }}">
            @csrf
            <textarea name="message" placeholder="{{ __('What\'s on your mind?') }}"
                class="block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">{{ old('message') }}</textarea>
            <x-input-error :messages="$errors->get('message')" class="mt-2" />
            <x-primary-button class="mt-4">{{ __('Chirp') }}</x-primary-button>
        </form>
        <div class="mt-6 bg-white shadow-sm rounded-lg divide-y">
            @foreach ($chirps as $chirp)
                <div class="p-6">
                    <div class="flex justify-between items-center">
                        <div>
                            <span class="text-gray-800">{{ $chirp->user->name }}</span>
                            <small class="ml-2 text-sm text-gray-600">{{ $chirp->created_at->format('j M Y, g:i a') }}</small>
                            @unless ($chirp->created_at->eq($chirp->updated_at))
                                <small class="text-sm text-gray-600"> &middot; {{ __('edited') }}</small>
                            @endunless
                        </div>
                        @if ($chirp->user->is(auth()->user()))
                            <div class="flex items-center">
                                <a href="{{ route('chirps.edit', $chirp) }}" class="text-blue-500 mr-2">Edit</a>
                                <form method="POST" action="{{ route('chirps.destroy', $chirp) }}" style="display:inline;">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" class="text-red-500">Delete</button>
                                </form>
                            </div>
                        @endif
                    </div>
                    <p class="mt-4 text-lg text-gray-900">{{ $chirp->message }}</p>
                    <div style="margin-top: 1rem;">
                        <button onclick="document.getElementById('reply-form-{{ $chirp->id }}').style.display = 'block'" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:border-gray-700 focus:ring focus:ring-gray-300 active:bg-gray-900 transition ease-in-out duration-150">
                            {{ __('Reply') }}
                        </button>
                    </div>
                    <div id="reply-form-{{ $chirp->id }}" style="display: none;" class="mt-4">
                        <form method="POST" action="{{ route('chirps.store') }}">
                            @csrf
                            <textarea name="message" placeholder="Reply to this chirp..."
                                class="block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"></textarea>
                            <input type="hidden" name="parent_id" value="{{ $chirp->id }}" />
                            <x-primary-button class="mt-4">{{ __('Post Reply') }}</x-primary-button>
                        </form>
                    </div>

                    @foreach ($chirp->replies as $reply)
                        <div class="ml-12 mt-4 bg-gray-100 rounded-lg p-4">
                            <div class="flex justify-between">
                                <div>
                                    <strong>{{ $reply->user->name }}:</strong> {{ $reply->message }}
                                    <div class="text-xs text-gray-600">{{ $reply->created_at->format('j M Y, g:i a') }}</div>
                                </div>
                                @if ($reply->user->is(auth()->user()))
                                    <div class="flex items-center">
                                        <a href="{{ route('chirps.edit', $reply) }}" class="text-blue-500 mr-2">Edit</a>
                                        <form method="POST" action="{{ route('chirps.destroy', $reply) }}" style="display:inline;">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="text-red-500">Delete</button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
