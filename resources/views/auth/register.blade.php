<x-guest-layout>
    @push('styles')
    <style>
        .touch-button {
            min-height: 60px;
            cursor: pointer;
            -webkit-tap-highlight-color: transparent;
            transition: transform 0.1s ease;
        }
        .touch-button:active {
            transform: scale(0.98);
        }
    </style>
    @endpush

    <div class="w-full max-w-md mx-auto">
        <!-- Card container with POS style -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden">
            <!-- Gradient header -->
            <div class="bg-gradient-to-l from-[#6C63FF] to-[#FF6B6B] p-6 text-center">
                <div class="w-16 h-16 mx-auto bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-white mt-3">{{ __('إنشاء حساب جديد') }}</h2>
                <p class="text-white/80 text-sm">{{ __('انضم إلى نظام نقاط البيع لدينا') }}</p>
            </div>

            <div class="p-6 sm:p-8">
                <form method="POST" action="{{ route('register') }}" dir="rtl">
                    @csrf

                    <!-- Name -->
                    <div>
                        <x-input-label for="name" :value="__('الاسم')" class="text-lg font-medium text-gray-700 dark:text-gray-300 text-right" />
                        <x-text-input id="name"
                            class="block mt-1 w-full rounded-xl border-2 border-gray-200 shadow-sm focus:border-[#6C63FF] focus:ring-[#6C63FF] text-lg py-4 px-4 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300 text-right placeholder:text-right"
                            type="text"
                            name="name"
                            :value="old('name')"
                            required
                            autofocus
                            autocomplete="name"
                            placeholder="{{ __('الاسم الكامل') }}" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2 text-sm" />
                    </div>

                    <!-- Email Address -->
                    <div class="mt-6">
                        <x-input-label for="email" :value="__('البريد الإلكتروني')" class="text-lg font-medium text-gray-700 dark:text-gray-300 text-right" />
                        <x-text-input id="email"
                            class="block mt-1 w-full rounded-xl border-2 border-gray-200 shadow-sm focus:border-[#6C63FF] focus:ring-[#6C63FF] text-lg py-4 px-4 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300 text-right placeholder:text-right"
                            type="email"
                            name="email"
                            :value="old('email')"
                            required
                            autocomplete="username"
                            placeholder="{{ __('example@domain.com') }}" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm" />
                    </div>

                    <!-- Password -->
                    <div class="mt-6">
                        <x-input-label for="password" :value="__('كلمة المرور')" class="text-lg font-medium text-gray-700 dark:text-gray-300 text-right" />
                        <x-text-input id="password"
                            class="block mt-1 w-full rounded-xl border-2 border-gray-200 shadow-sm focus:border-[#6C63FF] focus:ring-[#6C63FF] text-lg py-4 px-4 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300 text-right placeholder:text-right"
                            type="password"
                            name="password"
                            required
                            autocomplete="new-password"
                            placeholder="{{ __('••••••••') }}" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm" />
                    </div>

                    <!-- Confirm Password -->
                    <div class="mt-6">
                        <x-input-label for="password_confirmation" :value="__('تأكيد كلمة المرور')" class="text-lg font-medium text-gray-700 dark:text-gray-300 text-right" />
                        <x-text-input id="password_confirmation"
                            class="block mt-1 w-full rounded-xl border-2 border-gray-200 shadow-sm focus:border-[#6C63FF] focus:ring-[#6C63FF] text-lg py-4 px-4 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300 text-right placeholder:text-right"
                            type="password"
                            name="password_confirmation"
                            required
                            autocomplete="new-password"
                            placeholder="{{ __('••••••••') }}" />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-sm" />
                    </div>

                    <!-- Action Section -->
                    <div class="flex flex-col sm:flex-row items-center justify-between mt-8 space-y-4 sm:space-y-0">
                        <a href="{{ route('login') }}"
                           class="text-sm text-gray-600 dark:text-gray-400 hover:text-[#6C63FF] dark:hover:text-[#6C63FF] transition-colors duration-200">
                            {{ __('لديك حساب بالفعل؟') }}
                        </a>

                        <x-primary-button class="w-full sm:w-auto justify-center py-4 px-8 text-lg font-semibold rounded-xl shadow-md bg-gradient-to-l from-[#6C63FF] to-[#FF6B6B] hover:opacity-90 text-white border-0 touch-button">
                            {{ __('تسجيل') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>