<?php if (App::instance()->config['terminal']['enable']): ?>
    <?php $this->addHeader("<script src=\"{$this->router->baseUrl}/js/redmin/terminal.js\" type=\"text/javascript\"></script>"); ?>
    <div class="row-fluid span12 terminal terminal-console">
        redis <?= $this->app->current['host'] ?>:<?= $this->app->current['port'] ?>>
    </div>
    <div class="clearfix"></div>
    <div>
        <div class="span6 terminal terminal-command-line">
            <div class="span1 terminal-prompt">></div><input class="span11" id="terminal-input" />
        </div>
        <div class="terminal-clear icon-eraser icon-2x"></div>
    </div>
    <div class="clearfix"></div>
    <br/>
    <div class="alert alert-warning">
        <a class="close" data-dismiss="alert" href="#">×</a>
        This functionaliy takes advantage of <a href="http://www.php.net/manual/en/function.exec.php" target="_blank">PHP's exec function</a>. Although, all the commands are escaped for security, you can disable terminal from configuration file.
    </div>
    <?=$this->renderPartial('generalmodals')?>
<?php else: ?>
    <div class="alert alert-danger">
        Terminal is not enabled.
    </div>
<?php endif; ?>
