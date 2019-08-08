<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php if (isset($title)): $this->escapeEcho($title) . '-';endif?>けいじばん</title>
</head>

<body>
    <div class="header">
        <h1><a href="/">けいじばん</a></h1>
    </div>
    <div class="main"><?php echo ($_content); ?></div>
</body>

</html>