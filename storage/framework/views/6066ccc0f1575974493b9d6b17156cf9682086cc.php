<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="color-scheme" content="light">
<meta name="supported-color-schemes" content="light">
<title>Email Notification</title>
<style>
/* General Styles */
body {
background-color: #f4f4f9;
color: #333333;
font-family: Arial, sans-serif;
margin: 0;
padding: 0;
}
.wrapper {
width: 100%;
table-layout: fixed;
background-color: #f4f4f9;
padding: 30px 0;
}
.content {
background-color: #ffffff;
width: 100%;
max-width: 570px;
margin: 0 auto;
border-radius: 8px;
box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
overflow: hidden;
}
.header {
background-color: #4f46e5;
color: #3a9441fd;
padding: 20px;
text-align: center;
font-size: 20px;
font-weight: bold;
}
.body {
padding: 30px;
color: #51545e;
line-height: 1.6;
font-size: 16px;
}
.body h2 {
color: #333333;
font-size: 24px;
}
.body p {
margin: 10px 0;
}
.footer {
text-align: center;
padding: 20px;
color: #999999;
font-size: 12px;
background-color: #f4f4f9;
}
.footer a {
color: #3869d4;
text-decoration: none;
}
/* Responsive Styles */
@media  only screen and (max-width: 600px) {
.content {
width: 100% !important;
}
}
</style>
</head>
<body>
<table class="wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td align="center">
<table class="content" cellpadding="0" cellspacing="0" role="presentation">
<!-- Encabezado -->
<tr>
<td class="header">
<?php echo e(trans('panel.site_title')); ?>

</td>
</tr>
<!-- Cuerpo del correo -->
<tr>
<td class="body">
<p><?php echo e(Illuminate\Mail\Markdown::parse($slot)); ?></p>
<!-- Subcuerpo opcional -->
<?php echo e($subcopy ?? ''); ?>

</td>
</tr>
<!-- Pie de página -->
<tr>
<td class="footer">
© <?php echo e(date('Y')); ?> Logistica y Administracion LOAD. Todos los derechos reservados.
</td>
</tr>
</table>
</td>
</tr>
</table>
</body>
</html>
<?php /**PATH C:\wamp64\www\tisoporte\vendor\laravel\framework\src\Illuminate\Mail/resources/views/html/layout.blade.php ENDPATH**/ ?>