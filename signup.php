<!DOCTYPE html>
<html>
    <head>
        <title>CosMaths Sign-up</title>
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
        <h1>Sign-up</h1>
        <form action="processsignup.php" method="post" autocomplete="off">
            <label for="username">Username:</label><br>
            <input type="text" id="username" name="username"><br>
            <label for="email">Email Address:</label><br>
            <input type="text" id="email" name="email"><br>
            <label for="password">Password:</label><br>
            <input type="password" id="password" name="password"><br>
            <input type="checkbox" id="showpassword" onclick="showPassword()"><label for="showpassword">Show password</label><br>
            <input type="submit" value="Sign Up">
        </form>
    </body>
</html>
