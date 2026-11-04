<x-app-layout title="Account & admins">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Account & administrators</h2>
    </x-slot>

    <div class="grid gap-4 lg:grid-cols-2">
        <div class="bg-white shadow-sm sm:rounded-lg p-6">
            <h3 class="mb-5 font-semibold text-gray-800"><i class="fas fa-id-card text-primary-500 mr-2"></i>Your profile</h3>
            <form method="POST" action="{{ route('admin.profile.update') }}" class="space-y-5">
                @csrf @method('PUT')
                <x-input name="name" label="Name" :value="old('name', $user->name)" required />
                <x-input name="email" type="email" label="Email (used to sign in)" :value="old('email', $user->email)" required />
                <div class="flex justify-end"><x-button type="submit" icon="fas fa-save">Save profile</x-button></div>
            </form>
        </div>

        <div class="bg-white shadow-sm sm:rounded-lg p-6">
            <h3 class="mb-5 font-semibold text-gray-800"><i class="fas fa-key text-primary-500 mr-2"></i>Change password</h3>
            <form method="POST" action="{{ route('admin.profile.password') }}" class="space-y-5">
                @csrf @method('PUT')
                <x-input name="current_password" type="password" label="Current password" required autocomplete="current-password" />
                <x-input name="password" type="password" label="New password" required autocomplete="new-password" helper="At least 8 characters." />
                <x-input name="password_confirmation" type="password" label="Confirm new password" required autocomplete="new-password" />
                <div class="flex justify-end"><x-button type="submit" icon="fas fa-lock">Change password</x-button></div>
            </form>
        </div>

        <div class="lg:col-span-2 bg-white shadow-sm sm:rounded-lg p-6">
            <h3 class="mb-5 font-semibold text-gray-800"><i class="fas fa-users-gear text-primary-500 mr-2"></i>Administrators</h3>
            <div class="grid gap-6 lg:grid-cols-2">
                <table class="min-w-full divide-y divide-gray-200 self-start">
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($admins as $admin)
                            <tr>
                                <td class="py-3 text-sm">
                                    <span class="font-medium text-gray-900">{{ $admin->name }}</span>
                                    @if ($admin->is($user))<span class="ml-1 rounded bg-primary-50 px-1.5 py-0.5 text-[11px] text-primary-700">you</span>@endif
                                    <span class="block text-xs text-gray-500">{{ $admin->email }}</span>
                                </td>
                                <td class="py-3 text-right">
                                    @unless ($admin->is($user))
                                        <form method="POST" action="{{ route('admin.admins.destroy', $admin) }}" onsubmit="return confirm('Remove {{ $admin->name }} as an administrator?')">
                                            @csrf @method('DELETE')
                                            <button class="text-red-600 hover:text-red-900 text-sm" title="Remove"><i class="fas fa-user-minus"></i></button>
                                        </form>
                                    @endunless
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <form method="POST" action="{{ route('admin.admins.store') }}" class="space-y-4 rounded-lg border border-dashed border-gray-300 p-5" autocomplete="off">
                    @csrf
                    <p class="text-sm font-medium text-gray-700">Add an administrator</p>
                    <x-input id="new_name" name="name" label="Name" :value="''" required />
                    <x-input id="new_email" name="email" type="email" label="Email" :value="''" required />
                    <x-input id="new_password" name="password" type="password" label="Temporary password" required autocomplete="new-password" helper="Share it with them privately; they can change it here." />
                    <div class="flex justify-end"><x-button type="submit" icon="fas fa-user-plus">Add administrator</x-button></div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
