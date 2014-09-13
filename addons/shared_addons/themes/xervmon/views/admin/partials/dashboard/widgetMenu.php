<div id="customWidgetMenu" class="modal container hide fade">
    <div class="modal-header" style="background: #0D3144;color: white;">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
            &times;
        </button>
        <h4>
            <?php echo Asset::img('logo.png','Xervmon'); ?>&nbsp;
            Custom Widgets you can choose:
        </h4>
    </div>
    <div class="modal-body">
        <ul class="" id="newWidgetHolder"></ul>
    </div>
    <div class="modal-footer">
        <button class="btn btn-success btnAccept" data-dismiss="modal" aria-hidden="true" id="customWidgetMenu_add">Add Selected Widgets</button>
        <button class="btn btn-cancel" data-dismiss="modal" aria-hidden="true">Close</button>
    </div>
</div>
