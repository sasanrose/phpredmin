<!DOCTYPE html>
<head>
    <title>PHPRedmin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" media="all" type="text/css" href="<?=$this->router->baseUrl?>/bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" media="all" type="text/css" href="<?=$this->router->baseUrl?>/bootstrap/css/bootstrap-responsive.min.css">
    <link rel="stylesheet" media="all" type="text/css" href="<?=$this->router->baseUrl?>/font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" media="all" type="text/css" href="<?=$this->router->baseUrl?>/js/nvd3/src/nv.d3.css" />
	<link rel="stylesheet" media="all" type="text/css" href="<?=$this->router->baseUrl?>/css/custom.css" />
	<link rel="stylesheet" media="all" type="text/css" href="<?=$this->router->baseUrl?>/js/jquery-ui/css/jquery-ui.min.css" />
    <script type="text/javascript" src="<?=$this->router->baseUrl?>/js/jquery.min.js"></script>
    <script type="text/javascript" src="<?=$this->router->baseUrl?>/bootstrap/js/bootstrap.min.js"></script>
    <?php foreach($this->getHeaders() as $header) {
        echo $header."\n";
    } ?>
    <script type="text/javascript">
        baseurl = "<?=$this->router->url?>";
        $(document).ready(function() {
            $('.disabled').click(function(e) {
                e.preventDefault();
            });

            $('#reset_stats').click(function(e) {
                e.preventDefault();

                $('.modal-footer .save').unbind();
                $('.modal-footer .save').click(function() {
                    $.ajax({
                        url: '<?=$this->router->url?>/actions/reset',
                        dataType: 'json',
                        success: function(data) {
                            location.href = '<?=$this->router->url?>';
                        }
                    });
                });

                $('#confirmation').modal('show');
            });

            $('#flush_all').click(function(e) {
                e.preventDefault();

                $('.modal-footer .save').unbind();
                $('.modal-footer .save').click(function() {
                    $.ajax({
                        url: '<?=$this->router->url?>/actions/fall',
                        dataType: 'json',
                        success: function(data) {
                            location.href = '<?=$this->router->url?>';
                        }
                    });

                });

                $('#confirmation').modal('show');
            });

            $('#flush_db').click(function(e) {
                e.preventDefault();

                $('.modal-footer .save').unbind();
                $('.modal-footer .save').click(function() {
                    $.ajax({
                        url: '<?=$this->router->url?>/actions/fdb',
                        dataType: 'json',
                        success: function(data) {
                            location.href = '<?=$this->router->url?>';
                        }
                    });
                });

                $('#confirmation').modal('show');
            });
        });
    </script>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="navbar span12 navbar-inverse">
                <div class="navbar-inner">
                    <div class="container">
                        <a class="btn btn-navbar" data-toggle="collapse" data-target=".navbar-responsive-collapse">
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </a>
                        <a class="brand" href="<?=$this->router->url?>">PHPRedmin</a>
                        <div class="nav-collapse collapse navbar-responsive-collapse">
                            <ul class="nav">
                                <li <?php if($this->router->request == $this->router->url) {?>
                                    class="active"<
                                <?php } ?>>
                                    <a href="<?=$this->router->url?>">
                                        <i class="icon-white icon-home"></i> Home
                                    </a>
                                </li>
                                <li <?php if($this->router->request == $this->router->url."/welcome/info") {?>
                                    class="active"<
                                <?php } ?>>
                                    <a href="<?=$this->router->url?>/welcome/info">
                                        <i class="icon-white icon-info-sign"></i> Info
                                    </a>
                                </li>
                                <li <?php if($this->router->request == $this->router->url."/welcome/config") {?>
                                    class="active"<
                                <?php } ?>>
                                    <a href="<?=$this->router->url?>/welcome/config">
                                        <i class="icon-white icon-cogs"></i> Configurations
                                    </a>
                                </li>
                                <li <?php if($this->router->request == $this->router->url."/welcome/stats") {?>
                                    class="active"<
                                <?php } ?>>
                                    <a href="<?=$this->router->url?>/welcome/stats">
                                        <i class="icon-white icon-bar-chart"></i> Stats
                                    </a>
                                </li>
                                <li <?php if($this->router->request == $this->router->url."/welcome/slowlog") {?>
                                    class="active"<
                                <?php } ?>>
                                    <a href="<?=$this->router->url?>/welcome/slowlog">
                                        <i class="icon-white icon-warning-sign"></i> Slow Log
                                    </a>
                                </li>
                                <li>
                                    <a href="https://github.com/sasanrose/phpredmin" target="_blank">
                                        <i class="icon-white icon-github"></i> Github
                                    </a>
                                </li>
                            </ul>
                            <ul class="nav pull-right">
                                <li class="divider-vertical"></li>
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Actions <b class="caret"></b></a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a href="#" id="flush_db">
                                                <i class="icon-trash"></i> Flush Db
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#" id="flush_all">
                                                <i class="icon-remove"></i> Flush All
                                            </a>
                                        </li>
                                        <li class="divider"></li>
                                        <li>
                                            <a href="#" id="reset_stats">
                                                <i class="icon-refresh"></i> Reset Stats
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="span12">
                <div class="alert alert-success">
                    <a class="close" data-dismiss="alert" href="#">×</a>
                    <?php
                        echo sprintf('redis://%s:%s', $this->app->config['database']['redis']['host'],
                                                      $this->app->config['database']['redis']['port']);
                    ?>
                </div>
                <?php $password = isset($this->app->config['database']['redis']['password']) ? True : False; ?>
                <div class="alert alert-<?php if ($password) echo "info"; else echo "warning"?>">
                    <a class="close" data-dismiss="alert" href="#">×</a>
                    Using Password: <?php if ($password) echo "Yes"; else echo "No"?>
                </div>
            </div>
        </div>
        <div class="row">
            <?=$this->content?>
        </div>
    </div>
    <div id="confirmation" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="confirmation" aria-hidden="true">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3>Are you sure?</h3>
        </div>
        <div class="modal-body">
            <p>You can not undo this action</p>
        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
            <button class="btn btn-danger save">I am sure</button>
        </div>
    </div>
</body>
</head>
