<h1>Реєстрація</h1>
<div class="row">
    <form class="col s12">
      <div class="row">
        <div class="input-field col s6">
        <i class="material-icons prefix">account_circle</i>
          <input placeholder="Name" id="name" name="user-name" type="text" class="validate">
          <label for="name">Name</label>
          <span class="helper-text"
                      data-error="Error: the field cannot be empty"
                      data-success="Correct data">Name</span>
        </div>
        <div class="input-field col s6">
        <i class="material-icons prefix">mail</i>
          <input id="email" name="user-email" type="email" class="validate">
          <label for="email">Email</label>
          <span class="helper-text"
                      data-error="Error: the field cannot be empty"
                      data-success="Correct data">Email</span>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s6">
        <i class="material-icons prefix">lock</i>
          <input id="password" name="user-password" type="password" class="validate">
          <label for="password">Password</label>
          <span class="helper-text"
                      data-error="Error: the field cannot be empty"
                      data-success="Correct data">Password</span>
        </div>
        <div class="input-field col s6">
        <i class="material-icons prefix">key</i>
          <input id="repeatPassword" name="user-repeat" type="password" class="validate">
          <label for="repeatPassword">Repeat Password</label>
          <span class="helper-text"
                      data-error="Error: Password doesn't match"
                      data-success="Correct data">Repeat password</span>
        </div>
      </div>
      <div class="row">
        <div class="file-field input-field">
            <div class="btn">
            <i class="material-icons">photo</i>
                <span>File</span>
                <input type="file" name="user-avatar">
            </div>
            <div class="file-path-wrapper">
                <input class="file-path validate" type="text" placeholder="Upload one file">
            </div>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s3">
            <button class="btn waves-effect waves-light" type="button" id="signup-button" name="action">Зареєструватися
                <i class="material-icons right">send</i>
            </button>
        </div>
      </div>
    </form>
</div>