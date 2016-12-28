<?php if (App::instance()->config['action']['edit_value'] || App::instance()->config['action']['delete_value']) {
	$this->addHeader("<script src=\"{$this->router->baseUrl}/js/redmin/actions.js\" type=\"text/javascript\"></script>"); 
} ?>
<div id='mainContainer'>
    <?php if (App::instance()->config['action']['edit_value']): ?>
    <h3>Edit Sorted Set</h3>
    <?php else: ?>
    <h3>View Sorted Set</h3>
    <?php endif; if (App::instance()->config['action']['edit_value']): ?>
    <?=$this->renderPartial('zsets/add', array('oldkey' => $this->key))?>
    <?php endif; ?>
    <h5><i class="icon icon-key"></i> <?=$this->key?></h5>
    <table class="table table-striped settable keys-table">
        <tr>
            <th>Value</th>
            <th>Score</th>
            <?php if (App::instance()->config['action']['delete_value']): ?>
            <th>Delete</th>
            <th></th>
            <?php endif; ?>
        </tr>
        <?php foreach ($this->values as $member => $value): ?>
            <tr>
                <td>
                    <?=$member?>
                </td>
                <td>
                    <?=$value?>
                </td>
                <?php if (App::instance()->config['action']['delete_value']): ?>
                <td>
                    <a href="#" class="action del">
                        <i class="icon icon-trash" id="<?=$member?>" keytype="zsets" keyinfo="<?=$this->key?>"></i>
                    </a>
                </td>
                <td>
                    <input type="checkbox" name="keys[]" value="<?=$member?>" class="key-checkbox" />
                </td>
                <?php endif; ?>
            </tr>
        <?php endforeach; ?>
        <?php if (App::instance()->config['action']['delete_value'] && !empty($this->values)): ?>
            <tr>
                <td colspan="2">
                </td>
                <td>
                    <a href="#" class="action delall">
                        <i class="icon icon-trash" keytype="zsets" keyinfo="<?=$this->key?>"></i>
                    </a>
                </td>
                <td>
                    <input type="checkbox" name="checkall" id="checkall" class="all-key-checkbox" />
                </td>
            </tr>
        <?php endif; ?>
    </table>
    <?php if ($this->count > 30): ?>
        <ul class="pager">
            <li class="previous <?php if ($this->page == 0) {
    echo "disabled";
}?>">
                <a href="<?=$this->router->url?>/zsets/view/<?= $this->app->current['serverId'] . '/' . $this->app->current['database'] ?>/<?=urlencode($this->key)?>/<?=$this->page - 1?>">&larr; Previous</a>
            </li>
            <li class="next <?php if ($this->page == floor($this->count / 30)) {
    echo "disabled";
}?>">
                <a href="<?=$this->router->url?>/zsets/view/<?= $this->app->current['serverId'] . '/' . $this->app->current['database'] ?>/<?=urlencode($this->key)?>/<?=$this->page + 1?>">Next &rarr;</a>
            </li>
        </ul>
    <?php endif; ?>
</div>
