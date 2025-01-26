<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
        <!-- Success Message -->
        <x-auth-session-status class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mx-auto max-w-2xl" :status="session('status')" />

        <!-- Single Question Form -->
        <div class="bg-white rounded-xl shadow-sm p-8 max-w-2xl mx-auto">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Create New FAQ Entry</h2>
            
            <form method="POST" action="{{ route('questions.store') }}" class="space-y-6">
                @csrf

                <!-- Category -->
                <div>
                    <x-input-label for="category" :value="__('Category')" class="font-medium text-gray-700" />
                    <select id="category" name="category" required
                        class="mt-2 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-4">
                        <option value="">Select a Category</option>
                        @foreach([
                            'Informations Générales sur l\'Établissement',
                        'Programmes et Cours',
                        'Admission et Inscription',
                        'Vie Étudiante',
                        'Ressources Académiques',
                        'Services de Carrière',
                        'Santé et Bien-être',
                        'Technologie et Innovation',
                        'Politiques et Règlements',
                        'Événements et Actualités',
                        'Site Web et Plateformes en Ligne',
                        'Stages et Expériences Professionnelles',
                        'Professeurs et Encadrement',
                        'Clubs Étudiants et Associations',
                        'Services Administratifs et Carte Étudiante'
                        ] as $category)
                            <option value="{{ $category }}" {{ old('category') == $category ? 'selected' : '' }}>
                                {{ $category }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('category')" class="mt-2" />
                </div>

                <!-- Question -->
                <div>
                    <x-input-label for="question" :value="__('Question')" class="font-medium text-gray-700" />
                    <x-text-input id="question" 
                        type="text" 
                        name="question" 
                        :value="old('question')" 
                        class="mt-2 block w-full py-2.5 px-4"
                        required />
                    <x-input-error :messages="$errors->get('question')" class="mt-2" />
                </div>

                <!-- Answer -->
                <div>
                    <x-input-label for="answer" :value="__('Answer')" class="font-medium text-gray-700" />
                    <textarea id="answer" 
                        name="answer"
                        required
                        rows="4"
                        class="mt-2 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-4">{{ old('answer') }}</textarea>
                    <x-input-error :messages="$errors->get('answer')" class="mt-2" />
                </div>

                <div class="flex justify-end">
                    <x-primary-button class="px-6 py-3 text-base">
                        {{ __('Create Question') }}
                    </x-primary-button>
                </div>
            </form>
        </div>

        <!-- JSON Upload Section -->
        <div class="bg-white rounded-xl shadow-sm p-8 max-w-2xl mx-auto">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Import Questions from JSON</h2>
            
            <form method="POST" action="{{ route('questions.import') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div>
                    <x-input-label for="json_file" :value="__('JSON File Upload')" class="font-medium text-gray-700" />
                    <div class="mt-2">
                        <label class="flex flex-col items-center justify-center w-full p-8 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer hover:border-indigo-500 transition-colors ">
                            <div class="space-y-1 text-center cursor-pointer">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                                <div class="text-sm text-gray-600 cursor-pointer">
                                    <span class="font-semibold text-indigo-600">Click to upload</span>
                                    <span class="ml-1">or drag and drop</span>
                                </div>
                                <p class="text-xs text-gray-500">JSON files only, max 5MB</p>
                            </div>
                            <input 
                                type="file" 
                                name="json_file" 
                                id="json_file" 
                                class="hidden"
                                accept="application/json" 
                                required
                            >
                        </label>
                    </div>
                    <x-input-error :messages="$errors->get('json_file')" class="mt-2" />
                    <x-input-error :messages="$errors->get('json_errors')" class="mt-2" />
                </div>

                <div class="flex justify-end">
                    <x-primary-button class="px-6 py-3 text-base">
                        {{ __('Import Questions') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>