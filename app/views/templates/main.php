<?php $user = new \App\models\User; ?>

<!DOCTYPE html>
<!--[if lt IE 7]><html lang="ru" class="lt-ie9 lt-ie8 lt-ie7"><![endif]-->
<!--[if IE 7]><html lang="ru" class="lt-ie9 lt-ie8"><![endif]-->
<!--[if IE 8]><html lang="ru" class="lt-ie9"><![endif]-->
<!--[if gt IE 8]><!-->
<html lang="ru">
<!--<![endif]-->

<head>
    <meta charset="utf-8" />
    <title><?=\App\helpers\Title::get();?></title>
    <meta name="description" content="" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="shortcut icon" href="favicon.png" />
    <!-- normalize -->
    <link rel="stylesheet" href="http://necolas.github.io/normalize.css">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="/public/libs/bootstrap/bootstrap.min.css" />
    <!-- font-awesome -->
    <link rel="stylesheet" href="http://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">
    <!-- <link rel="stylesheet" href="/public/css/fonts.css" /> -->
    <link rel="stylesheet" href="/public/css/style.css">
    <!-- <link rel="stylesheet" href="/public/css/media.css" /> -->
    
</head>

<body>
    <div class="wrapper">
        <div class="page">
            <div class="container">
                <nav class="navbar navbar-default">
                  <div class="container-fluid">
                    <!-- Brand and toggle get grouped for better mobile display -->
                    <div class="navbar-header">
                      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="/">TODO</a>
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
<!--                     <form class="navbar-form navbar-left">
                        <div class="form-group">
                          <input type="text" class="form-control" placeholder="Search">
                      </div>
                      <button type="submit" class="btn btn-default">Submit</button>
                  </form> -->

                  <ul class="nav navbar-nav navbar-right">
                      <?php if ($user->isLoggedIn()): ?>
                        <li class="dropdown">
                          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><strong><?=ucfirst($user->data()->username);?></strong><span style="margin-left: 10px;" class="caret"></span></a>
                          <ul class="dropdown-menu">
<!--                             <li><a href="#">Action</a></li>
                            <li><a href="#">Another action</a></li>
                            <li><a href="#">Something else here</a></li> -->
                            <!-- <li role="separator" class="divider"></li> -->
                            <li><a href="/logout">Log out</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                  <li><a href="/login">Login</a></li>
                  <li><a href="/register">Register</a></li>
              <?php endif; ?>
              </ul>
          </div><!-- /.navbar-collapse -->
      </div><!-- /.container-fluid -->
  </nav>
</div><!-- /.container -->
<?php require_once \App\helpers\Config::get('path/views') . '/' . $contentPage . '.php';  ?>
</div><!-- /.page -->
<footer>
    <div class="container">
        <p class="text-right">Kalayda Vitaly (C) 2017</p>
    </div>
</footer>
</div>
    <!--[if lt IE 9]>
    <script src="libs/html5shiv/es5-shim.min.js"></script>
    <script src="libs/html5shiv/html5shiv.min.js"></script>
    <script src="libs/html5shiv/html5shiv-printshiv.min.js"></script>
    <script src="libs/respond/respond.min.js"></script>
    <![endif]-->
    <script src="/public/libs/jquery/jquery-3.1.1.min.js"></script>
    <script src="/public/libs/bootstrap/bootstrap.min.js"></script>
    <script src="/public/js/common.js"></script>
</body> 
</html>