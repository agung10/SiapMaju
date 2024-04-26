<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview Surat</title>
</head>
<body>
    {!! $data->isi_surat !!}
    <script type="text/javascript">
        if (screen.width > 600) {
            setTimeout(() => {
                window.print()
                window.close()
            }, 1000);
        }
    </script>
</body>
</html>