<!-- admin側　従業員詳細画面のblade -->
<x-app-layout>
    <x-slot name="header">
    </x-slot>

    <body>
        @foreach($employee_lists as $emplo)
        <div class="max-w-2xl mx-auto my-3 bg-gray-100 p-16">
            <div class="flex flex-col text-center w-full mb-20">
                <h1 class="sm:text-4xl text-2xl font-medium title-font mb-2 text-gray-900">詳細画面</h1>
            </div>
            <div class="text-right">
                <!-- 退職フラグが0の場合は、退職ボタンを出し -->
                @if(($emplo->retirement_authority) == "0")
                <form method="get" action="{{ route('admin.destroy_check',[$emplo->emplo_id,$emplo->retirement_authority])}}">
                    @csrf
                    <button class="text-white bg-red-500 border-0 py-1 px-2 focus:outline-none hover:bg-red-600 rounded text-lg" title="ボタンをクリックすることで、退職処理を行います。">退職</button>
                </form>
                @else
                <!-- 退職フラグが1の場合は、復職ボタンを出す -->
                <form method="get" action="{{ route('admin.reinstatement_check',[$emplo->emplo_id,$emplo->retirement_authority])}}">
                    @csrf
                    <button class="text-white bg-green-500 border-0 py-1 px-2 focus:outline-none hover:bg-green-600 rounded text-lg" title="ボタンをクリックすることで、復職処理を行います。">復職</button>
                </form>
                @endif
                <!-- ボタンここまで -->
            </div>
            <form method="POST" action="{{ route('admin.details_update') }}">
                @csrf
                @method('post')
                <!-- フラッシュメッセージの表示 -->
                @if (session('status'))
                <div class="alert alert-info mt-2">
                    {{ session('status') }}
                </div>
                @endif

                @if (session('warning'))
                <div class="alert alert-warning mt-2">
                    <small>{{ session('warning') }}</small>
                </div>
                @endif

                @if ($errors->has('name'))
                <div class="alert text-center alert-warning mt-2">
                    {{ $errors->first('name') }}
                </div>
                @endif
                <div class="grid gap-6 mb-6 lg:grid-cols-3">
                    <div>
                        <!-- 社員番号 -->
                        <label for="emplo_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300" title="選択した社員の社員番号です。">社員番号</label>
                        <input type="emplo_id" id="emplo_id" name="emplo_id" value="{{ $emplo->emplo_id }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" readonly>
                    </div>
                    <div>
                        <!-- 社員名 -->
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300" title="選択した社員の社員名です。">社員名</label>
                        <input type="text" id="name" name="name" value="{{ $emplo->name }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="山田太郎">
                    </div>
                    <div>
                        <!-- 部下配属権限 -->
                        <label for="subord_authority" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300" title="トグルをONにすると、選択した社員に部下を配属することができます。">部下配属権限</label>
                        <label for="subord_authority" class="flex items-center cursor-pointer relative mb-4">
                            <input type="checkbox" onclick="clickBtn7()" id="subord_authority" name="subord_authority" class="sr-only" <?= $emplo->subord_authority == 1 ? 'checked' : '' ?>>
                            <div class="toggle-bg bg-gray-200 border-2 border-gray-200 h-6 w-11 rounded-full"></div>
                        </label>
                    </div>
                </div>
                <div class="grid gap-6 mb-6 lg:grid-cols-3">
                    <div>
                        <!-- 上司社員番号 -->
                        <label for="management_emplo_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300" title="選択した社員の上司の社員番号です。">上司社員番号</label>
                        <input type="text" id="management_emplo_id" name="management_emplo_id" maxlength="4" value="{{ $emplo->management_emplo_id }}" data-toggle="tooltip" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="1000" readonly>
                    </div>
                    <div>
                        <!-- 上司の名前（現在） -->
                        <label for="high_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300" title="選択した社員の上司の名前（現在）です。">上司の名前（現在）</label>
                        <input type="high_name" id="high_name" value="{{ $emplo->high_name }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" readonly>
                    </div>
                    <div>
                        <!-- 上司検索 -->
                        <label for="search" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300" title="上司を検索します。">上司検索</label>
                        <input type="search" id="search-list" list="keywords" autocomplete="off" data-toggle="tooltip" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="上司の名前を選択">
                        <datalist id="keywords">
                            @foreach($subord_authority_lists as $subord_authority_list)
                            <option value="{{$subord_authority_list->name}}" label="{{$subord_authority_list->emplo_id}}"></option>
                            @endforeach
                        </datalist>
                        <script src="{{ asset('js/another/search.js') }}" defer></script>
                    </div>
                </div>
                @if ($errors->has('restraint_start_time'))
                <div class="alert text-center alert-warning">
                    {{ $errors->first('restraint_start_time') }}
                </div>
                @endif
                @if ($errors->has('restraint_closing_time'))
                <div class="alert text-center alert-warning">
                    {{ $errors->first('restraint_closing_time') }}
                </div>
                @endif
                <div class="grid gap-6 mb-6 lg:grid-cols-3">
                    <div>
                        <!-- 始業時間 -->
                        <label for="restraint_start_time" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300" title="選択した社員の始業時間です。">始業時間</label>
                        <input type="time" id="restraint_start_time" name="restraint_start_time" value="{{ $emplo->restraint_start_time }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="10:00">
                    </div>
                    <div>
                        <!-- 終業時間 -->
                        <label for="restraint_closing_time" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300" title="選択した社員の終業時間です。">終業時間</label>
                        <input type="time" id="restraint_closing_time" name="restraint_closing_time" value="{{ $emplo->restraint_closing_time }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="15:00">
                    </div>
                    <div>
                        <!-- 就業時間 -->
                        <label for="restraint_total_time" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300" title="選択した社員の就業時間です。">就業時間</label>
                        <input type="time" id="restraint_total_time" name="restraint_total_time" value="{{ $emplo->restraint_total_time }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="5:00" readonly>
                    </div>
                </div>
                <div class="grid gap-6 mb-12 lg:grid-cols-3">
                    <div>
                        <!-- 登録日時 -->
                        <label for="restraint_start_time" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300" title="選択した社員の登録日時です。">登録日時</label>
                        <input type="timestamp" id="ceated_at" name="created_at" value="{{ $emplo->created_at }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" readonly>
                    </div>
                    <div>
                        <!-- 更新日時 -->
                        <label for="restraint_closing_time" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300" title="選択した社員の更新日時です。">更新日時</label>
                        <input type="timestamp" id="updated_at" name="updated_at" value="{{ $emplo->updated_at }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" readonly>
                    </div>
                    @if(isset($emplo->retirement_date))
                    <div>
                        <!-- 退職日 -->
                        <label for="restraint_total_time" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300" title="選択した社員の退職日です。">退職日</label>
                        <input type="timestamp" id="retirement_date" name="retirement_date" value="{{ $emplo->retirement_date }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" readonly>
                    </div>
                    @else
                    <div>
                        <!-- 入社日 -->
                        <label for="hire_date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300" title="選択した社員の入社日です。">入社日</label>
                        <input type="timestamp" id="hire_date" name="hire_date" value="{{ $emplo->hire_date }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" readonly>
                    </div>
                    @endif
                </div>
                <!-- ボタン配置 -->
                <div class="flex justify-center">
                    <button type="submit" class="flex mx-auto text-white bg-green-500 border-0 py-2 px-8 focus:outline-none hover:bg-green-600 rounded text-lg" title="ボタンをクリックすることで、更新が行われます。">更新</button>
                    <input id="myButton" class="flex mx-auto text-white bg-yellow-400 border-0 py-2 px-8 focus:outline-none hover:bg-yellow-300 rounded text-lg" type="button" value="戻る" onclick="window.history.back()" title="1つ前の画面に戻ります。">
                    <script type="text/javascript">
                        document.getElementById("myButton").onclick = function() {
                            location.href = "{{ route('admin.dashboard') }}";
                        };
                    </script>
                </div>
                <!-- ボタンここまで -->
            </form>
        </div>
        @endforeach
    </body>
</x-app-layout>
