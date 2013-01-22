<script type="text/javascript">
    $(document).ready(function() {
        $('.del').click(function(e) {
            e.preventDefault();
            
            var tr = $(e.target).parents('tr');

            $('.modal-footer .deletekey').unbind();
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

        $('#checkall').click(function(e) {
            $("input[name=keys\\[\\]]").attr('checked', $(e.target).is(':checked'));
        });

        $('.delall').click(function(e) {
            e.preventDefault();
            var checkboxes = $("input[name=keys\\[\\]]:checked");

            if (checkboxes.length > 0) {
                $('.modal-footer .deletekey').unbind();
                $('.modal-footer .deletekey').click(function() {
                    var values = [];
                    checkboxes.each(function() {
                        values.push($(this).val());
                    });

                    $.post(
                        baseurl+'/keys/delall',
                        {'values[]': values}, 
                        function(data) {
                            $('#del_confirmation').modal('hide');

                            checkboxes.each(function() {
                                $(this).parents('tr').remove();
                            });
                        }
                    );
                });
                $('#del_confirmation').modal('show');
            } else {
                invalid();
            }
        });
    });
</script>
<?=$this->renderPartial('generalmodals')?>
<span class="span12">
    <div class="alert alert-info">
        <a class="close" data-dismiss="alert" href="#">×</a>
        Number of results: <?=count($this->keys)?>
    </div>
    <h5><i class="icon-key"></i> Redis Keys</h5>
    <form class="form-search" action="<?=$this->router->url?>/keys/search" method="post">
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
                        <i class="icon-move"></i>
                    </a>
                </td>
                <td>
                    <a href="#" class="action delall">
                        <i class="icon-trash"></i>
                    </a>
                </td>
                <td>
                    <input type="checkbox" name="checkall" id="checkall" />
                </td>
            </tr>
        <?php } ?>
    </table>
</span>
<div id="del_confirmation" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="confirmation" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3>Are you sure?</h3>
    </div>
    <div class="modal-body">
        <p>You can not undo this action</p>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
        <button class="btn btn-danger deletekey">I am sure</button>
    </div>
</div>
