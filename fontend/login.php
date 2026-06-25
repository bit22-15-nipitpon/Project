<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="asset/bootstrap-5.3.8-dist/css/bootstrap.min.css">
</head>

<body>
    <!-- main content -->
    <div class="container vh-100 d-flex flex-column justify-content-center align-items-center">
        <!-- error arlet -->
        <div class="alert alert-danger d-none mb-3" id="error"></div>

        <div class="card shadow" style="width: 400px;">
            <div class="card-body">
                <div class="d-flex justify-content-end">
                    <i class="fs-1" role="button" id="back">&times;</i>
                </div>
                <h2 class="text-center mb-4">Login</h2>

                <form id="login">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username:</label>
                        <input type="text" class="form-control" id="username">
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password:</label>
                        <input type="password" class="form-control" id="password">
                    </div>

                    <div class="mb-3 ">
                        <button type="submit" class="btn btn-primary w-100">Login</button>
                        <p class="text-center">Don't have an account? <a href="register.php" class="text-primary">Register</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('back').addEventListener('click', () => {
            history.back();
        })

        const login = document.getElementById('login');

        login.addEventListener('submit', (e) => {
            e.preventDefault();

            const user = document.getElementById('username').value.trim();
            const pass = document.getElementById('password').value.trim();
            const error = document.getElementById('error');

            error.classList.add('d-none');

            if (!user) {
                error.innerHTML = "Please enter a username";
                error.classList.remove('d-none');
                return;
            }

            if (!pass) {
                error.innerHTML = "Please enter a password";
                error.classList.remove('d-none');
                return;
            }

            const formdata = new FormData();
            formdata.append("username", user);
            formdata.append("password", pass);

            const requestOptions = {
                method: "POST",
                body: formdata,
                redirect: "follow"
            };

            fetch("http://10.26.56.25/project/api/login", requestOptions)
                .then((response) => response.json())
                .then((result) => {
                    console.log(result)
                    if(!result.success) {
                        error.innerHTML = "Invalid Username and Password";
                        error.classList.remove('d-none');
                        return;
                    }

                    localStorage.setItem("token", result.data.token);

                    if (result.data.user.role == "admin") {
                        window.location.href = "admin.php";
                    }
                    if (result.data.user.role == "user") {
                        window.location.href = "user.php";
                    }
                })
                .catch((error) => console.error(error));
        });
    </script>
</body>

</html>