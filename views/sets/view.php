<?php if (App::instance()->config['action']['edit_value'] || App::instance()->config['action']['delete_value']) {
    $this->addHeader("<script src=\"{$this->router->baseUrl}/js/redmin/actions.js\" type=\"text/javascript\"></script>");
} ?>
<div id='mainContainer'>
    <?php if (App::instance()->config['action']['edit_value']): ?>
    <h3>Edit Set</h3>
    <?php else: ?>
    <h3>View Set</h3>
    <?php endif; if (App::instance()->config['action']['edit_value']): ?>
    <?=$this->renderPartial('sets/add', array('oldkey' => $this->key))?>
    <?php endif; ?>
    <h5><i class="icon icon-key"></i> <?=$this->key?></h5>
    <table class="table table-striped settable keys-table">
        <tr>
            <th>Value</th>
            <?php if (App::instance()->config['action']['edit_value']): ?>
            <th>Edit</th>
            <?php endif; if (App::instance()->config['action']['delete_value']): ?>
            <th>Delete</th>
            <th></th>
            <?php endif; ?>
        </tr>
        <?php foreach ($this->members as $member): ?>
            <tr>
                <td>
                    <?=$member?>
                </td>
                <?php if (App::instance()->config['action']['edit_value']): ?>
                <td>
                    <a href="<?=$this->router->url?>/sets/edit/<?= $this->app->current['serverId'] . '/' . $this->app->current['database'] ?>/<?=urlencode($this->key)?>/<?=urlencode($member)?>" target="_blank" class="action">
                        <i class="icon icon-edit"></i>
                    </a>
                </td>
                <?php endif; if (App::instance()->config['action']['delete_value']): ?>
                <td>
                    <a href="#" class="action del">
                        <i class="icon icon-trash" id="<?=$member?>" keytype="sets" keyinfo="<?=$this->key?>"></i>
                    </a>
                </td>
                <?php endif; if (App::instance()->config['action']['edit_value'] || App::instance()->config['action']['delete_value']):?>
                <td>
                    <input type="checkbox" name="keys[]" value="<?=$member?>" class="key-checkbox" />
                </td>
                <?php endif; ?>
            </tr>
        <?php endforeach; ?>
        <?php if ((App::instance()->config['action']['edit_value'] || App::instance()->config['action']['delete_value']) && !empty($this->members)): ?>
            <tr>
                <?php if (App::instance()->config['action']['edit_value'] && App::instance()->config['action']['delete_value']): ?>
                <td colspan="3">
                <?php else: ?>
                <td colspan="2">
                <?php endif; ?>
                </td>
                <td>
                    <input type="checkbox" name="checkall" id="checkall" class="all-key-checkbox" />
                    <?php if (App::instance()->config['action']['edit_value']): ?>
                    <a href="#" class="action moveall" style="margin-left: 10px;">
                        <i class="icon icon-move" keytype="sets" keyinfo="<?=$this->key?>" modaltitle="Move value to?" modaltip="Destination Set">
                        </i>
                    </a>
                    <?php endif; if (App::instance()->config['action']['delete_value']): ?>
                    <a href="#" class="action delall" style="margin-left: 10px;">
                        <i class="icon icon-trash" keytype="sets" keyinfo="<?=$this->key?>"></i>
                    </a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endif; ?>
    </table>
</div>
