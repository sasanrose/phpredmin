<?php $this->addHeader("<script src=\"{$this->router->baseUrl}/js/redmin/actions.js\" type=\"text/javascript\"></script>"); ?>
<div id='mainContainer'>
    <h3>Edit Hash</h3>
    <?=$this->renderPartial('hashes/add', array('oldkey' => $this->key))?>
    <h5><i class="icon icon-key"></i> <?=$this->key?></h5>
    <table class="table table-striped settable keys-table">
        <tr>
            <th>Key</th>
            <th>Value</th>
            <th>Edit</th>
            <th>Delete</th>
            <th></th>
        </tr>
        <?php foreach ($this->members as $member => $value): ?>
            <tr>
                <td>
                    <?=$member?>
                </td>
                <td>
                    <?=$value?>
                </td>
                <td>
                    <a href="<?=$this->router->url?>/hashes/edit/<?= $this->app->current['serverId'] . '/' . $this->app->current['database'] ?>/<?=urlencode($this->key)?>/<?=urlencode($member)?>" target="_blank" class="action">
                        <i class="icon icon-edit"></i>
                    </a>
                </td>
                <td>
                    <a href="#" class="action del">
                        <i class="icon icon-trash" id="<?=$member?>" keytype="hashes" keyinfo="<?=$this->key?>"></i>
                    </a>
                </td>
                <td>
                    <input type="checkbox" name="keys[]" value="<?=$member?>" class="key-checkbox" />
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if (!empty($this->members)): ?>
            <tr>
                <td colspan="3">
                </td>
                <td>
                    <a href="#" class="action delall">
                        <i class="icon icon-trash" keytype="hashes" keyinfo="<?=$this->key?>"></i>
                    </a>
                </td>
                <td>
                    <input type="checkbox" name="checkall" id="checkall" class="all-key-checkbox" />
                </td>
            </tr>
        <?php endif; ?>
    </table>
</div>
