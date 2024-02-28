document.addEventListener( 'DOMContentLoaded', () => {
    // search the registration button
    const signupButton = document.getElementById("signup-button");
    if(signupButton) {
        signupButton.onclick = signupButtonClick; 
    }
    // search the auth button
    const authButton = document.getElementById("auth-button") ;
    if(authButton) {
        authButton.onclick = authButtonClick; 
    }
    // search the exit button
    const exitButton = document.getElementById("exit-button") ;
    if(exitButton) {
        exitButton.onclick = exitButtonClick; 
    }
    //modal windows
    var elems = document.querySelectorAll('.modal');
    M.Modal.init(elems, {
            "opacity":         0.5,   // Opacity of the modal overlay.
            "inDuration":     250,   // Transition in duration in milliseconds.
            "outDuration":     250,   // Transition out duration in milliseconds.
            "onOpenStart":     null,  // Callback function called before modal is opened.
            "onOpenEnd":     null,  // Callback function called after modal is opened.
            "onCloseStart":    null,  // Callback function called before modal is closed.
            "onCloseEnd":     null,  // Callback function called after modal is closed.
            "preventScrolling": true,  // Prevent page from scrolling while modal is open.
            "dismissible":     true,  // Allow modal to be dismissed by keyboard or overlay click.
            "startingTop":     '4%',  // Starting top offset
            "endingTop":     '10%'  // Ending top offset
          });
});

function exitButtonClick(e) {
    fetch("/auth", { 
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(r => r.json())
    .then(j => {
        if(j.status == 1) { // exit is successful
            window.location = '/'; // move to the main page
            console.log(j.data.message);
        } else { // exit error
            alert(j.data.message);
        }
    });
}

// function exitButtonClick(e) {
//     fetch( "/auth", { method: 'EXIT'} )
//     .then( r => r.json() )
//     .then( j => {
//         if( j.status == 1 ) { //exit is successful
//             alert( 'Exit is successful' ) ;
//             window.location = '/' ; //move to the main page
//         }
//         else { //exit error
//             alert( j.data.message ) ;
//         }
//     } ) ;
// }

function authButtonClick(e) {
    const emailInput = document.querySelector('input[name="auth-email"]');
    if( ! emailInput ) { throw "auth-email not found" ; }
    const passwordInput = document.querySelector('input[name="auth-password"]');
    if( ! passwordInput ) { throw "auth-password not found" ; }
    
    // console.log( emailInput.value, passwordInput.value ) ;
    fetch(`/auth?&email=${emailInput.value}&password=${passwordInput.value}`, {
        method: 'PATCH'
    })
    .then( r => r.json() )
    .then( response => {
        console.log(response) ;
        if(response.status === 1) {
            location.reload() ;
        }
    } ) ;
}

function signupButtonClick(e) {
    // e.target - look for form
    const signupForm = e.target.closest('form') ;
    if( ! signupForm ) {
        throw "Signup form not found" ;
    }
    // look for elements
    const nameInput = signupForm.querySelector('input[name="user-name"]');
    if( ! nameInput ) { throw "nameInput form not found" ; }
    const emailInput = signupForm.querySelector('input[name="user-email"]');
    if( ! emailInput ) { throw "emailInput form not found" ; }
    const passwordInput = signupForm.querySelector('input[name="user-password"]');
    if( ! passwordInput ) { throw "passwordInput form not found" ; }
    const repeatInput = signupForm.querySelector('input[name="user-repeat"]');
    if( ! repeatInput ) { throw "repeatInput form not found" ; }
    const avatarInput = signupForm.querySelector('input[name="user-avatar"]');
    if( ! avatarInput ) { throw "avatarInput form not found" ; }

    // Data validation
    let isFormValid = true ;
    // Name Validation
    if( nameInput.value == "" ) {
        nameInput.classList.remove("valid") ;
        nameInput.classList.add("invalid") ;
        isFormValid = false ;
    }
    else {
        nameInput.classList.remove("invalid") ;
        nameInput.classList.add("valid") ;
    }
    // Email Validation
    if( emailInput.value == "" ) {
        emailInput.classList.remove("valid") ;
        emailInput.classList.add("invalid") ;
        isFormValid = false ;
    }
    else {
        emailInput.classList.remove("invalid") ;
        emailInput.classList.add("valid") ;
    }
    // Password Validation
    if( passwordInput.value == "" ) {
        passwordInput.classList.remove("valid") ;
        passwordInput.classList.add("invalid") ;
        isFormValid = false ;
    }
    else {
        passwordInput.classList.remove("invalid") ;
        passwordInput.classList.add("valid") ;
    }
    // Repeat Password Validation
    if( passwordInput.value == "" || repeatInput.value != passwordInput.value ) {
        repeatInput.classList.remove("valid") ;
        repeatInput.classList.add("invalid") ;
        isFormValid = false ;
    }
    else {
        repeatInput.classList.remove("invalid") ;
        repeatInput.classList.add("valid") ;
    }


    if( ! isFormValid ) return ;
    // The End

    // formation of data for transmition to backend
    const formData = new FormData() ;
    formData.append( "user-name", nameInput.value ) ;
    formData.append( "user-email", emailInput.value ) ;
    formData.append( "user-password", passwordInput.value ) ;
    if( avatarInput.files.length > 0) {
        formData.append( "user-avatar", avatarInput.files[0] ) ;
    }

    // transmit - form the request
    fetch( "/auth", { method: 'POST', body: formData } )
    .then( r => r.json() )
    .then( j => {
        if( j.status == 1 ) { //registration is successful
            alert( 'Registration is successful' ) ;
            window.location = '/' ; //move to the main page
        }
        else { //registration error
            alert( j.data.message ) ;
        }
    } ) ;
}