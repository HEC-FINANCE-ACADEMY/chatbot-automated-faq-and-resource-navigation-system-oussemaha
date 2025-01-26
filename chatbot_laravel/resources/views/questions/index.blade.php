<x-app-layout>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Search Form -->
      <div class="mb-8">
          <form method="GET" action="{{ route('questions.index') }}" class="flex gap-4">
              <x-text-input 
                  type="text" 
                  name="search" 
                  class="block w-full"
                  placeholder="Search questions or answers..."
                  value="{{ old('search', $search) }}"
              />
              <x-primary-button type="submit">
                  {{ __('Search') }}
              </x-primary-button>
              @if($search)
                  <a href="{{ route('questions.index') }}" class="px-4 py-2 text-gray-600 hover:text-gray-800">
                      Clear
                  </a>
              @endif
          </form>
      </div>

      <!-- FAQ List -->
      <div class="bg-white rounded-lg shadow">
          @forelse($questions as $category => $items)
              <div class="border-b last:border-b-0">
                  <h2 class="p-6 text-xl font-semibold bg-gray-200 text-gray-800">
                      {{ $category }}
                  </h2>
                  
                  <div class="divide-y">
                      @foreach($items as $question)
                          <div x-data="{ open: false }" class="group">
                              <div class="flex items-center justify-between w-full px-6 py-4 text-left hover:bg-gray-100">
                                  <button 
                                      @click="open = !open"
                                      class="flex-1 flex justify-between items-center"
                                  >
                                      <span class="text-gray-700">{{ $question->question }}</span>
                                      <svg 
                                          class="w-5 h-5 transform transition-transform ml-4"
                                          :class="{ 'rotate-180': open }" 
                                          fill="none" 
                                          stroke="currentColor" 
                                          viewBox="0 0 24 24"
                                      >
                                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                      </svg>
                                  </button>
                                  <form 
                                      method="POST" 
                                      action="{{ route('questions.destroy', $question->id) }}"
                                      onsubmit="return confirm('Are you sure you want to delete this question?')"
                                  >
                                      @csrf
                                      @method('DELETE')
                                      <button type="submit" class="ml-4 text-red-600 hover:text-red-800">
                                          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                          </svg>
                                      </button>
                                  </form>
                              </div>
                              
                              <div x-show="open" x-collapse class="px-6 pb-4 pt-2 bg-gray-50">
                                  <div class="prose max-w-none text-gray-600">
                                      {!! nl2br(e($question->answer)) !!}
                                  </div>
                              </div>
                          </div>
                      @endforeach
                  </div>
              </div>
          @empty
              <div class="p-6 text-center text-gray-500">
                  No questions found matching your criteria.
              </div>
          @endforelse
      </div>
  </div>
</x-app-layout>