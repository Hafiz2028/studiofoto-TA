<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Districts</title>
</head>
<body>
    <form action="{{ route('submit') }}" method="post">
        @csrf
        <select name="district">
            @foreach($districts as $district)
                <option value="{{ $district['id'] }}">{{ $district['name'] }}</option>
            @endforeach
        </select>
        <button type="submit">Submit</button>
    </form>
</body>
</html>
