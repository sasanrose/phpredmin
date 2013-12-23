<ul class="nav nav-pills nav-stacked">
    <li class="nav-header">Servers & databases</li>
    <?php foreach ($this->app->config['database']['redis'] as $serverId => $server): ?>
        <?php 
            if ($serverId == $this->app->current['serverId']) {
                $serverClass = 'active';
                $serverIcon = 'icon-chevron-down';
            } 
            else {
                $serverClass = '';
                $serverIcon = 'icon-chevron-right';
            }
        ?>
        <li class="<?= $serverClass ?>">
            <a href="<?=$this->router->url?>/welcome/index/<?= $serverId . '/0' ?>">
                <i class="<?= $serverIcon ?>"></i> 
                <?= $server['host'] ?>:<?= $server['port'] ?>
            </a>
        </li>
        <?php if ($serverId == $this->app->current['serverId']) : ?>
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
                <li class="<?= $dbClass ?>">
                    <a href="<?=$this->router->url?>/welcome/index/<?= $serverId . '/' . $database ?>">
                        <i class="<?= $dbIcon ?>"></i> 
                        DB <?= $database ?>
                    </a>
                </li>
            <?php endforeach; ?>
        <?php endif; ?>
    <?php endforeach; ?>  
</ul>                                
