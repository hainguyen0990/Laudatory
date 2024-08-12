<!-- BEGIN: main -->
<script src="{ASSETS_STATIC_URL}/editors/ckeditor5-classic/ckeditor.js"></script>
<link rel="stylesheet" href="{ASSETS_STATIC_URL}/js/select2/select2.min.css">
<script src="{ASSETS_STATIC_URL}/js/select2/select2.min.js"></script>
<!-- BEGIN: error -->
<div class="alert alert-danger">
    {ERROR}
</div>
<!-- END: error -->
<!-- BEGIN: search -->
<div class="well row">
    <form method="get" action="{URL}" >
        <input type="hidden" name="{NV_LANG_VARIABLE}" value="{NV_LANG_DATA}"/>
        <input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}"/>
        <input type="hidden" name="{NV_OP_VARIABLE}" value="{OP}"/>
        <!-- BEGIN: select_admin -->
        <div class="col-xs-12 col-md-8">
            <select class="form-control" name="ceo">
                <option value="-1">{LANG.all}</option>
                <!-- BEGIN: loop -->
                <option value="{ADMIN.userid}" data-email="{ADMIN.email}" data-sdt="{ADMIN.phone}" {ADMIN.selected}>
                    {ADMIN.first_name} {ADMIN.last_name}
                </option>
                <!-- END: loop -->
            </select>
        </div>
        <!-- END: select_admin -->
        <div class="col-xs-12 col-md-8">
            <div class="form-group">
                <input type="text" name="q" class="form-control" placeholder="{LANG.name_department}" value="{SEARCH.q}">
            </div>
        </div>
        <div class="col-xs-12 col-md-24">
            <button name="search" type="submit" class="btn btn-primary"><i class="fa fa-search" aria-hidden="true"></i> {LANG.search}</button>
            <a href="{URL}&request=1" class="btn btn-warning"><i class="fa fa-plus-circle" aria-hidden="true"></i> {LANG.add_department}</a>
            <a href="{URL_POSITION}" class="btn btn__pue btn_position"><i class="fa fa-plus-circle" aria-hidden="true"></i> {LANG.add_position}</a>
        </div>
    </form>
</div>
<!-- END: search -->
<form method="post" id="form_table" >
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead>
            <tr>
                <th class="text-center"><input name="check_all[]" type="checkbox" value="yes" onclick="nv_checkAll(this.form, 'idcheck[]', 'check_all[]',this.checked);"> </th>
                <th class="text-center">{LANG.stt}</th>
                <th class="text-center">{LANG.name_department}</th>
                <th class="text-center">{LANG.department_head}</th>
                <th class="text-center">{LANG.position}</th>
                <th class="text-center">{LANG.phone}</th>
                <th class="text-center">{LANG.email}</th>
                <th class="text-center">{LANG.description}</th>
                <th class="text-center">{LANG.total_employee}</th>
                <th class="text-center">{LANG.action}</th>
            </tr>
            </thead>
            <tbody>
            <!-- BEGIN: loop -->
            <tr>
                <td class="text-center">
                    <input type="checkbox" onclick="nv_UncheckAll(this.form, 'idcheck[]', 'check_all[]', this.checked);" value="{ROW.id}" name="idcheck[]" />
                </td>
                <td class="text-center">{ROW.stt}</td>
                <td class="text-center">{ROW.name_department}</td>
                <td class="text-center">{ROW.name_department_head}</td>
                <th class="text-center">{ROW.position}</th>
                <td class="text-center">{ROW.phone}</td>
                <td class="text-center">{ROW.email}</td>
                <td class="text-center">{ROW.description}</td>
                <th class="text-center "> <a href="{ROW.url_employed_department}" class="btn color-blue bg-primary">{ROW.total_employee}</a></th>
                <td class="text-center text-nowrap">
                    <a href="{ROW.url_employed_department}" class="btn btn-warning  "><i class="fa fa-plus-circle" aria-hidden="true"></i> {LANG.add_employed}</a>
                    <a href="{ROW.url_edit}" class="btn btn-primary "> <i class="fa fa-pencil-square-o" aria-hidden="true" ></i> {LANG.edit}</a>
                    <a href="{ROW.url_delete}" class="btn btn-danger btn_sm" onclick="return confirm('{LANG.confirm_delete}')">
                        <i class="fa fa-trash" aria-hidden="true"></i> {LANG.delete}
                    </a>
                </td>
            </tr>
            <!-- END: loop -->
            </tbody>
        </table>
        <div class="form-group form-inline" >
            <select class="form-control" name="delete_all">
                <option value="{NV_CHECK_SESSION}" >{GLANG.delete}</option>
            </select>
            <button type="button" class="btn btn-primary" onclick="nv_delete_department_all('{ROW.id}','{NV_CHECK_SESSION}')" >Thực hiện</button>
        </div>
    </div>
</form>
<!-- BEGIN: generate_page -->
<div class="text-center">
    {GENERATE_PAGE}
