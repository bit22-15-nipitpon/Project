<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="asset/bootstrap-5.3.8-dist/css/bootstrap.min.css">
</head>
<body>
    <!-- error arlet -->
    <div class="error alert alert-danger d-none"></div>

    <!-- main content -->
    <div class="container vh-100 d-flex justify-content-center align-items-center">
        <div class="card shadow" style="width: 400px;">
            <div class="card-body">
                <div class="d-flex justify-content-end">
                    <i class="fs-1" role="button" id="back">&times;</i>
                </div>
                <h2 class="text-center mb-4">Register</h2>

                <form id="login">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username:</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password:</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Confirm Password:</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>

                    <div class="mb-3 ">
                        <button type="submit" class="btn btn-primary w-100">Register</button>
                        <p class="text-center">have an account? <a href="login.php" class="text-primary">Login</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('back').addEventListener('click', () => {
            history.back();
        })
    </script>
</body>
</html>