<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="/assets/logo-pln.png">

    <title>SIAMAM PLN ASAME</title>
    <link id="pagestyle" href="/assets/css/material-dashboard.css?v=3.0.5" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <script src="/assets/js/fontawesome-kit.js" crossorigin="anonymous"></script>
    <style>
        .password-toggle-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            z-index: 10;
            color: #7b809a;
        }
        .input-group.input-group-outline .form-control {
            padding-right: 40px !important;
        }
    </style>
    <?= $this->renderSection('pageStyles') ?>
</head>

<body>

    <?= '' // view('App\Views\Auth\_navbar') 
    ?>

    <?= $this->renderSection('main') ?>

    <script src="/assets/js/jquery-3.6.3.min.js"></script>
    <script src="/assets/js/core/popper.min.js"></script>
    <script src="/assets/js/core/bootstrap.min.js"></script>
    <script src="/assets/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="/assets/js/plugins/smooth-scrollbar.min.js"></script>
    <script src="/assets/js/material-dashboard.min.js?v=3.0.5"></script>
    <script>
        $(document).ready(function() {
            $(document).on('click', '.password-toggle-icon', function() {
                const icon = $(this).find('i');
                const input = $(this).siblings('input');
                if (input.attr('type') === 'password') {
                    input.attr('type', 'text');
                    icon.removeClass('fa-eye-slash').addClass('fa-eye');
                } else {
                    input.attr('type', 'password');
                    icon.removeClass('fa-eye').addClass('fa-eye-slash');
                }
            });
        });
    </script>

    <?= $this->renderSection('pageScripts') ?>
</body>

</html>