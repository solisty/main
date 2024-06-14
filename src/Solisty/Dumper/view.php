<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{% env('APP_NAME') %}</title>

    <style>
        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }

        #dump {
            background: #444;
            color: lightcoral;
            padding: 10px;
            width: fit-content;
        }
    </style>

</head>
<body>
    <div id="dump"></div>

    <script>
        const dump = `{! $dump !}`;
        // TODO: parse dump

        const lines = dump.split('\n');
        lines.forEach(line => {
            const pre = document.createElement('pre');
            pre.textContent = line
            document.getElementById("dump").append(pre)
        })

    </script>
</body>
</html>