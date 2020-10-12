<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>会員編集</title>
    <script src="https://cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>
</head>
<body>
    <form method="post">
        <input type="hidden" name="_token" value="{{csrf_token()}}">
        <table>
            <thead>
                <tr><th>id</th><th>名前</th><th>グループ</th><th>登録日</th></tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>
                        <input name="users[{{ $user->id }}][id]" type="hidden" value="{{ $user->id }}">
                        @foreach ($groups as $group)
                            <input
                                id="user{{ $user->id }}group{{ $group->id }}"
                                type="checkbox"
                                name="users[{{ $user->id }}][groups][]"
                                value="{{ $group->id }}"
                                {{ in_array($group->name, $user->groups()->get()->pluck('name')->toArray(), true) ? 'checked="checked"': ''}}
                            >
                            <label
                                for="user{{ $user->id }}group{{ $group->id }}"
                            >
                                {{ $group->name }}
                            </label>
                        @endforeach
                    </td>
                    <td>{{ $user->created_at }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <button>保存</button>
    </form>
    <textarea id="summary-ckeditor" name="summary-ckeditor"></textarea>
    <script>CKEDITOR.replace('summary-ckeditor');</script>
</body>
</html>
