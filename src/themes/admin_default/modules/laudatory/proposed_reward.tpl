<!-- BEGIN: main -->
<link rel="stylesheet" href="{ASSETS_STATIC_URL}/js/select2/select2.min.css">
<script src="{ASSETS_STATIC_URL}/js/select2/select2.min.js"></script>
<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title text-center">{LANG.thong_tin_khen_thuong}</h3>
    </div>
    <div class="panel-body">
        <h1 class="text-center text-danger">{LANG.tieu_de_khen_thuong}: {CATEGORY.description}</h1>
        <h1 class="text-center text-danger">{LANG.department}: {NAME_DEPART.name_department}</h1>
        <p class="text-center text-danger">{LANG.time}: {TIME_AWARD}</p>
    </div>
</div>
<!-- BEGIN: add_btn -->
<div class="form-group">
    <a href="#" data-toggle="add" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> {LANG.add_category}</a>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $('[data-toggle="add"]').on('click', function(e) {
            e.preventDefault();
            $('html, body').animate({
                scrollTop: $('#form-holder').offset().top
            }, 200, function() {
                $('[name="time_awards"]').focus();
            });
        });
    });
</script>
<!-- END: add_btn -->
<form method="post" id="form_table_delete_proposed">
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <colgroup>
                <col class="w100">
            </colgroup>
            <thead>
            <tr>
                <th width="1%"><input name="check_all[]" type="checkbox" value="yes" onclick="nv_checkAll(this.form, 'idcheck[]', 'check_all[]', this.checked);"> </th>
                <th style="width: 1%" class="text-nowrap text-center">{LANG.stt}</th>
                <th style="width: 10%" class="text-nowrap text-center">{LANG.category_laudatory}</th>
                <th style="width: 10%" class="text-nowrap text-center">{LANG.department}</th>
                <th style="width: 10%" class="text-center text-nowrap">{LANG.employed}</th>
                <th style="width: 10%" class="text-center text-nowrap">{LANG.reason}</th>
                <th style="width: 10%" class="text-center text-nowrap">{LANG.time_proposed}</th>
                <th style="width: 10%" class="text-center text-nowrap">{LANG.action}</th>
            </tr>
            </thead>
            <tbody>
            <!-- BEGIN: loop -->
            <tr>
                <td>
                    <input type="checkbox" onclick="nv_UncheckAll(this.form, 'idcheck[]', 'check_all[]', this.checked);" value="{ROW.id_pose}" name="idcheck[]">
                </td>
                <td class="text-center">
                    {ROW.stt}
                </td>
                <td class="text-center">
                    {ROW.id_category_name}
                </td>
                <td class="text-center">
                    {ROW.departments}
                </td>
                <td class="text-center">
                    <div>{ROW.id_employed_name}</div>
                </td>
                <td class="text-center"> {ROW.reason}</td>
                <td class="text-center text-nowrap">{ROW.time_proposed}</td>
                <td class="text-center text-nowrap">
                    <div class="text-center">{ROW.success}</div>
                    <!-- BEGIN: enable -->
                    <a class="btn btn-primary" href="{ROW.url_edit}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> {GLANG.edit}</a>
                    <a href="{ROW.url_delete}" class="btn btn-danger" onclick="return confirm('{LANG.confirm_delete}')"> <i class="fa fa-trash" aria-hidden="true"></i>{GLANG.delete}</a>
                    <!-- END: enable -->
                </td>
            </tr>
            <!-- END: loop -->
            </tbody>
        </table>
        <div class="form-group form-inline ">
            <select class="form-control" name="delete_all">
                <option value="{NV_CHECK_SESSION}">{GLANG.delete}</option>
            </select>
            <button type="button" class="btn btn-primary" onclick="nv_delete_proposed_all('{ROW.id}','{NV_CHECK_SESSION}')">{LANG.save}</button>
            <a href="{URL_BACK}" class="btn btn-default center_back">
                <i class="fa fa-arrow-left" aria-hidden="true"> {LANG.back}</i>
            </a>
            <a class="btn btn-warning center" href="{URL_APPOVE}">{LANG.approve_proposal}</a>
        </div>
    </div>
</form>
<div id="form-holder"></div>
<!-- BEGIN: error -->
<div class="alert alert-danger">{ERROR}</div>
<!-- END: error -->
<h2><i class="fa fa-th-large" aria-hidden="true"></i> {CAPTION}</h2>
<div class="panel panel-default">
    <div class="panel-body">
        <form method="post" action="{BASE_URL}" class="form-horizontal">
            <div class="form-group">
                <label class="col-sm-6 control-label" for="element_description"> {LANG.category_laudatory}: </label>
                <div class="col-sm-18 col-lg-10">
                    <select class="form-control" name="id_category">
                        <!-- BEGIN: category -->
                        <option value="{CATEGORY.id}" {CATEGORY.selected}>{CATEGORY.time_awards} - {CATEGORY.description}</option>
                        <!-- END: category -->
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-6 control-label"> {LANG.employed} <span class="fa-required text-danger">(<em class="fa fa-asterisk"></em>)</span>:</label>
                <div class="d-flex col-md-12 align-items-center">
                    <div class="col-md-14 p-0">
                        <select class="form-control employed" name="id_employed[]" id="id_employed" data_placeholder="{LANG.all}" multiple="multiple">
                            <option value="-1" disabled>{LANG.choose_employed}</option>
                            <!-- BEGIN: employed -->
                            <option value="{EMPLOYED.userid}" {EMPLOYED.selected}>{EMPLOYED.first_name} {EMPLOYED.last_name} - {EMPLOYED.name_department}</option>
                            <!-- END: employed -->
                        </select>
                    </div>
                    <div class="col-md-8" >
                        <a href="{URL_EMPLOYEE}" class="btn btn-primary ml-2" >
                            <i class="fa fa-plus" aria-hidden="true"></i> {LANG.add_employed}
                        </a>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-6 control-label" for="reason"> {LANG.reason} <span class="fa-required text-danger">(<em class="fa fa-asterisk"></em>)</span>:</label>
                <div class="col-sm-18 col-lg-10">
                    <textarea class="form-control" rows="3" id="reason" name="reason">{DATA.reason}</textarea>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-18 col-sm-offset-6">
                    <input type="hidden" name="save" value="{NV_CHECK_SESSION}">
                    <button type="submit" name="submit" class="btn btn-primary" href="{URL}&request=1">{GLANG.submit}</button>
                </div>
            </div>
        </form>
    </div>
</div>
<link type="text/css" href="{ASSETS_STATIC_URL}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" />
<script type="text/javascript" src="{ASSETS_STATIC_URL}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{ASSETS_LANG_STATIC_URL}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>
<script type="text/javascript">
    $(document).ready( function() {
        $('.uidatepicker').datepicker({
            showOn : "both",
            dateFormat : "mm/dd/yy",
            changeMonth : true,
            changeYear : true,
            showOtherMonths : true,
            buttonImage : nv_base_siteurl + "assets/images/calendar.gif",
            buttonImageOnly : true
        });
        $('.employed').select2({
            placeholder: "{LANG.all}",
            allowClear: true,
            width: '100%'
        });
    });
</script>
