<?php if ($this->saved) { ?>
    <div class="alert alert-info">
        <a class="close" data-dismiss="alert" href="#">×</a>
        DB saved to file <?=$this->filename?>
    </div>
<?php } else { ?>
    <div class="alert alert-danger">
        <a class="close" data-dismiss="alert" href="#">×</a>
        There was a problem saving database
    </div>
<?php } ?>
