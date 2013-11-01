<?=$this->renderPartial('actions')?>
<div>
    <?=$this->renderPartial('hashes/add', array('oldkey' => $this->key))?>
    <h5><i class="icon-key"></i> <?=$this->key?></h5>
    <table class="table table-striped settable">
        <tr>
            <th>Key</th>
            <th>Value</th>
            <th>Edit</th>
            <th>Delete</th>
            <th></th>
        </tr>
        <?php foreach ($this->members as $member => $value) { ?>
            <tr>
                <td>
                    <?=$member?>
                </td>
                <td>
                    <?=$value?>
                </td>
                <td>
                    <a href="<?=$this->router->url?>/hashes/edit/<?=urlencode($this->key)?>/<?=urlencode($member)?>" target="_blank" class="action">
                        <i class="icon-edit"></i>
                    </a>
                </td>
                <td>
                    <a href="#" class="action del">
                        <i class="icon-trash" id="<?=$member?>" keytype="hashes" keyinfo="<?=$this->key?>"></i>
                    </a>
                </td>
                <td>
                    <input type="checkbox" name="keys[]" value="<?=$member?>" />
                </td>
            </tr>
        <?php } ?>
        <?php if (!empty($this->members)) { ?>
            <tr>
                <td colspan="3">
                </td>
                <td>
                    <a href="#" class="action delall">
                        <i class="icon-trash" keytype="hashes" keyinfo="<?=$this->key?>"></i>
                    </a>
                </td>
                <td>
                    <input type="checkbox" name="checkall" id="checkall" />
                </td>
            </tr>
        <?php } ?>
    </table>
</div>
<?=$this->renderPartial('generalmodals')?>
