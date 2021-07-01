<!DOCTYPE html>
<html>
  
<head>
    <title>Pluralization</title>
</head>
<body>
    <!-- {{__('plural.welcome', ['name' => 'dayle'])}} -->

    {{trans_choice('plural.apples', 10)}}

    <!-- {{trans_choice('plural.minutes_ago', 5, ['value' => 5])}} -->
</body>
</html>