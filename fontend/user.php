<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Page</title>
    <link rel="stylesheet" href="asset/bootstrap-5.3.8-dist/css/bootstrap.min.css">
</head>

<body>
    <!-- system header -->
    <header class="container-fluid shadow-sm bg-warning py-2">
        <div class="mx-5">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mx-5">
                <!-- Logo + Website Name -->
                <div class="d-flex align-items-center mb-2 mb-md-0 mx-5">
                    <img src="asset/image/logo.png" width="80" class="me-2" alt="Logo">
                    <h1 class="h4 mb-0">Issue Reporting System</h1>
                </div>

                <!-- Welcome + Logout -->
                <nav class="d-flex align-items-center mx-5">
                    <span class="me-3 fw-semibold">Welcome User</span>
                    <button class="btn btn-outline-primary" id="logout">Logout</button>
                </nav>
            </div>
        </div>
    </header>

    <!-- main content -->

    <script>
        const token = localStorage.getItem("token");

        const logout = document.getElementById('logout');

        logout.addEventListener("click", () => {
            const myHeaders = new Headers();
            myHeaders.append("Authorization", `Bearer ${token}`);

            const requestOptions = {
                method: "POST",
                headers: myHeaders,
                redirect: "follow"
            };

            fetch("http://10.26.56.25/project/api/logout", requestOptions)
                .then(response => response.json())
                .then(result => {
                    console.log(result);

                    if (result.success) {
                        localStorage.removeItem("token");
                        window.location.href = "index.php";
                    }
                })
                .catch(error => console.error(error));
        });
    </script>
</body>

</html>