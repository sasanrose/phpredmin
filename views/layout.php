<!DOCTYPE html>
<html>
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
        currentHost = "<?= $this->app->current['host'] ?>";
        currentPort = "<?= $this->app->current['port'] ?>";
        currentServerDb = "<?= $this->app->current['serverId'] . '/' . $this->app->current['database'] ?>";
        $(document).ready(function() {
            $('.disabled').click(function(e) {
                e.preventDefault();
            });

            $('#reset_stats').click(function(e) {
                e.preventDefault();

                $('.modal-footer .save').unbind();
                $('.modal-footer .save').click(function() {
                    $.ajax({
                        url: '<?=$this->router->url?>/actions/reset/<?= $this->app->current['serverId'] . '/' . $this->app->current['database'] ?>',
                        dataType: 'json',
                        success: function(data) {
                            location.href = '<?=$this->router->url?>/welcome/index/<?= $this->app->current['serverId'] . '/' . $this->app->current['database'] ?>';
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
                        url: '<?=$this->router->url?>/actions/fall/<?= $this->app->current['serverId'] . '/' . $this->app->current['database'] ?>',
                        dataType: 'json',
                        success: function(data) {
                            location.href = '<?=$this->router->url?>/welcome/index/<?= $this->app->current['serverId'] . '/0' ?>';
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
                        url: '<?=$this->router->url?>/actions/fdb/<?= $this->app->current['serverId'] . '/' . $this->app->current['database'] ?>',
                        dataType: 'json',
                        success: function(data) {
                            location.href = '<?=$this->router->url?>/welcome/index/<?= $this->app->current['serverId'] . '/0' ?>';
                        }
                    });
                });

                $('#confirmation').modal('show');
            });
                        
            $('#add_db').click(function(e) {
                e.preventDefault();

                $('.modal-footer .save').unbind();
                $('.modal-footer .save').click(function() {
                    var dbIdx = parseInt($('#dbIdx').val());
                    location.href = '<?=$this->router->url?>/welcome/index/<?= $this->app->current['serverId'] . '/' ?>'+dbIdx;
                });

                $('#addDB').modal('show');
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
                                    <a href="<?=$this->router->url?>/welcome/index/<?= $this->app->current['serverId'] . '/' . $this->app->current['database'] ?>">
                                        <i class="icon-white icon-home"></i> Home
                                    </a>
                                </li>
                                <li <?php if($this->router->request == $this->router->url."/welcome/info/") {?>
                                    class="active"<
                                <?php } ?>>
                                    <a href="<?=$this->router->url?>/welcome/info/<?= $this->app->current['serverId'] . '/' . $this->app->current['database'] ?>">
                                        <i class="icon-white icon-info-sign"></i> Info
                                    </a>
                                </li>
                                <li <?php if($this->router->request == $this->router->url."/welcome/config") {?>
                                    class="active"<
                                <?php } ?>>
                                    <a href="<?=$this->router->url?>/welcome/config/<?= $this->app->current['serverId'] . '/' . $this->app->current['database'] ?>">
                                        <i class="icon-white icon-cogs"></i> Configurations
                                    </a>
                                </li>
                                <li <?php if($this->router->request == $this->router->url."/welcome/stats") {?>
                                    class="active"<
                                <?php } ?>>
                                    <a href="<?=$this->router->url?>/welcome/stats/<?= $this->app->current['serverId'] . '/' . $this->app->current['database'] ?>">
                                        <i class="icon-white icon-bar-chart"></i> Stats
                                    </a>
                                </li>
                                <li <?php if($this->router->request == $this->router->url."/welcome/slowlog") {?>
                                    class="active"<
                                <?php } ?>>
                                    <a href="<?=$this->router->url?>/welcome/slowlog/<?= $this->app->current['serverId'] . '/' . $this->app->current['database'] ?>">
                                        <i class="icon-white icon-warning-sign"></i> Slow Log
                                    </a>
                                </li>
                                <?php if (App::instance()->config['terminal']['enable']): ?>
                                    <li <?php if($this->router->request == $this->router->url."/terminal/") {?>
                                        class="active"<
                                    <?php } ?>>
                                        <a href="<?=$this->router->url?>/terminal/index/<?= $this->app->current['serverId'] . '/' . $this->app->current['database'] ?>">
                                            <i class="icon-white icon-terminal"></i> Terminal
                                        </a>
                                    </li>
                                <?php endif; ?>
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
                                            <a href="<?=$this->router->url?>/welcome/save/<?= $this->app->current['serverId'] . '/' . $this->app->current['database'] ?>/1" target="_blank">
                                                <i class="icon-save"></i> Asynchronous Save
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?=$this->router->url?>/welcome/save/<?= $this->app->current['serverId'] . '/' . $this->app->current['database'] ?>" target="_blank">
                                                <i class="icon-save"></i> Synchronous Save
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
                <ul class="breadcrumb">
                    <li>
                        <?= isset($this->app->current['password']) ? '<i class="icon-lock" title="With password"></i>' : '<i class="icon-eye-open" title="No password"></i>' ?>
                        <?= $this->app->current['host'] ?>:<?= $this->app->current['port'] ?> <span class="divider">/</span>
                    </li>
                    <li>
                        <?= ($this->app->current['dbs'][$this->app->current['database']]['name'] ? $this->app->current['dbs'][$this->app->current['database']]['name'] . " (DB {$this->app->current['database']})" : "DB {$this->app->current['database']}") ?>
                    </li>
                </ul>
            </div>
        </div>
        <div class="row">
            <div class="span2">
                <?= $this->renderPartial('navigation') ?>
            </div>
            <div class="span10">
                <div class="row-fluid">
                    <?= $this->content ?>
                </div>
            </div>
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
    
    <div id="addDB" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="addDb" aria-hidden="true">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3>Add Database</h3>
        </div>
        <div class="modal-body">
            <p>Databases are not created until data is added and they are initialized. To begin, 
            specify the database index you want to initialize. You will be redirected and able to 
            add data to the database</p>
            <hr />
            <form class="form-horizontal">
                <div class="control-group">
                    <label class="control-label" for="inputEmail">Database: </label>
                    <div class="controls">
                        <select name="dbIdx" id="dbIdx">
                        <?php for ($x=0; $x < $this->app->current['max_databases']; $x++): ?>
                            <?php if (!array_key_exists($x, $this->app->current['dbs'])): ?>
                            <option value='<?=$x?>'>DB <?=$x?></option>
                            <?php endif; ?>
                        <?php endfor; ?>
                        </select>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
            <button class="btn btn-primary save">Create</button>
        </div>
    </div>
</body>
</html>
