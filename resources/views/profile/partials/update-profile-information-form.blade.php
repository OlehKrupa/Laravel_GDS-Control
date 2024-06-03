<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')"/>
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                          :value="old('name', $user->name)"
                          required autofocus autocomplete="name"/>
            <x-input-error class="mt-2" :messages="$errors->get('name')"/>
        </div>

        <!-- Surname -->
        <div>
            <x-input-label for="surname" :value="__('Surname')"/>
            <x-text-input id="surname" class="block mt-1 w-full" type="text" name="surname"
                          :value="old('surname', $user->surname)" required autofocus autocomplete="surname"/>
            <x-input-error :messages="$errors->get('surname')" class="mt-2"/>
        </div>

        <!-- Patronymic -->
        <div>
            <x-input-label for="patronymic" :value="__('Patronymic')"/>
            <x-text-input id="patronymic" class="block mt-1 w-full" type="text" name="patronymic"
                          :value="old('patronymic', $user->patronymic)" required autofocus
                          autocomplete="patronymic"/>
            <x-input-error :messages="$errors->get('patronymic')" class="mt-2"/>
        </div>

        <!-- Station -->
        <div>
            <x-input-label for="station_id" :value="__('Station')"/>
            <select id="station_id" name="station_id" class="block mt-1 w-full"
                    required>
                <option
                    value="{{ $userStation->id }}" {{ $isAdmin ? 'selected' : '' }}>{{ $userStation->label }}
                    , {{ $userStation->city }}</option>
                @foreach($stations as $station)
                    <option value="{{ $station->id }}">{{ $station->label }}, {{ $station->city }}</option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('station_id')" class="mt-2"/>
        </div>

        <!-- Roles -->
        <div>
            <x-input-label for="roles" :value="__('Roles')"/>
            <div class="mt-2 space-y-2">
                @foreach($roles as $role)
                    <div>
                        <input type="checkbox" id="role_{{ $role->id }}" name="roles[]"
                               value="{{ $role->id }}" {{ in_array($role->id, $userRoles->pluck('id')->toArray()) ? 'checked' : '' }}>
                        <label for="role_{{ $role->id }}">{{ __($role->name) }}</label>
                    </div>
                @endforeach
            </div>
            <x-input-error :messages="$errors->get('roles')" class="mt-2"/>
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')"/>
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full"
                          :value="old('email', $user->email)" required autocomplete="username"/>
            <x-input-error class="mt-2" :messages="$errors->get('email')"/>

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification"
                                class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
