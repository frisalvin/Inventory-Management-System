<?php
session_start();
include "inc/koneksi.php";

if(isset($_POST['login'])){

    $username = $_POST['username'];
    $password = $_POST['password'];

    $cek = mysqli_query($conn,"
    SELECT * FROM admin 
    WHERE username='$username' 
    AND password='$password'
    ");

    if(mysqli_num_rows($cek) > 0){

        $_SESSION['login'] = true;
        header("Location:index.php");
        exit;

    }else{
        $error = "Username atau Password salah!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Login Admin</title>

<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{
background:#f1f2f6;
font-family:Arial;
margin:0;
}

/* WRAPPER AGAR RESPONSIVE TENGAH */
.login-wrapper{
min-height:100vh;
display:flex;
justify-content:center;
align-items:center;
padding:15px;
}

/* CARD */
.card{
width:400px;
max-width:100%;
border-radius:10px;
}

/* RESPONSIVE MOBILE */
@media(max-width:480px){

.card{
width:100%;
}

.card-header{
font-size:18px;
}

}

</style>

</head>
<body>

<div class="login-wrapper">

<div class="card">

<div class="card-header text-center">
<b>Login Admin</b>
</div>

<div class="card-body">

<?php if(isset($error)){ ?>
<div class="alert alert-danger"><?= $error ?></div>
<?php } ?>

<form method="POST">

<input name="username" class="form-control mb-2" placeholder="Username" required>

<!-- PASSWORD DENGAN ICON MATA -->
<div class="input-group mb-3">

<input 
type="password"
name="password"
id="password"
class="form-control"
placeholder="Password"
required>

<button type="button" class="btn btn-outline-secondary" onclick="togglePassword()">👁</button>

</div>

<button name="login" class="btn btn-primary w-100">Login</button>

</form>

</div>
</div>

</div>

<script>
function togglePassword(){

let input = document.getElementById("password");

if(input.type === "password"){
    input.type = "text";
}else{
    input.type = "password";
}

}
</script>

</body>
</html>