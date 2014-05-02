<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Wordpress Database Migration</title>

        <!-- Bootstrap -->
        <link href="css/bootstrap.min.css" rel="stylesheet">

        <style>

            .container{
                padding:40px 0;
                width:600px;
                text-align: center;
            }

            table{
                border:1px solid #bbbbbb;
                border-radius:3px;
                text-align: left;
            }

            a{
                color: inherit;
            }

            label {
                display: block;
            }

            input{
                display: inline-block !important;
                width: 40% !important;
                text-align: center;
                font-weight: normal;
                margin:10px 0 10px 0;
            }

            .alert a{
                text-decoration: underline;
            }

        </style>

    </head>
    <body>

        <?php include_once('config.php'); ?>

        <div class="container">

            <div class="page-header">
                <h1>Wordpress Database Migration</h1>
            </div>

            <p class="lead">Update absolute URLs and other settings.</p>

            <?php
                if( isset($_POST['submit']) ){
                    include_once('inc/migrate.php');
                }
            ?>

            <hr />

            <?php if( !isset( $_POST['submit'] ) || $error ) : ?>

                <form method="POST" action="<?php echo $_SERVER['PHP_SELF']?>">

                    <label>
                        DB Name
                        <br />
                        <input class="form-control" type="text" name="db_name" value="<?php echo isset($_POST['db_name']) ? $_POST['db_name'] : DATABASE_NAME; ?>" />
                    </label>

                    <label>
                        DB User
                        <br />
                        <input class="form-control" type="text" name="db_user" value="<?php echo isset($_POST['db_user']) ? $_POST['db_user'] : DATABASE_USER; ?>" />
                    </label>

                    <label>
                        DB Password
                        <br />
                        <input class="form-control" type="text" name="db_password" value="<?php echo isset($_POST['db_password']) ? $_POST['db_password'] : DATABASE_PASSWORD; ?>" />
                    </label>

                    <label>
                        DB Table Prefix
                        <br />
                        <input class="form-control" type="text" name="db_prefix" value="<?php echo isset($_POST['db_prefix']) ? $_POST['db_prefix'] : DB_TABLE_PREFIX; ?>" />
                    </label>

                    <label>
                        Old Site URL (e.g. www.old_site.dev)
                        <br />
                        <input class="form-control" type="text" name="old_site_url" value="<?php echo isset($_POST['old_site_url']) ? $_POST['old_site_url'] : OLD_SITE_URL; ?>" />
                    </label>

                    <label>
                        New Site URL (e.g. www.new_site.co.uk)
                        <br />
                        <input class="form-control" type="text" name="new_site_url" value="<?php echo isset($_POST['new_site_url']) ? $_POST['new_site_url'] : NEW_SITE_URL; ?>" />
                    </label>

                    <input type="submit" name="submit" class="btn btn-primary" value="Migrate Website" />
                </form>

            <?php endif; ?>

            <hr />

            <?php echo date('Y'); ?> <a href="http://www.scottcarmichael.co.uk">Scott Carmichael</a>

        </div>

        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="js/bootstrap.min.js"></script>
    </body>
</html>

