<?=$this->renderPartial('actions')?>
<div id='mainContainer'>
    <h3>Search Results <small><?=count($this->keys)?> results found</small></h3>

    <div class="alert alert-warning">
        <a class="close" data-dismiss="alert" href="#">×</a>
        Since this doesn't support pagination yet, try to limit your search. Otherwise your browser might crash
    </div>
    <h5><i class="icon-key"></i> Redis Keys</h5>
    <form class="form-search" action="<?=$this->router->url?>/keys/search/<?= $this->app->current['serverId'] . '/' . $this->app->current['database'] ?>" method="post">
        <div class="input-prepend">
            <span class="add-on"><i class="icon-key"></i></span>
            <input type="text" value="<?=$this->search?>" name="key">
        </div>
        <button type="submit" class="btn"><i class="icon-search"></i> Search</button>
    </form>
    <table class="table table-striped">
        <tr>
            <th>Key</th>
            <th>Type</th>
            <th>TTL</th>
            <th>Ref Count</th>
            <th>Idle Time</th>
            <th>Encoding</th>
            <th>Size</th>
            <th>Expire</th>
            <th>Rename</th>
            <th>View</th>
            <th>Move</th>
            <th>Delete</th>
            <th></th>
        </tr>
        <?php foreach($this->keys as $key) {?>
            <tr>
                <td>
                    <?=$key?>
                </td>
                <td>
                    <?=Redis_Helper::instance()->getType($key)?>
                </td>
                <td>
                    <?=Redis_Helper::instance()->getTTL($key)?>
                </td>
                <td>
                    <?=Redis_Helper::instance()->getCount($key)?>
                </td>
                <td>
                    <?=Redis_Helper::instance()->getIdleTime($key)?>
                </td>
                <td>
                    <?=Redis_Helper::instance()->getEncoding($key)?>
                </td>
                <td>
                    <?=Redis_Helper::instance()->getSize($key)?>
                </td>
                <td>
                    <a href="<?=$this->router->url?>/keys/expire/<?= $this->app->current['serverId'] . '/' . $this->app->current['database'] ?>/<?=urlencode($key)?>" target="_blank" class="action">
                        <i class="icon-time"></i>
                    </a>
                </td>
                <td>
                    <a href="<?=$this->router->url?>/keys/rename/<?= $this->app->current['serverId'] . '/' . $this->app->current['database'] ?>/<?=urlencode($key)?>" target="_blank" class="action">
                        <i class="icon-edit"></i>
                    </a>
                </td>
                <td>
                    <a href="<?=$this->router->url?>/keys/view/<?= $this->app->current['serverId'] . '/' . $this->app->current['database'] ?>/<?=urlencode($key)?>" target="_blank" class="action">
                        <i class="icon-folder-open-alt"></i>
                    </a>
                </td>
                <td>
                    <a href="<?=$this->router->url?>/keys/move/<?= $this->app->current['serverId'] . '/' . $this->app->current['database'] ?>/<?=urlencode($key)?>" target="_blank" class="action">
                        <i class="icon-move"></i>
                    </a>
                </td>
                <td>
                    <a href="#" class="action del">
                        <i class="icon-trash" id="<?=$key?>" keytype="keys"></i>
                    </a>
                </td>
                <td>
                    <input type="checkbox" name="keys[]" value="<?=$key?>" />
                </td>
            </tr>
        <?php } ?>
        <?php if (!empty($this->keys)) { ?>
            <tr>
                <td colspan="10">
                </td>
                <td>
                    <a href="#" class="action moveall">
                        <i class="icon-move" keytype="keys" modaltitle="Move key to?" modaltip="Database Number"></i>
                    </a>
                </td>
                <td>
                    <a href="#" class="action delall">
                        <i class="icon-trash" keytype="keys"></i>
                    </a>
                </td>
                <td>
                    <input type="checkbox" name="checkall" id="checkall" />
                </td>
            </tr>
        <?php } ?>
    </table>
</div>