</div>
<!-- END: generate_page -->
<!-- BEGIN: request -->
<form method="post">
    <div class="table-responsive">
        <table class="table table-hover table-striped table-bordered">
            <caption><strong><em class="fa fa-file-text-o"></em>{LANG.add_department}</strong></caption>
            <tbody>
            <tr>
                <td>
                    <label>{LANG.name_department} <span class="fa-required text-danger">(<em class="fa fa-asterisk"></em>)</span>:</label>
                </td>
                <td>
                    <input class="form-control" type="text" name="name_department" value="{DATA.name_department}">
                </td>
            </tr>
            <tr>
                <td>
                    <label>{LANG.department_header} <span class="fa-required text-danger">(<em class="fa fa-asterisk"></em>)</span>:</label>
                </td>
                <td>
                    <!-- BEGIN: select_admin -->
                    <select class="form-control department_header sl2" name="id_department_head[]" id="id_department_head" data_placeholder="{LANG.all}" multiple="multiple">
                        <option value="-1" disabled>{LANG.all}</option>
                        <!-- BEGIN: loop -->
                        <option value="{ADMIN.userid}" data-email="{ADMIN.email}" data-sdt="{ADMIN.phone}" {ADMIN.selected}>
                            {ADMIN.first_name} {ADMIN.last_name}
                        </option>
                        <!-- END: loop -->
                    </select>
                    <!-- END: select_admin -->
                </td>
            </tr>
            <tr>
                <td>{LANG.position}</td>
                <td>
                    <!-- BEGIN: select_position -->
                    <div class="row">
                        <div class="col-md-12">
                            <select  name="id_position" class="form-control sl2" >
                                <option value="-1" >{LANG.all}</option>
                                <!-- BEGIN: loop -->
                                <option value="{POSITION.id}" {POSITION.selected}>{POSITION.name_position}</option>
                                <!-- END: loop -->
                            </select>
                        </div>
                        <div class="col-md-12">
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
                                <i class="fa fa-plus" aria-hidden="true"></i> {LANG.add_position}
                            </button>
                        </div>
                    </div>
                    <!-- END: select_position -->
                </td>
            </tr>
            <tr>
                <td>
                    <label>{LANG.phone} <span class="fa-required text-danger">(<em class="fa fa-asterisk"></em>)</span>:</label>
                </td>
                <td><input class="form-control" type="number" name="phone" value="{DATA.phone}" id="phone"></td>
            </tr>
            <tr>
                <td>
                    <label>{LANG.email} <span class="fa-required text-danger">(<em class="fa fa-asterisk"></em>)</span>:</label>
                </td>
                <td><input class="form-control" type="email" name="email" value="{DATA.email}" id="email"></td>
            </tr>
            <tr>
                <td>{LANG.description}</td>
                <td><textarea class="form-control" name="description" id="description">{DATA.description}</textarea></td>
            </tr>
            </tbody>
            <tfoot>
            <tr>
                <td colspan="2" class="text-center">
                    <a href="{URL_BACK}" class="btn btn-default">
                        <i class="fa fa-arrow-left" aria-hidden="true"> {LANG.back}</i>
                    </a>
                    <button type="submit" class="btn btn-primary" name="submit">{LANG.save}</button>
                </td>
            </tr>
            </tfoot>
        </table>
    </div>
</form>
<!-- END: request -->
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">{LANG.add_position}</h4>
            </div>
            <div class="modal-body">
                <form method="post" id="addPositionForm">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped table-bordered">
                            <tbody>
                                <caption><strong><em class="fa fa-file-text-o"></em> {LANG.position_manager} </strong></caption>
                                <tr>
                                    <td>{LANG.name_position}<span class="fa-required text-danger">(<em class="fa fa-asterisk"></em>)</span></td><td>
                                        <input type="text" class="form-control" name="name_position" >
                                    </td>
                                </tr>
                                <tr>
                                    <td>{LANG.description_position}</td>
                                    <td><textarea class="form-control" id="description_position" name="description_position" rows="5"></textarea></td>
                                </tr>
                            </tbody>
                            <tfoot>
                            <tr>
                                <td colspan="3" class="text-center">
                                    <input type="hidden" name="add_position" value="{NV_CHECK_SESSION}">
                                    <button type="button" class="btn btn-primary" id="submit_position">{LANG.save}</button>
                                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times text-danger"></i> {LANG.close}</button>
                                </td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    // CKEDITOR.replace('description');
    $(document).ready(function() {
        $('#id_department_head').change(function() {
            var selectedOption = $(this).find('option:selected');
            var email = selectedOption.data('email');
            var sdt = selectedOption.data('sdt');
            if (email) {
                $('#email').val(email);
            } else {
                $('#email').val('');
            }
            if (sdt) {
                $('#phone').val(sdt);
            } else {
                $('#phone').val('');
            }
        });
        $('.sl2').select2({
            placeholder: "{LANG.all}",
            allowClear: true,
        });

        $('#submit_position').click(function() {
            var $form = $('#addPositionForm');
            $.ajax({
                type: 'POST',
                url: location.href,
                data: $form.serialize(),
            }).done(function (data) {
                if (data['res'] == 'success') {
                    $('#myModal').modal('hide');
                    var newid_position = data['position']['id'];
                    var newname_position = data['position']['name_position'];
                    var selected = $('select[name="id_position"]');
                    var newOption = $('<option>', {
                        value: newid_position,
                        text: newname_position,
                    });
                    selected.append(newOption);
                    selected.val(newid_position).trigger('change');
                } else {
                    alert(data['mess']);
                }
            });
        });
    });
</script>
<!-- END: main -->
