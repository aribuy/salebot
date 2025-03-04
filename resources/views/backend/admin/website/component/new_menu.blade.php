<div class="modal" id="clone_menu">
    <li class="dd-item dd3-item menu-item" data-id="2">
        <input type="hidden" name="menu_lenght[]" id="menu_lenght" value="1">
        <div class="dd-handle dd3-handle"></div>
        <div class="dd3-content sortable-section mb-4">
            <ul class="sortable-menu-icon">
                <li class="menuMove">
                </li>
                <li>
                    <a href="javascript:void(0);" onclick="$(this).closest('.dd-item').remove()" class="delete-icon"><i
                            class="las la-trash-alt"></i></a>
                </li>
            </ul>

            <div class="row gx-20 align-items-center">
                <div class="col-lg-3">
                    <input type="text" name="label[]" class="form-control rounded-2"
                        placeholder="Label" required>
                </div>
                <div class="col-lg-9">
                    <div class="d-flex align-items-center gap-4">
                        <input type="text" name="url[]" class="form-control rounded-2"
                            placeholder="Link" required>
                    </div>
                </div>
            </div>
        </div>
    </li>
</div>