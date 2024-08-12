<!-- BEGIN: main -->
<link rel="stylesheet" href="{ASSETS_STATIC_URL}/js/select2/select2.min.css">
<script src="{ASSETS_STATIC_URL}/js/select2/select2.min.js"></script>
<script src="{ASSETS_STATIC_URL}/editors/ckeditor/ckeditor.js"></script>
<a href="{BACK}" class="btn btn-default back_em">
    <i class="fa fa-arrow-left" aria-hidden="true"> {LANG.back}</i>
</a>
<div>
    <h2 class="text-center"><strong>{LANG.list_employed_department}</strong></h2>
    <p>{LANG.total} <span class="btn btn-warning">{TOTAL}</span></p>
    <form method="post" id="form_table_employee">
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th width="1%"><input name="check_all[]" type="checkbox" value="yes" onclick="nv_checkAll(this.form, 'idcheck[]', 'check_all[]',this.checked);"> </th>
                    <th class="text-center">{LANG.stt}</th>
                    <th class="text-center"> {LANG.name_department} </th>
                    <th class="text-center"> {LANG.name_employed} </th>
                    <th class="text-center">{LANG.position}</th>
                    <th class="text-center"> {LANG.action} </th>
                </tr>
                </thead>
                <tbody>
                <!-- BEGIN: loop -->
                <tr>
                    <td>
                        <input type="checkbox" onclick="nv_UncheckAll(this.form, 'idcheck[]', 'check_all[]', this.checked);" value="{ROW.id}" name="idcheck[]" />
                    </td>
                    <td class="text-center">{ROW.stt}</td>
                    <td class="text-center">{ROW.name_department}</td>
                    <td class="text-center">{ROW.name_employed}</td>
                    <td class="text-center">{ROW.name_position}</td>
                    <td class="text-center">
                        <a href="{ROW.url_delete}"  class="btn btn-danger" onclick="return confirm('{LANG.confirm_delete}')"><i class="fa fa-trash" aria-hidden="true"></i> {LANG.delete}</a>
                    </td>
                </tr>
                <!-- END: loop -->
                </tbody>
            </table>
            <div class="form-group form-inline" >
                <select class="form-control" name="delete_all">
                    <option value="{NV_CHECK_SESSION}" >{GLANG.delete}</option>
                </select>
                <button type="button" class="btn btn-primary" onclick="nv_delete_employee_all('{ROW.id}','{NV_CHECK_SESSION}')" >{GLANG.submit}</button>
            </div>
        </div>
    </form>
</div>
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">{LANG.add_position}</h4>
            </div>
            <div class="modal-body">
                <form method="post" id="addPositionForm_employee">
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
                                    <button type="button" class="btn btn-primary" id="submit_position_employee">{LANG.save}</button>
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
<form method="post">
    <div class="table-responsive">
        <table class="table table-hover table-striped table-bordered">
            <tbody>
            <caption><strong><em class="fa fa-file-text-o"></em> {LANG.employed_manager} </strong></caption>
            <tr>
                <td>{LANG.department}</td>
                <td>
                    <select class="form-control" name="id_department" >
                        <!-- BEGIN: department -->
                        <option value="{DEPARTMENT.id}" {DEPARTMENT.selected}>{DEPARTMENT.name_department}</option>
                        <!-- END: department -->
                    </select>
                </td>
            </tr>
            <tr>
                <td>{LANG.employed}</td>
                <td>
                    <select class="form-control employed sl2" name="id_employed[]" id="id_employed" data_placeholder="{LANG.all}" multiple="multiple" >
                        <option value="-1" disabled>{LANG.all}</option>
                        <!-- BEGIN: user -->
                        <option value="{USER.userid}" {USER.selected}>{USER.first_name} {USER.last_name}</option>
                        <!-- END: user   -->
                    </select>
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

            </tbody>

            <tfoot>
            <tr>
                <td colspan="3" class="text-center"><button type="submit" class="btn btn-primary" name="submit">{LANG.save}</button></td>
            </tr>
            </tfoot>
        </table>
    </div>
</form>
<script>
    $(document).ready(function() {
        $('#submit_position_employee').click(function() {
            var $form = $('#addPositionForm_employee');
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
<script>
    $(document).ready(function() {
        $('.sl2').select2();
    });
</script>
<!-- END: main -->
