<?php $this->addHeader("<script src=\"{$this->router->baseUrl}/js/redmin/actions.js\" type=\"text/javascript\"></script>"); ?>
<?=$this->renderPartial('generalmodals')?>
<div id="move_confirmation" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="confirmation" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3></h3>
    </div>
    <div class="modal-body">
        <input type="text" placeholder="" name="destination">
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
        <button class="btn btn-primary movekey">Move</button>
    </div>
</div>
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
