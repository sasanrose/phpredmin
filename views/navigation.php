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
		<?php if (isset($this->app->current['dbs']) && !empty($this->app->current['dbs'])): ?>
        <?php foreach ($this->app->current['dbs'] as $database): ?>
            <li class="database <?= ($database['id'] == $this->app->current['database'] ? 'active':null) ?>">
                <a href="<?=$this->router->url?>/welcome/index/<?= $this->app->current['serverId'] . '/' . $database['id'] ?>">
                    <?= ($database['name'] !== null ? $database['name'] : "DB ".$database['id']) ?>
                    <span class="label pull-right" title="Number of keys"><?= $database['keys'] ?></span>
                </a>
            </li>
        <?php endforeach; endif; if ($this->app->current['newDB']): ?>
            <li class="database active">
                <a href="<?=$this->router->url?>/welcome/index/<?= $this->app->current['serverId'] . '/' . $this->app->current['database'] ?>">
                   <i class="icon-plus"></i> DB <?=$this->app->current['database']?>
                    <span class="label pull-right" title="Number of keys">0</span>
                </a>
            </li>
        <?php else: ?>
            <li class="database">
                <a href="#" id="add_db"><i class="icon-plus"></i> Add DB</a>
            </li>
        <?php endif; ?>
    </ul>
</div>
<?php if (count($this->app->config['database']['redis']) > 1) : ?>
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
<?php endif; ?>
