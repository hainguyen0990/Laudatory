<!-- BEGIN: main -->
<script src="{ASSETS_STATIC_URL}/editors/ckeditor/ckeditor.js"></script>
<link type="text/css" href="{ASSETS_STATIC_URL}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" />
<script type="text/javascript" src="{ASSETS_STATIC_URL}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{ASSETS_STATIC_URL}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>
<script type="text/javascript" src="{ASSETS_STATIC_URL}/js/clipboard/clipboard.min.js"></script>
<!-- BEGIN: error -->
<div class="alert alert-danger">{ERROR}</div>
<!-- END: error -->
<div>
    <a href="#" data-toggle="add" class="btn btn-success btn-sm" id="scrollToPosition"><i class="fa fa-plus"></i> {LANG.add_position}</a>
    <p style="margin-top: 10px">{LANG.total} <span class="btn btn-warning">{TOTAL}</span></p>
    <form id="form_position">
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th class="text-center"><input name="check_all[]" type="checkbox" value="yes" onclick="nv_checkAll(this.form, 'idcheck[]', 'check_all[]',this.checked);"></th>
                    <th class="text-center">{LANG.stt}</th>
                    <th class="text-center">{LANG.name_position}</th>
                    <th class="text-center">{LANG.description_position}</th>
                    <th class="text-center">{LANG.action}</th>
                </tr>
                </thead>
                <tbody>
                <!-- BEGIN: loop -->
                <tr>
                    <td class="text-center">
                        <input type="checkbox" onclick="nv_UncheckAll(this.form, 'idcheck[]', 'check_all[]', this.checked);" value="{ROW.id}" name="idcheck[]">
                    </td>
                    <td class="text-center">
                        <a href="javascript:void(0);" title="{LANG.order_weight_number}: {ROW.weight}" class="btn btn-default btn-block" onclick="nv_sort_weight({ROW.id}, {ROW.weight})">{ROW.weight}</a>
                    </td>
                    <td class="text-center">{ROW.name_position}</td>
                    <td class="text-center">{ROW.description}</td>
                    <td class="text-center">
                        <a href="{ROW.url_edit}" class="btn btn-primary">{LANG.edit}</a>
                        <a href="{ROW.url_delete}" class="btn btn-danger" onclick="return confirm('{LANG.confirm_delete}')">{LANG.delete}</a>
                    </td>
                </tr>
                <!-- END: loop -->
                </tbody>
            </table>
            <div class="form-group form-inline">
                <select class="form-control" name="delete_all1">
                    <option value="{NV_CHECK_SESSION}">{GLANG.delete}</option>
                </select>
                <button type="button" class="btn btn-primary" onclick="nv_delete_position_all('{ROW.id}','{NV_CHECK_SESSION}')" >{LANG.action}</button>
            </div>
        </div>
    </form>
</div>
<div id="order_weight" title="{LANG.order_weight}">
    <strong id="order_weight_title">{LANG.order_weight}</strong>
    <form method="post" class="form-horizontal" id="form_change_weight_position">
        <input type="hidden" name="order_weight_id" value="0" id="order_weight_id"/>
        <div class="form-group">
            <label for="order_weight_number" class="col-sm-12 control-label">{LANG.order_weight_number}</label>
            <div class="col-sm-12">
                <input type="number" class="form-control text-center w100" id="order_weight_number"  value="" readonly="readonly">
            </div>
        </div>
        <div class="form-group">
            <label for="order_weight_new" class="col-sm-12 control-label">{LANG.order_weight_new}</label>
            <div class="col-sm-12">
                <input type="number" class="form-control text-center w100" name="order_weight_new" id="order_weight_new"  value="" min="1">
            </div>
        </div>
        <div class="form-group text-center">
            <input type="hidden" name="changeweight" value="{NV_CHECK_SESSION}" />
            <button type="button" class="btn btn-primary" onclick="nv_change_weight_position('{ROW.id}','{NV_CHECK_SESSION}')">{LANG.action}</button>
        </div>
    </form>
</div>
<form method="post" id="add_position">
    <div class="table-responsive">
        <table class="table table-hover table-striped table-bordered">
            <tbody>
            <tr>
                <td>
                    {LANG.name_position}
                    <span class="fa-required text-danger">
                        (<em class="fa fa-asterisk"></em>)
                    </span>
                </td>
                <td>
                    <input type="text" class="form-control" name="name_position" value="{DATA.name_position}" id="name_position_field">
                </td>
            </tr>
            <tr>
                <td>{LANG.description_position}</td>
                <td>
                    <textarea class="form-control" id="description" name="description" rows="5">{DATA.description}</textarea>
                </td>
            </tr>
            </tbody>
            <tfoot>
            <tr>
                <td colspan="3" class="text-center">
                    <a href="{URL_BACK}" class="btn btn-default"><i class="fa fa-arrow-circle-left"></i> {LANG.back}</a>
                    <button type="submit" class="btn btn-primary" name="submit">{LANG.save}</button>
                </td>
            </tr>
            </tfoot>
        </table>
    </div>
</form>
<script type="text/javascript">
    $(function() {
        $( "#order_weight" ).dialog({
            autoOpen: false,
            show: {
                effect: "blind",
                duration: 500
            },
            hide: {
                effect: "explode",
                duration: 500
            }
        });
        $('#scrollToPosition').click(function(e) {
            e.preventDefault();
            $('html, body').animate({
                scrollTop: $('#name_position_field').offset().top
            }, 500);
            $('#name_position_field').focus();
        });
    });
</script>
<script>
    CKEDITOR.replace('description');
</script>
<!-- BEGIN: generate_page -->
<div class="text-center">
    {GENERATE_PAGE}
</div>
<!-- END: generate_page -->
<!-- END: main -->
