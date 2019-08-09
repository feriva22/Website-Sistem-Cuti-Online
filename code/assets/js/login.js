function onSignIn(googleUser) {

    var id_token = googleUser.getAuthResponse().id_token;
    var submitted_data = {
        idtoken: id_token
    };
    $.ajax({
        url: base_url + 'login/google',
        type: 'POST',
        data: submitted_data,
        success: function(resp){
            if(typeof(resp) != 'object')
                resp = JSON.parse(resp);
            console.log(resp);
            if(resp.status == 'ok'){
                //redirect ke dashboard / index
                var redir_url = resp.redir;
                document.location = redir_url ? redir_url : base_url;
            }else{
                showMessage('error', resp.message);
                gapi.auth2.getAuthInstance().signOut();
            }
        },
        error: function(evt){
            showMessage('error', 'Error koneksi dengan server');
            gapi.auth2.getAuthInstance().signOut();
        }
    });
}

function onLoad() {
    gapi.load('auth2', function() {
        gapi.auth2.init();
    });
}

function signOut() {
    if(typeof(gapi) != 'undefined'){
        gapi.auth2.getAuthInstance().signOut();
        var auth2 = gapi.auth2.getAuthInstance();
        auth2.signOut().then(function () {
            window.location = base_url + "login/logout";
        });
    }else{
        window.location = base_url + "login/logout";
    }
}