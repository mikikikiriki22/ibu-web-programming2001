var AuthService = {
    init: function () {
        var token = localStorage.getItem("user_token");
        if (token && token !== undefined) {
            window.location.replace("index.html");
        }
        $("#login-form").validate({
            submitHandler: function (form) {
                var entity = Object.fromEntries(new FormData(form).entries());
                AuthService.login(entity);
            },
        });
    },

    login: function (entity) {
        $.ajax({
            url: Constants.PROJECT_BASE_URL + "auth/login",
            type: "POST",
            data: JSON.stringify(entity),
            contentType: "application/json",
            dataType: "json",
            success: function (result) {
                if (result.data && result.data.token) {
                    localStorage.setItem("user_token", result.data.token);
                    localStorage.setItem("user", JSON.stringify(result.data.user));
                    window.location.replace("index.html#homepage");
                } else {
                    toastr.error(result.error || 'Login failed');
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                toastr.error(XMLHttpRequest?.responseText ? XMLHttpRequest.responseText : 'Error');
            },
        });
    },

    logout: function () {
        localStorage.removeItem("user_token");
        localStorage.removeItem("user");
        window.location.replace("login.html");
    },

    generateMenuItems: function () {
        const token = localStorage.getItem("user_token");
        const user = JSON.parse(localStorage.getItem("user"));

        if (user && user.role) {
            let nav = "";
            let main = "";

            // Common navigation items for all users
            nav = '<li class="nav-item mx-0 mx-lg-1">' +
                '<a class="nav-link py-3 px-0 px-lg-3 rounded" href="#parfumes">Parfumes</a>' +
                '</li>' +
                '<li class="nav-item mx-0 mx-lg-1">' +
                '<a class="nav-link py-3 px-0 px-lg-3 rounded" href="#profile">Profile</a>' +
                '</li>';

            // Add admin button if user is admin
            if (user.role === 'admin') {
                nav += '<li class="nav-item mx-0 mx-lg-1">' +
                    '<a class="nav-link py-3 px-0 px-lg-3 rounded" href="#admin">Admin Panel</a>' +
                    '</li>';
            }

            // Add logout button
            nav += '<li>' +
                '<button class="btn btn-primary" onclick="AuthService.logout()">Logout</button>' +
                '</li>';

            $("#tabs").html(nav);

            // Main content sections
            main = '<section id="parfumes"></section>' +
                '<section id="profile"></section>';

            // Add admin section if user is admin
            if (user.role === 'admin') {
                main += '<section id="admin"></section>';
            }

            $("#spapp").html(main);
        } else {
            window.location.replace("login.html");
        }
    },

    register: function (entity) {
        $.ajax({
            url: Constants.PROJECT_BASE_URL + "auth/register",
            type: "POST",
            data: JSON.stringify(entity),
            contentType: "application/json",
            dataType: "json",
            success: function (result) {
                toastr.success('Registration successful! Please log in.');
                setTimeout(function () {
                    window.location.replace("index.html#login");
                }, 1500);
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                let msg = 'Registration failed';
                if (XMLHttpRequest.responseJSON && XMLHttpRequest.responseJSON.error) {
                    msg = XMLHttpRequest.responseJSON.error;
                } else if (XMLHttpRequest.responseText) {
                    msg = XMLHttpRequest.responseText;
                }
                toastr.error(msg);
            }
        });
    },

    initRegister: function () {
        console.log('AuthService.initRegister called');
        $("#register-form").validate({
            submitHandler: function (form, event) {
                console.log('Register form submitHandler called');
                if (event) event.preventDefault();
                var entity = Object.fromEntries(new FormData(form).entries());
                // Only keep the required fields
                entity.gender = $("input[name='papa']:checked").val();
                delete entity.papa;
                AuthService.register(entity);
                return false;
            },
        });
    }
}; 