<!DOCTYPE html>
<html>
<head>
    <title>Upload Test</title>
</head>
<body>

@if(session('success'))
    <p style="color:green">{{ session('success') }}</p>

    <img
        src="https://marketplace.betalearnings.com/assets/uploads/{{ session('filename') }}"
        width="250">
@endif

   {{-- <img
        src="https://marketplace.betalearnings.com/assets/uploads/1784204703_1bf10dbfd0336f7da3b1ea8ca84f0156.jpg"
        width="250"> --}}

<form action="{{ route('upload.test') }}"
      method="POST"
      enctype="multipart/form-data">

    @csrf

    <input type="file" name="image">

    <br><br>

    <button type="submit">
        Upload
    </button>

</form>

</body>
</html>