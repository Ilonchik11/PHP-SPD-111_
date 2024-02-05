<!doctype html>
<html>

<head>
	<!-- Compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
	<!--Import Google Icon Font-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons" >
    <!--Let browser know website is optimized for mobile-->
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>PHP SPD-111</title>
</head>

<body>
<nav>
    <div class="nav-wrapper purple">
      <a href="/" class="brand-logo">PHP</a>
      <ul id="nav-mobile" class="right hide-on-med-and-down">
      <?php foreach( [
            '/basics' => 'Основи',
            '/layout' => 'Шаблонізація',
            '/regexp' => 'Регулярні вирази'
            ] as $href => $name ) : ?>
        <li <?= $uri==$href ? 'class="active"' : '' ?> ><a href="<?= $href ?>"><?= $name ?></a></li>
        <?php endforeach ?>
      </ul>
    </div>
  </nav>

<div class="container">  

    <?php include $page_body; ?>

</div>

<!-- Compiled and minified JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>

</body>
</html>