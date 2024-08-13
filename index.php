<?php 

    include("./src/headfiles/head.php"); 

?> <!--header files -->

<body>

    <!--main container-->
    <div class="wrapper">

        <!--header section-->
        <?php include("./src/header/header.php"); ?>
        <!--login section-->
        <?php include("./src/form/login_form.php"); ?>

    </div> <!--end of main container--->

    <!--text messae section-->
    <?php include("./src/msg_show/msg_show.php"); ?>

    <script type="module" src="./src/js/index.js"></script>
</body>

</html>