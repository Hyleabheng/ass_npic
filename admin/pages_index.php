<?php
session_start();
include('conf/config.php'); //get configuration file
if (isset($_POST['login'])) {
  $email = $_POST['email'];
  $password = sha1(md5($_POST['password'])); //double encrypt to increase security
  
  // 1. Check if Admin
  $stmt = $mysqli->prepare("SELECT email, password, admin_id FROM iB_admin WHERE email=? AND password=?");
  $stmt->bind_param('ss', $email, $password);
  $stmt->execute();
  $stmt->bind_result($db_email, $db_password, $admin_id);
  $rs = $stmt->fetch();
  $stmt->close();
  
  if ($rs) {
    $_SESSION['admin_id'] = $admin_id;
    header("location:pages_dashboard.php");
    exit;
  }
  
  // 2. Check if Staff
  $stmt2 = $mysqli->prepare("SELECT email, password, staff_id FROM iB_staff WHERE email=? AND password=?");
  $stmt2->bind_param('ss', $email, $password);
  $stmt2->execute();
  $stmt2->bind_result($db_email, $db_password, $staff_id);
  $rs2 = $stmt2->fetch();
  $stmt2->close();
  
  if ($rs2) {
    $_SESSION['staff_id'] = $staff_id;
    header("location:../staff/pages_dashboard.php");
    exit;
  } else {
    $err = "Access Denied Please Check Your Credentials";
  }
}

/* Persisit System Settings On Brand */
$ret = "SELECT * FROM `iB_SystemSettings` ";
$stmt = $mysqli->prepare($ret);
$stmt->execute(); //ok
$res = $stmt->get_result();
while ($auth = $res->fetch_object()) {
?>
<!-- Log on to codeastro.com for more projects! -->
  <!DOCTYPE html>
  <html>
  <meta http-equiv="content-type" content="text/html;charset=utf-8" />
  <?php include("dist/_partials/head.php"); ?>

  <body class="hold-transition login-page">
    <div class="login-box ib-login-box">
      <!-- /.login-logo -->
      <div class="card ib-card">
        <img class="ib-banner" src="dist/img/banner_logo.png" alt="Banner" loading="eager">
        <div class="card-body login-card-body">
          <p class="login-box-msg">Log In To Start Admin or Staff Session</p>
          <p class="text-center" style="margin:-6px 0 14px;font-weight:800;color:#0f172a;letter-spacing:.2px;">ACLEDA BANK Plc.</p>

          <form method="post">
            <div class="input-group mb-3">
              <input type="email" name="email" class="form-control" placeholder="Email">
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-envelope"></span>
                </div>
              </div>
            </div>
            <div class="input-group mb-3">
              <input type="password" name="password" class="form-control" placeholder="Password">
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-lock"></span>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-4">
                <div class="icheck-primary">
                  <!-- <input type="checkbox" id="remember">
                  <label for="remember">
                    Remember Me
                  </label> -->
                </div>
              </div>
              <!-- /.col -->
              <div class="col-8">
                <button type="submit" name="login" class="btn btn-danger btn-block">Log In</button>
              </div>
              <!-- /.col -->
            </div>
          </form>


          <!-- /.social-auth-links -->

          <!-- <p class="mb-1">
            <a href="pages_reset_pwd.php">I forgot my password</a>
          </p> -->
          <!--
          Uncomment this line to allow account creations for admins
          
      <p class="mb-0">
        <a href="pages_signup.php" class="text-center">Register a new membership</a>
      </p>
      -->
        </div>
        <!-- /.login-card-body -->
      </div>
    </div>
    <!-- /.login-box -->

    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.min.js"></script>

  </body>

  </html>
<?php
} ?>