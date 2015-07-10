<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta charset="utf-8">

    <title>SCM4 Book Shop</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="css/bootstrap-3.3.4/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-3.3.4/bootstrap-theme.min.css" rel="stylesheet">
    <link href="css/main.css" rel="stylesheet">

</head>
<body>


<div class="navbar navbar-inverse navbar-fixed-top">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                    data-target="#bs-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/">SCM 4 Bookshop (V 2.0)</a>
        </div>


        <div class="navbar-collapse collapse" id="bs-navbar-collapse-1">
            <?php $view = ((isset($_REQUEST['view'])) ? $_REQUEST['view'] : '');
            var_dump($view) ?>
            <ul class="nav navbar-nav">
                <li><a href="index.php">Home</a></li>
                <li><a href="index.php?view=list" <?php ($view == 'list') ? 'class="active"' : '' ?>>List</a></li>
                <li><a href="index.php?view=search" <?php ($view == 'search') ? 'class="active"' : '' ?>>Search</a></li>
                <li>
                    <a href="index.php?view=checkout" <?php ($view == 'checkout') ? 'class="active"' : '' ?>>Checkout</a>
                </li>
            </ul>
            <ul class="nav navbar-nav navbar-right login">
                <li>
                    <a href="index.php?view=checkout">
                        <span class="badge"><?php echo Util::escape(ShoppingCard::size()); ?></span> <span
                            class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span></a>
                </li>
                <li class="dropdown">
                    <?php if ($user == null): ?>
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            Not logged in!
                            <b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu" role="menu">
                            <li>
                                <a href="index.php?view=login">Login now</a>
                            </li>
                        </ul>
                    <?php else: ?>
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                  Logged in as  <span class="badge"><?php echo Util::escape($user->getUserName()); ?></span>
                  <b class="caret"></b>
                </a>
                </a>
                <ul class="dropdown-menu" role="menu">
                  <li class="centered">
                    <form method="post" action="<?php echo Util::action('logout'); ?>">
                      <input class="btn btn-xs" role="button" type="submit" value="Logout" />
                    </form>
                    </li>
                  </ul>
              <?php endif; ?>
                </li>
            </ul>
            <!-- /. login -->

        </div>
        <!--/.nav-collapse -->
    </div>
</div>

<div class="container">

