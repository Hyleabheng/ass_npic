<?php
/* Persisit System Settings On Brand */
$ret = "SELECT * FROM `iB_SystemSettings` ";
$stmt = $mysqli->prepare($ret);
$stmt->execute(); //ok
$res = $stmt->get_result();
while ($sys = $res->fetch_object()) {
?>

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>ACLEDA BANK Plc. - <?php echo $sys->sys_tagline; ?></title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- overlayScrollbars -->
        <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
        <!-- Ionicons -->
        <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
        <!-- icheck bootstrap -->
        <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="dist/css/adminlte.min.css">
        <!-- ACLEDA BANK Plc. custom UI -->
        <style>
            body.login-page {
                background: #ffffff;
            }

            .ib-login-box {
                width: 410px;
            }

            .ib-brand {
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 10px;
                margin-bottom: 16px;
                text-align: center;
                color: rgba(255, 255, 255, .92);
            }

            .ib-brand img {
                height: 46px;
                width: auto;
                object-fit: contain;
                filter: drop-shadow(0 10px 18px rgba(0, 0, 0, .35));
            }

            .ib-brand .ib-brand-title {
                font-weight: 800;
                letter-spacing: .2px;
                font-size: 16px;
                line-height: 1.25;
                margin: 0;
            }

            .ib-card {
                border-radius: 18px;
                overflow: hidden;
                border: 1px solid rgba(255, 255, 255, .08);
                box-shadow: 0 30px 70px rgba(0, 0, 0, .35);
            }

            .ib-banner {
                width: 100%;
                height: 54px;
                object-fit: contain;
                display: block;
                background: #ffffff;
                padding: 6px 10px;
                border-bottom: 1px solid rgba(15, 23, 42, .08);
            }

            .login-card-body {
                border-radius: 0;
                padding: 18px 18px 20px;
            }

            .login-box-msg {
                margin-bottom: 14px;
                color: #0f172a;
                font-weight: 700;
            }

            .login-card-body .form-control {
                border-radius: 12px;
                height: 44px;
                border-color: rgba(15, 23, 42, .12);
            }

            .login-card-body .input-group-text {
                border-radius: 12px;
                border-color: rgba(15, 23, 42, .12);
                background: #fff;
            }

            .login-card-body .btn {
                border-radius: 12px;
                height: 44px;
                font-weight: 800;
                letter-spacing: .2px;
            }

            @media (max-width: 480px) {
                .ib-login-box {
                    width: calc(100vw - 36px);
                }
            }
        </style>
        <!-- Google Font: Source Sans Pro -->
        <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
        <!--Data tables css-->
        <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.css">
        <!--load swal js -->
        <script src="dist/js/swal.js"></script>
        <!-- Favicon -->
        <link rel="icon" type="image/png" sizes="16x16" href="../admin/dist/img/acleda_logo.png">
        <!-- Data Tables CSS -->
        <link rel="stylesheet" type="text/css" href="plugins/datatable/custom_dt_html5.css">

        <!-- React (for global Loading overlay) -->
        <script defer src="https://unpkg.com/react@18/umd/react.production.min.js"></script>
        <script defer src="https://unpkg.com/react-dom@18/umd/react-dom.production.min.js"></script>
        <!-- Global Loading -->
        <script defer src="dist/js/ib-loading.js?v=11"></script>

        <!--Inject SWAL-->
        <?php if (isset($success)) { ?>
             <!--This code for injecting success alert-->
             <script>
                 setTimeout(function() {
                     // Check if loading API is available
                     if (window.iBLoading && window.iBLoading.show) {
                         window.iBLoading.show("Processing Success...");
                     }

                     setTimeout(function() {
                         // Hide loading before showing swal to avoid overlap
                         if (window.iBLoading && window.iBLoading.hide) {
                             window.iBLoading.hide();
                         }

                         swal("Successfully", "<?php echo $success; ?>", "success")
                             .then(() => {
                                 if (window.iBLoading && window.iBLoading.show) {
                                     window.iBLoading.show("Update Successfully");
                                     setTimeout(function() {
                                         window.iBLoading.hide();
                                     }, 1000);
                                 }
                             });
                     }, 800); // Show loading for 800ms
                 }, 100);
             </script>

         <?php } ?>

        <?php if (isset($err)) { ?>
            <!--This code for injecting error alert-->
            <script>
                setTimeout(function() {
                        swal("Failed", "<?php echo $err; ?>", "error");
                    },
                    100);
            </script>

        <?php } ?>
        <?php if (isset($info)) { ?>
            <!--This code for injecting info alert-->
            <script>
                setTimeout(function() {
                        swal("Success", "<?php echo $info; ?>", "warning");
                    },
                    100);
            </script>

        <?php } ?>
        <script>
            function getiBankAccs(val)

            {
                $.ajax({
                    //get account rates
                    type: "POST",
                    url: "pages_ajax.php",
                    data: 'iBankAccountType=' + val,
                    success: function(data) {
                        //alert(data);
                        $('#AccountRates').val(data);
                    }
                });

                $.ajax({
                    //get account transferable name
                    type: "POST",
                    url: "pages_ajax.php",
                    data: 'iBankAccNumber=' + val,
                    success: function(data) {
                        //alert(data);
                        $('#ReceivingAcc').val(data);
                    }
                });

                $.ajax({
                    //get account transferable holder | owner
                    type: "POST",
                    url: "pages_ajax.php",
                    data: 'iBankAccHolder=' + val,
                    success: function(data) {
                        //alert(data);
                        $('#AccountHolder').val(data);
                    }
                });
            }
        </script>
        <script>
            function confirmDelete(url) {
                // Use the official API to manage the overlay
                if (window.iBLoading && window.iBLoading.hide) {
                    window.iBLoading.hide();
                }

                swal({
                    title: "Are you sure?",
                    text: "Do you want to delete this data?",
                    icon: "warning",
                    buttons: ["No", "Yes"],
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        if (window.iBLoading && window.iBLoading.show) {
                            window.iBLoading.show("Deleting Records...");
                        }
                        window.location.href = url;
                    } else {
                        if (window.iBLoading && window.iBLoading.show) {
                            window.iBLoading.show("Keeping Records...");
                            setTimeout(() => window.iBLoading.hide(), 600);
                        }
                    }
                });
            }

            function confirmUpdate(event) {
                event.preventDefault(); // Prevent immediate form submission
                var form = event.target.closest('form');
                
                if (window.iBLoading && window.iBLoading.hide) {
                    window.iBLoading.hide();
                }

                swal({
                    title: "Are you sure?",
                    text: "Do you want to update this data?",
                    icon: "info",
                    buttons: ["No", "Yes"],
                    dangerMode: false,
                })
                .then((willUpdate) => {
                    if (willUpdate) {
                        if (window.iBLoading && window.iBLoading.show) {
                            window.iBLoading.show("Processing Changes...");
                        }
                        // If there's a submit button with a name, we need to ensure it's sent
                        const submitBtn = form.querySelector('button[type="submit"], input[type="submit"]');
                        if (submitBtn && submitBtn.name) {
                            var input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = submitBtn.name;
                            input.value = submitBtn.value || '1';
                            form.appendChild(input);
                        }
                        form.submit();
                    } else {
                         if (window.iBLoading && window.iBLoading.show) {
                            window.iBLoading.show("Continuing...");
                            setTimeout(() => window.iBLoading.hide(), 600);
                        }
                    }
                });
            }
        </script>

    </head>
<?php
} ?>