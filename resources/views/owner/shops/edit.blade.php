<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <x-input-error :messages="$errors->get('image')" class="mb-4" />
                    <form method="post" action="{{ route('owner.shops.update', ['shop'=>$shop->id]) }}" enctype="multipart/form-data">
                        @csrf
                        <div class="-m-2">
                          <div class="p-2 w-1/2 mx-auto">
                            <div class="relative">
                              <label for="image" class="leading-7 text-sm text-gray-600">画像</label>
                              {{-- accept属性でアップロードするファイル名を指定する --}}
                              <input type="file" id="image" name="image" accept='image/png,image/jpeg,image/jpg' class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                            </div>
                          </div>
                          <div class="p-2 w-full flex justify-around mt-4">
                            <button type="button" onclick="location.href='{{ route('owner.shops.index') }}'" class="bg-green-400 border-0 py-2 px-8 focus:outline-none hover:bg-green-500 rounded text-lg">戻る</button>
                            <button type="submit" class=" text-white bg-blue-500 border-0 py-2 px-8 focus:outline-none hover:bg-blue-600 rounded text-lg">更新する</button>
                          </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
