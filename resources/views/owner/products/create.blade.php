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
                    <form method="post" action="{{ route('owner.products.store') }}">
                        @csrf
                        <div class="-m-2">
                          <div class="p-2 w-1/2 mx-auto">
                            <div class="relative">
                              {{-- selectで複数の中から1つ選び --}}
                              <select name="category">
                                入ってくるカテゴリーを1つずつ展開
                                @foreach($categories as $category)
                                    {{-- Primaryカテゴリーの名前をoptgroupでラベル表示 --}}
                                    <optgroup label="{{ $category->name }}">
                                        {{-- Primaryカテゴリーからsecondaryカテゴリーに渡す --}}
                                        @foreach($category->secondary as $secondary)
                                        {{-- econdaryカテゴリーの中から選択 --}}
                                        <option value="{{ $secondary->id }}">
                                            {{ $secondary->name }}
                                        </option>
                                        @endforeach
                                @endforeach
                              </select>
                                
                            </div>
                          </div>
                          <div class="p-2 w-full flex justify-around mt-4">
                            <button type="button" onclick="location.href='{{ route('owner.products.index') }}'" class="bg-green-400 border-0 py-2 px-8 focus:outline-none hover:bg-green-500 rounded text-lg">戻る</button>
                            <button type="submit" class=" text-white bg-blue-500 border-0 py-2 px-8 focus:outline-none hover:bg-blue-600 rounded text-lg">登録する</button>
                          </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
