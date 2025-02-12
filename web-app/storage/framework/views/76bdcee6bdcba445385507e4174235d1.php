<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>

<?php if(session('status')): ?> <!-- ?? -->
    <div><?php echo e(session('status')); ?></div>
<?php endif; ?>

<?php echo e($slot); ?>


</body>
</html>
<?php /**PATH C:\Users\dell\web-app\resources\views/components/layout.blade.php ENDPATH**/ ?>