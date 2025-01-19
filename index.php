<!DOCTYPE html>
<html>
<head>
    <title>Register | YOUR_SERVER_NAME</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <link href="images/favicon.png"  rel="icon"  media="(prefers-color-scheme: light)" />
    <link href="images/favicon-light.png"  rel="icon"  media="(prefers-color-scheme: dark)" />
    <style>
        html, body {
            height: 100%;
        }
        body {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .alert-container {
            position: fixed;
            top: 100px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1000;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <div class="text-center mb-3">
                            <img src="images/logo-light.png" alt="Logo" class="img-fluid" style="max-height: 100px;">
                        </div>
                        <!-- TODO: Uncomment this when you want to add your server name aside from logo -->
                        <!-- <h1 class="text-center fs-2">ADDYOUR SERVER NAME</h1> -->
                        <h3 class="text-center fs-4">Register Account</h3>
                    </div>
                    <div class="card-body">
                        <form action="process.php" method="POST" class="needs-validation" novalidate>
                            <div class="mb-3">
                                <label for="username" class="form-label">Username:</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    <input type="text" class="form-control" id="username" name="username" required>
                                    <div class="invalid-feedback">
                                        Please choose a username.
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email:</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                    <div class="invalid-feedback">
                                        Please enter a valid email address.
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password:</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                    <div class="invalid-feedback">
                                        Please enter a password.
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">Confirm Password:</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                    <div class="invalid-feedback">
                                        Passwords must match.
                                    </div>
                                </div>
                            </div>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">Register</button>
                            </div>
                            <div class="text-center mt-3">
                                <small>Already have an account? <br> Login in the Game.</small>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer bg-light">
                        <div class="d-flex justify-content-center gap-4">
                            <a href="" class="text-decoration-none"><i class="fab fa-facebook fa-2x text-primary"></i></a>
                            <a href="" class="text-decoration-none"><i class="fab fa-discord fa-2x text-primary"></i></a>
                            <a href="" class="text-decoration-none"><i class="fab fa-google-drive fa-2x text-primary"></i></a>
                            <a href="" class="text-decoration-none"><i class="fas fa-fire fa-2x text-primary"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="alert-container">
        <div id="alertMessage" class="alert" style="display: none;" role="alert"></div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
    
    <script>
        (() => {
            'use strict'

            const form = document.querySelector('.needs-validation');
            const alertDiv = document.getElementById('alertMessage');

            form.addEventListener('submit', async (event) => {
                event.preventDefault();
                event.stopPropagation();

                form.classList.add('was-validated');
                const password = form.querySelector('#password');
                const confirmPassword = form.querySelector('#confirm_password');
                
                if (!form.checkValidity()) {
                    return;
                }

                if (password.value !== confirmPassword.value) {
                    confirmPassword.setCustomValidity('Passwords do not match');
                    return;
                } else {
                    confirmPassword.setCustomValidity('');
                }

                try {
                    const formData = new FormData(form);
                    const response = await fetch('process.php', {
                        method: 'POST',
                        body: formData
                    });

                    const result = await response.json();
                    
                    alertDiv.style.display = 'block';
                    alertDiv.className = `alert ${result.success ? 'alert-success' : 'alert-danger'}`;
                    alertDiv.innerHTML = result.message;

                    if (result.success) {
                        form.reset();
                        form.classList.remove('was-validated');
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                        
                        setTimeout(() => {
                            alertDiv.style.display = 'none';
                        }, 5000);
                    }

                } catch (error) {
                    alertDiv.style.display = 'block';
                    alertDiv.className = 'alert alert-danger';
                    alertDiv.innerHTML = 'An error occurred. Please try again later.';
                }
            });
        })();
    </script>
</body>
</html>
