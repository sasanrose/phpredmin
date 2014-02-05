<script type="text/javascript">
    $(document).ready(function() {
        $('#redisTab a').click(function (e) {
            e.preventDefault();
            $(this).tab('show');
        });

        $('#bulkdelete').submit(function(e) {
            e.preventDefault();
            var url = baseurl+'/keys/bulkdelete/<?= $this->app->current['serverId'] . '/' . $this->app->current['database'] ?>';
            var val = $('#bulkkey').val();

            if ($('#delete_interval').val().trim() != '') {
                alert('There is another delete process going on');
                return;
            }

            $('.delete_progress').hide();
            $('.delete_progress_counts').hide();
            $('.delete_bar').css('width', '0%');
            $('.delete_error').hide();
            $('.delete_details').html('');

            if (val.trim() != '') {
                $('.modal-footer .save').unbind();
                $('.modal-footer .save').click(function() {
                    $.ajax({
                        url: url,
                        type: "POST",
                        data: {key: val},
                        dataType: 'json',
                        success: function(data) {
                            $('#confirmation').modal('hide');
                            $('#bulkkey').val('');
                            $('.delete_progress').addClass('active');
                            $('.delete_progress').show();
                            updateDeleteInfo(val);
                        }
                    });
                });

                $('#confirmation').modal('show');
            }
        });
    });

    var updateDeleteInfo = function(key) {
        var url = baseurl+'/keys/deleteinfo/<?= $this->app->current['serverId'] . '/' . $this->app->current['database'] ?>/'+key;
        int = window.setInterval(function() {
            $.ajax({
                url: url,
                type: "POST",
                dataType: 'json',
                success: function(data) {
                    $('#count_deleted').html(data[1]);
                    if (data[0] !== false) {
                        if (data[1] !== false) {
                            var percent = Math.round((data[1] * 100) / data[0]);
                            $('.delete_bar').css('width', percent+'%');
                            $('#total_delete_count').html(data[0]);
                            $('.delete_progress_counts').show();
                        }
                    } else {
                        var total = $('#total_delete_count').html();
                        if (data[1] != false && data[1] == total) {
                            $('.delete_bar').css('width', '100%');
                        } else if (total.trim() == '') {
                            $('.delete_error_exists').show();
                            $('.delete_progress').hide();
                        } else
                            $('.delete_error_incomplete').show();

                        $('.delete_progress').removeClass('active');
                        $('#delete_interval').val('');
                        window.clearInterval(int);
                    }
                }
            });
        }, 2000);

        $('#delete_interval').val(int);
    }

</script>
<?=$this->renderPartial('generalmodals')?>
<div>
    <ul class="nav nav-tabs" id="redisTab">
        <li class="active">
            <a href="#keys">Keys</a>
        </li>
        <li>
            <a href="#delete">Bulk Delete</a>
        </li>
        <li class='pull-right'>
            <a href="#sorted_sets">Sorted Sets</a>
        </li>
        <li class='pull-right'>
            <a href="#sets">Sets</a>
        </li>
        <li class='pull-right'>
            <a href="#lists">Lists</a>
        </li>
        <li class='pull-right'>
            <a href="#hashes">Hashes</a>
        </li>
        <li class='pull-right'>
            <a href="#strings">Strings</a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane fade active in" id="keys">
            <form class="form-search" action="<?=$this->router->url?>/keys/search/<?= $this->app->current['serverId'] . '/' . $this->app->current['database'] ?>" method="post">
                <legend>Search keys</legend>
                <div class="alert alert-warning">
                    <a class="close" data-dismiss="alert" href="#">×</a>
                    Since this doesn't support pagination yet, try to limit your search. Otherwise your browser might crash
                </div>
                <div class="input-prepend">
                    <span class="add-on"><i class="icon-key"></i></span>
                    <input type="text" placeholder="Key" name="key">
                </div>
                <button type="submit" class="btn"><i class="icon-search"></i> Search</button>
            </form>
        </div>
        <div class="tab-pane fade" id="delete">
            <form class="form-search" id="bulkdelete">
                <legend>Delete keys</legend>
                <div class="alert alert-warning">
                    <a class="close" data-dismiss="alert" href="#">×</a>
                    This uses gearman. You need to install <a href="http://www.php.net/manual/en/gearman.setup.php" target="_blank">gearman extionsion for php</a> and <a href="https://github.com/sasanrose/phpredmin#gearman-worker" target="_blank">setup gearman worker</a>
                </div>
                <div class="alert alert-danger delete_error_exists delete_error" style="display: none;">
                    <a class="close" data-dismiss="alert" href="#">×</a>
                    No keys found
                </div>
                <div class="alert alert-danger delete_error_incomplete delete_error" style="display: none;">
                    <a class="close" data-dismiss="alert" href="#">×</a>
                    There were some error in deleting all keys. More information is availalbe at cli logs
                </div>
                <div class="input-prepend">
                    <span class="add-on"><i class="icon-key"></i></span>
                    <input type="text" placeholder="Key" name="key" id="bulkkey">
                </div>
                <button type="submit" class="btn"><i class="icon-trash"></i> Delete</button>
            </form>
            <div class="progress progress-striped delete_progress" style="display: none;">
                <div class="bar delete_bar"></div>
            </div>
            <div class="delete_progress_counts" style="display:none; text-align:center;">
                <span id="count_deleted" class="delete_details"></span> / <span id="total_delete_count" class="delete_details"></span>
            </div>
            <input type="hidden" id="delete_interval" value="" />
        </div>
        <div class="tab-pane fade" id="strings">
            <?=$this->renderPartial('strings/add')?>
        </div>
        <div class="tab-pane fade" id="hashes">
            <?=$this->renderPartial('hashes/add')?>
        </div>
        <div class="tab-pane fade" id="lists">
            <?=$this->renderPartial('lists/add')?>
        </div>
        <div class="tab-pane fade" id="sets">
            <?=$this->renderPartial('sets/add')?>
        </div>
        <div class="tab-pane fade" id="sorted_sets">
            <?=$this->renderPartial('zsets/add')?>
        </div>
    </div>
</div>
