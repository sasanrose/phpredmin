<script type="text/javascript">
    $(document).ready(function() {
        $('.del').click(function(e) {
            e.preventDefault();
            
            var tr = $(e.target).parents('tr');

            console.log(tr);

            $('.modal-footer .deletekey').click(function() {
                $.ajax({
                    url: '<?=$this->router->url?>/keys/delete/'+encodeURIComponent($(e.target).attr('id')),
                    dataType: 'json',
                    success: function(data) {
                        $('#del_confirmation').modal('hide');
                        tr.remove();
                    }
                });
            });

            $('#del_confirmation').modal('show');
        });
    });
</script>
<span class="span12">
    <h5><i class="icon-key"></i> Redis Keys</h5>
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
                    <a href="<?=$this->router->url?>/keys/expire/<?=urlencode($key)?>" target="_blank" class="action">
                        <i class="icon-time"></i>
                    </a>
                </td>
                <td>
                    <a href="<?=$this->router->url?>/keys/rename/<?=urlencode($key)?>" target="_blank" class="action">
                        <i class="icon-edit"></i>
                    </a>
                </td>
                <td>
                    <a href="<?=$this->router->url?>/keys/view/<?=urlencode($key)?>" target="_blank" class="action">
                        <i class="icon-folder-open-alt"></i>
                    </a>
                </td>
                <td>
                    <a href="<?=$this->router->url?>/keys/move/<?=urlencode($key)?>" target="_blank" class="action">
                        <i class="icon-move"></i>
                    </a>
                </td>
                <td>
                    <a href="#" class="action del">
                        <i class="icon-trash" id="<?=$key?>"></i>
                    </a>
                </td>
            </tr>
        <?php } ?>
    </table>
</span>
<div id="del_confirmation" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="confirmation" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="confirmation">Are you sure?</h3>
    </div>
    <div class="modal-body">
        <p>You can not undo this action</p>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
        <button class="btn btn-danger deletekey">I am sure</button>
    </div>
</div>
