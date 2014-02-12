<?php $this->addHeader("<script src=\"{$this->router->baseUrl}/js/redmin/actions.js\" type=\"text/javascript\"></script>"); ?>
<div id='mainContainer'>
    <h3>Edit Sorted Set</h3>
    <?=$this->renderPartial('zsets/add', array('oldkey' => $this->key))?>
    <h5><i class="icon-key"></i> <?=$this->key?></h5>
    <table class="table table-striped settable">
        <tr>
            <th>Value</th>
            <th>Score</th>
            <th>Delete</th>
            <th></th>
        </tr>
        <?php foreach ($this->values as $member => $value) { ?>
            <tr>
                <td>
                    <?=$member?>
                </td>
                <td>
                    <?=$value?>
                </td>
                <td>
                    <a href="#" class="action del">
                        <i class="icon-trash" id="<?=$member?>" keytype="zsets" keyinfo="<?=$this->key?>"></i>
                    </a>
                </td>
                <td>
                    <input type="checkbox" name="keys[]" value="<?=$member?>" />
                </td>
            </tr>
        <?php } ?>
        <?php if (!empty($this->values)) { ?>
            <tr>
                <td colspan="2">
                </td>
                <td>
                    <a href="#" class="action delall">
                        <i class="icon-trash" keytype="zsets" keyinfo="<?=$this->key?>"></i>
                    </a>
                </td>
                <td>
                    <input type="checkbox" name="checkall" id="checkall" />
                </td>
            </tr>
        <?php } ?>
    </table>
    <?php if ($this->count > 30) {
        $ceil = floor($this->count / 30);
    ?>
        <ul class="pager">
            <li class="previous <?php if ($this->page == 0) echo "disabled";?>">
                <a href="<?=$this->router->url?>/zsets/view/<?= $this->app->current['serverId'] . '/' . $this->app->current['database'] ?>/<?=urlencode($this->key)?>/<?=$this->page - 1?>">&larr; Previous</a>
            </li>
            <li class="next <?php if ($this->page == $ceil) echo "disabled";?>">
                <a href="<?=$this->router->url?>/zsets/view/<?= $this->app->current['serverId'] . '/' . $this->app->current['database'] ?>/<?=urlencode($this->key)?>/<?=$this->page + 1?>">Next &rarr;</a>
            </li>
        </ul>
    <?php } ?>
</div>
