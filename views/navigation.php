<ul class="nav nav-pills nav-stacked">
    <li class="nav-header">Server & databases</li>
    <li class="server active">
        <a href="<?=$this->router->url?>/welcome/index/<?= $this->app->current['serverId'] . '/0' ?>">
            <i class="icon-chevron-down"></i> <?= $this->app->current['host'] ?>:<?= $this->app->current['port'] ?>
        </a>
    </li>
    <?php foreach ($this->app->current['dbs'] as $database): ?>
        <?php 
            if ($database == $this->app->current['database']) {
                $dbClass = 'active';
                $dbIcon = 'icon-ok-sign';
            } 
            else {
                $dbClass = '';
                $dbIcon = '';
            }
        ?>
        <li class="database <?= $dbClass ?>">
            <a href="<?=$this->router->url?>/welcome/index/<?= $this->app->current['serverId'] . '/' . $database ?>">
                <i class="<?= $dbIcon ?>"></i> DB <?= $database ?>
            </a>
        </li>
    <?php endforeach; ?>
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
