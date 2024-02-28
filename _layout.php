<?php 
    session_start() ;
    if( isset( $_SESSION[ 'user' ] ) ) {
        $interval = time() - $_SESSION[ 'auth-moment' ] ;
        if( $interval > 3000 ) {
            unset( $_SESSION[ 'user' ] ) ;
            unset( $_SESSION[ 'auth-moment' ] ) ;
            $user = null ;
        }
        else {
            $user = $_SESSION[ 'user' ] ;
            $_SESSION[ 'auth-moment' ] = time() ;
            $filename = $_SESSION['user']['avatar'];
        }
    }
    // else if( ! isset( $_SESSION[ 'user' ] ) ) {
    //         unset( $_SESSION[ 'user' ] ) ;
    //         unset( $_SESSION[ 'auth-moment' ] ) ;
    //         $user = null ;
    //         session_destroy();
    // }
?>
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
    <link rel="stylesheet" href="/css/site.css"/>  
</head>

<body>
<nav>
    <div class="nav-wrapper purple">
      <a href="/" class="brand-logo"><img src="/img/php.png"/></a>
      <ul id="nav-mobile" class="right hide-on-med-and-down">
      <?php foreach( [
            'basics' => 'Основи',
            'layout' => 'Шаблонізація',
            'regexp' => 'Регулярні вирази',
            'api' => 'API',
            // 'formRegistration' => 'Реєстрація' 
            ] as $href => $name ) : ?>
        <li <?= $uri==$href ? 'class="active"' : '' ?> ><a href="/<?= $href ?>"><?= $name ?></a></li>
        <?php endforeach ?>
        <li><a href="/formRegistration"><i class="material-icons">person_add</i></a></li>
        <!-- Modal Trigger -->
        <?php 
            if( isset( $_SESSION[ 'user' ] ) ) {
              echo "<li><a href='/profile' class=\"avatar\"><img src=\"/avatar/$filename\" alt=\"Avatar\"></a></li>";
                echo '<li><a href="#exit-modal" class="modal-trigger"><i class="material-icons">logout</i></a></li>';
              }
            else if( ! isset( $_SESSION[ 'user' ] ) ) {
                echo '<li><a href="#auth-modal" class="modal-trigger"><i class="material-icons">key</i></a></li>';
            }
        ?>
      </ul>
    </div>
  </nav>

<!-- Auth in <?= $interval ?> sec -->
<!-- <?= $filename ?> -->

<div class="container">  
    <?php include $page_body; ?>
</div>

<footer class="page-footer purple">
          <div class="container">
            <div class="row">
              <div class="col l6 s12">
                <h5 class="white-text">Footer Content</h5>
                <p class="grey-text text-lighten-4">You can use rows and columns here to organize your footer content.</p>
              </div>
              <div class="col l4 offset-l2 s12">
                <h5 class="white-text">Links</h5>
                <ul>
                  <li><a class="grey-text text-lighten-3" href="#!">Link 1</a></li>
                  <li><a class="grey-text text-lighten-3" href="#!">Link 2</a></li>
                </ul>
              </div>
            </div>
          </div>
          <div class="footer-copyright">
            <div class="container">
            © 2024 Copyright Text
            <a class="grey-text text-lighten-4 right" href="#!">More Links</a>
            </div>
          </div>
        </footer>
		
  <!-- Modal Auth Structure -->
  <div id="auth-modal" class="modal">
  <div class="col s12">
    <div class="modal-content mymodel">
      <h4 class="mymodel">Введіть e-mail та пароль для входу</h4>
      <div class="input-field col s6">
          <i class="material-icons prefix">email</i>
          <input id="user-input-email" type="text" class="validate" name="auth-email">
          <label for="user-input-email">Email</label>
      </div>
      <div class="input-field col s6">
          <i class="material-icons prefix">lock</i>
          <input id="user-input-password" type="password" class="validate" name="auth-password">
          <label for="user-input-password">Password</label>
      </div>
    </div>
    <div class="modal-footer">
      <button class="modal-close grey btn-flat">Закрити</button>
      <button class="modal-close purple btn-flat" style="margin-left:15px;"
        id="auth-button">Вхід</button>
    </div>
  </div>
</div>	

<!-- Modal Exit Structure -->
<div id="exit-modal" class="modal">
  <div class="col s12">
    <div class="modal-content mymodel">
      <h4 class="mymodel">Ви впевнені, що хочете вийти із системи?</h4>
    </div>
    <div class="modal-footer">
      <button class="modal-close grey btn-flat">Ні</button>
      <button class="modal-close purple btn-flat" style="margin-left:15px;"
        id="exit-button">Так</button>
    </div>
  </div>
</div>	

<!-- Модальное окно для входа -->
<!-- <div id="modal-login" class="modal">
    <div class="modal-content">
        <h4>Вхід</h4>
        <form id="login-form">
            <div class="input-field">
                <input id="emailCheck" name="emailCheck" type="email" class="validate" required>
                <label for="emailCheck">Email</label>
            </div>
            <div class="input-field">
                <input id="passwordCheck" name="passwordCheck" type="password" class="validate" required>
                <label for="passwordCheck">Password</label>
            </div>
            <button class="btn waves-effect waves-light" type="submit" name="action">Увійти</button>
        </form>
    </div>
    <div class="modal-footer">
        <a href="#!" class="modal-close waves-effect waves-green btn-flat">Закрити</a>
    </div>
</div> -->


<!-- Compiled and minified JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
<script src="/js/site.js"></script>
<!-- <script>
    document.addEventListener('DOMContentLoaded', function() {
        var elems = document.querySelectorAll('.modal');
        var instances = M.Modal.init(elems);
    });

    document.getElementById('login-form').addEventListener('submit', function(event) {
        event.preventDefault(); // Предотвращаем отправку формы по умолчанию

        // Получаем значения полей формы
        var email = document.getElementById('emailCheck').value;
        var password = document.getElementById('passwordCheck').value;

        // код для обработки введенных данных

        // Очищаем поля формы
        document.getElementById('emailCheck').value = '';
        document.getElementById('passwordCheck').value = '';

        // Закрываем модальное окно
        var modalInstance = M.Modal.getInstance(document.getElementById('modal-login'));
        modalInstance.close();
    });
</script> -->

</body>
</html>