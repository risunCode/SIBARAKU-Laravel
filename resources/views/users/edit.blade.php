<x-app-layout title="Edit Pengguna">
    <div class="max-w-2xl">
        <div class="mb-6">
            <a href="{{ route('users.index') }}" class="text-sm text-gray-600 hover:text-gray-700 flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali
            </a>
            <h2 class="text-xl font-bold text-gray-900 mt-2">Edit Pengguna: {{ $user->name }}</h2>
        </div>

        <div class="card">
            <form action="{{ route('users.update', $user) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card-body space-y-4">
                    <!-- Info Referral -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-gray-600">Kode Referral:</span>
                                <code class="ml-2 bg-white px-2 py-1 rounded">{{ $user->referral_code }}</code>
                            </div>
                            <div>
                                <span class="text-gray-600">Direferensikan oleh:</span>
                                <span class="ml-2 font-medium">{{ $user->referrer?->name ?? 'Tidak ada' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <x-form.input label="Nama Lengkap" name="name" :value="$user->name" required />
                        <x-form.input label="Email" name="email" type="email" :value="$user->email" required />
                    </div>

                    <x-form.input label="No. Telepon" name="phone" :value="$user->phone" />

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <x-form.input label="Password Baru" name="password" type="password" helper="Kosongkan jika tidak ingin mengubah" />
                        <x-form.input label="Konfirmasi Password" name="password_confirmation" type="password" />
                    </div>

                    <x-form.select label="Role" name="role" :value="$user->role" required>
                        @foreach($roles as $value => $label)
                        <option value="{{ $value }}" {{ $user->role == $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                        @endforeach
                    </x-form.select>

                    <div>
                        <label class="form-label">Avatar</label>
                        <div class="flex items-center gap-4">
                            <img src="{{ $user->avatar_url }}" class="w-16 h-16 rounded-full object-cover" alt="">
                            <input type="file" name="avatar" accept="image/*" class="text-sm">
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="is_active" value="1" {{ $user->is_active ? 'checked' : '' }} class="rounded border-gray-300 text-primary-600">
                        <label class="text-sm text-gray-700">Pengguna aktif</label>
                    </div>
                </div>

                <div class="card-footer flex justify-end gap-3">
                    <a href="{{ route('users.index') }}" class="btn btn-outline">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
