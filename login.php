<!DOCTYPE html>
<html>
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
    </head>
    <body>
        <h1>Log-in</h1>
        <?php
            // start session to use session variables
            session_start();

            // check log in status to check whether there was a previous attempt
            if (isset($_SESSION["loginstatus"])) {
                if (!$_SESSION["loginstatus"]) {
                    // display error message
                    echo("<p>Log-in error: " . $_SESSION["loginerrormsg"] . "</p>");
                }
            }
        ?>
        <form action="processlogin.php" method="post" autocomplete="off">
            <label for="email">Email Address:</label><br>
            <input type="text" id="email" name="email"><br>
            <label for="password">Password:</label><br>
            <input type="password" id="password" name="password"><br>
            <input type="checkbox" id="showpassword" onclick="showPassword()"><label for="showpassword">Show password</label><br>
            <input type="submit" value="Login">
        </form>
    </body>
</html>
