<!-- BEGIN: main -->
<link type="text/css" href="{ASSETS_STATIC_URL}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" />
<script type="text/javascript" src="{ASSETS_STATIC_URL}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{ASSETS_STATIC_URL}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>
<script type="text/javascript" src="{ASSETS_STATIC_URL}/js/clipboard/clipboard.min.js"></script>
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
<p>{LANG.luu_y_phong_ban}</p>
<form method="post" id="form_category">
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <colgroup>
                <col class="w100">
            </colgroup>
            <thead>
                <tr>
                    <th style="width:1%" class="text-center"><input name="check_all[]" type="checkbox" value="yes" id = "check_id" onclick="nv_checkAll(this.form, 'idcheck[]', 'check_all[]',this.checked);"> </th>
                    <th style="width: 2%" class="text-nowrap text-center">{LANG.stt}</th>
                    <th style="width: 10%" class="text-nowrap text-center">{LANG.time_awards}</th>
                    <th style="width: 10%" class="text-center text-nowrap">{LANG.description_laudatory}</th>
                    <th style="width: 10%" class="text-center text-nowrap">{LANG.total_proposed}</th>
                    <th style="width: 10%" class="text-center text-nowrap">{LANG.total_department}</th>
                    <th style="width: 10%" class="text-center text-nowrap">{LANG.action}</th>
                </tr>
            </thead>
            <tbody>
                <!-- BEGIN: loop -->
                <tr>
                    <td class="text-center" width="1%">
                        <input type="checkbox" onclick="nv_UncheckAll(this.form, 'idcheck[]', 'check_all[]', this.checked);" value="{ROW.id}" name="idcheck[]">
                    </td>
                    <td class="text-center">
                        <a href="javascript:void(0);" title="{LANG.order_weight_number}: {ROW.weight}" class="btn btn-default btn-block" onclick="nv_sort_weight({ROW.id}, {ROW.weight})">{ROW.weight}</a>
                    </td>
                    <td class="text-center">
                        <strong>{ROW.time_awards}</strong>
                    </td>
                    <td class="text-center">
                        <div>{ROW.description}</div>
                    </td>
                    <td class="text-center "> <a href="{ROW.url_total_proposed}" class="btn color-blue bg-primary">{ROW.total_proposed}</a></td>
                    <td class="text-center "> <a href="{ROW.url_department}" class="btn color-blue bg-primary">{ROW.total_department}</a></td>
                    <td class="text-center text-nowrap">
                        <a class="btn btn-sm btn-warning btn-sm btn-custom" href="{ROW.url_propose}"><i class="fa fa-plus-circle" aria-hidden="true"></i> {LANG.add_proposed}</a>
                        <a class="btn btn-sm btn-info btn-sm btn-custom" href="{ROW.url_department}"><i class="fa fa-eye" aria-hidden="true"></i> {LANG.add_department}</a>
                        <a class="btn btn-sm btn-primary btn-sm btn-custom" href="{ROW.url_edit}"><i class="fa fa-pencil-square-o" aria-hidden="true" ></i> {LANG.edit}</a>
                        <a class="btn btn-sm btn-danger btn-sm btn-custom" href="javascript:void(0);" onclick="nv_delele_cats('{ROW.id}', '{NV_CHECK_SESSION}');"><i class="fa fa-trash" aria-hidden="true"></i> {LANG.delete}</a>
                    </td>
                </tr>
            <!-- END: loop -->
            </tbody>
        </table>
        <div class="form-group form-inline" >
            <select class="form-control" name="delete_all">
                <option value="{NV_CHECK_SESSION}" >{GLANG.delete}</option>
            </select>
            <button type="button" class="btn btn-primary" id="btn_delete_cate" onclick="nv_delete_cat_all('{ROW.id}','{NV_CHECK_SESSION}')" >{LANG.action}</button>
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
                <label class="col-sm-6 control-label" for="element_time_awards">{LANG.time_awards}  <span class="fa-required text-danger">(<em class="fa fa-asterisk"></em>)</span>:</label>
                <div class="col-sm-18 col-lg-10">
                    <input class="form-control w350 uidatepicker" type="text" name="time_awards" value="{DATA.time_awards}" maxlength="10" autocomplete="off" readonly="readonly">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-6 control-label" for="element_description"> {LANG.description_laudatory}<span class="fa-required text-danger">(<em class="fa fa-asterisk"></em>)</span>:</label>
                <div class="col-sm-18 col-lg-10">
                    <textarea class="form-control" rows="3" id="element_description" name="description">{DATA.description}</textarea>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-18 col-sm-offset-6">
                    <input type="hidden" name="save" value="{NV_CHECK_SESSION}">
                    <button type="submit" name="submit" class="btn btn-primary">{GLANG.submit}</button>
                </div>
            </div>
        </form>
    </div>
</div>
<div id="order_weight" title="{LANG.order_weight}">
    <strong id="order_weight_title">{LANG.order_weight}</strong>
    <form method="post" class="form-horizontal" id="form_change_weight">
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
            <button type="button" class="btn btn-primary" onclick="nv_change_weight_cat('{ROW.id}','{NV_CHECK_SESSION}')">{LANG.action}</button>
        </div>
    </form>
</div>
<!-- BEGIN: scroll -->
<script type="text/javascript">
    $(window).on('load', function() {
        $('html, body').animate({
            scrollTop: $('#form-holder').offset().top
        }, 200, function() {
            $('[name="title"]').focus();
        });
    });
</script>
<!-- END: scroll -->
<!-- BEGIN: generate_page -->
<div class="text-center">
    {GENERATE_PAGE}
</div>
<!-- END: generate_page -->
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
    });
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
    });
</script>
<!-- END: main -->
