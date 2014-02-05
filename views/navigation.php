<ul class="nav nav-pills nav-stacked">
    <li class="nav-header">Server & databases</li>
    <li class="server active">
        <a href="<?=$this->router->url?>/welcome/index/<?= $this->app->current['serverId'] . '/0' ?>">
            <i class="icon-chevron-down"></i> <?= $this->app->current['host'] ?>:<?= $this->app->current['port'] ?>
        </a>
    </li>
</ul>
<div class="tabbable tabs-left" id="dbTabs">
    <ul class="nav nav-tabs">
        <?php foreach ($this->app->current['dbs'] as $database): ?>
            <li class="database <?= ($database['id'] == $this->app->current['database'] ? 'active':null) ?>">
                <a href="<?=$this->router->url?>/welcome/index/<?= $this->app->current['serverId'] . '/' . $database['id'] ?>">
                    <i class="<?= $dbIcon ?>"></i> <?= ($database['name'] !== null ? $database['name'] : "DB ".$database['id']) ?>
                    <span class="label pull-right" title="Number of keys"><?= $database['keys'] ?></span>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
<ul class="nav nav-pills nav-stacked" id="srvList">
    <li class="nav-header">Other servers</li>    
    <?php foreach ($this->app->config['database']['redis'] as $serverId => $server): ?>
        <?php if ($serverId != $this->app->current['serverId']) : ?>
            <li class="server">
                <a href="<?=$this->router->url?>/welcome/index/<?= $serverId . '/0' ?>">
                    <i class="icon-chevron-right"></i> <?= $server['host'] ?>:<?= $server['port'] ?>
                </a>
            </li>
        <?php endif; ?>
    <?php endforeach; ?>  
</ul>                                
