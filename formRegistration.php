<h1>Реєстрація</h1>
<div class="row">
    <form class="col s12">
      <div class="row">
        <div class="input-field col s12">
          <input placeholder="Name" id="name" name="name" type="text" class="validate">
          <label for="name">First Name</label>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s12">
          <input id="email" name="email" type="email" class="validate">
          <label for="email">Email</label>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s12">
          <input id="password" name="password" type="password" class="validate">
          <label for="password">Password</label>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s12">
          <input id="repeatPassword" name="repeatPassword" type="password" class="validate">
          <label for="repeatPassword">Repeat Password</label>
        </div>
      </div>
      <div class="row">
        <div class="file-field input-field">
            <div class="btn">
                <span>File</span>
                <input type="file" name="avatar">
            </div>
            <div class="file-path-wrapper">
                <input class="file-path validate" type="text" placeholder="Upload one file">
            </div>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s3">
            <button class="btn waves-effect waves-light" type="button" name="action">Зареєструватися
                <i class="material-icons right">send</i>
            </button>
        </div>
      </div>
    </form>
</div>