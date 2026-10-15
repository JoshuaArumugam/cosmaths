<!DOCTYPE html>
<html style="width: 100%; height: 100%;">
    <head>
        <title>CosMaths Log-in</title>
        <script>
            function showPassword() {
                // get checkbox element
                let checkbox = document.getElementById("showpassword");
                // get password input element
                let passwordbox = document.getElementById("password");

                // if checkbox checked, set input type to text, else set input type to password to hide text
                if (checkbox.checked == true) {
                    passwordbox.type = "text";
                }
                else {
                    passwordbox.type = "password";
                }
            }
        </script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    </head>
    <body style="margin: 0; width: 100%; height: 100%;">
        <div style="display: flex; width: 100%; height: 100%; flex-direction: column; justify-content: center; align-items: center; background-color: #dbdbdb;">
            <div class="panel panel-primary" style="margin: 0; width: 30%;">
                <div class="panel-heading" style=" display: flex; align-items: center; justify-content: center;">
                    <h1 style="margin: 0;">Log-in</h1>
                </div>
                <div class="panel-body">
                    <?php
                        // start session to use session variables
                        session_start();

                        // check log in status to check whether there was a previous attempt
                        if (isset($_SESSION["loginstatus"])) {
                            if (!$_SESSION["loginstatus"]) {
                                // display error message
                                echo("<div class='alert alert-danger'>Log-in error: " . $_SESSION["loginerrormsg"] . "</div>");
                            }
                        }
                    ?>
                    <form action="processlogin.php" method="post" autocomplete="off">
                        <div class="form-group">
                            <label for="email">Email Address:</label>
                            <input type="text" id="email" name="email" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="password">Password:</label>
                            <input type="password" id="password" name="password" class="form-control">
                        </div>
                        <div class="checkbox">
                            <label><input type="checkbox" id="showpassword" onclick="showPassword()"> <b>Show password</b></label>
                        </div>
                        <button type="submit" class="btn btn-default">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>
