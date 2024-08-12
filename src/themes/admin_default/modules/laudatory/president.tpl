<!-- BEGIN: main -->
<a href="{BACK}" class="btn btn-default ">
    <i class="fa fa-arrow-left" aria-hidden="true"> {LANG.back}</i>
</a>
<div class="panel panel-primary mt-3">
    <div class="panel-heading">
        <h3 class="panel-title text-center">{LANG.info_department}</h3>
    </div>
    <div class="panel-body">
        <h1 class="text-center text-danger">{LANG.category}: {CATEGORY.description}</h1>
        <h1 class="text-center text-danger">{LANG.department}: {DEPART.name_department}</h1>
        <p class="text-center text-danger">{LANG.time} : {TIME_AWARD}</p>
    </div>
</div>
<h1 class="text-center mt-5">{LANG.danh_sach_de_xuat}</h1>
<ul class="nav nav-tabs" id="myTab" role="tablist">
    <!-- BEGIN: admin -->
    <li class="nav-item" role="presentation">
        <a class="nav-link active" id="proposed-tab" data-toggle="tab" href="#proposed" role="tab" aria-controls="proposed" aria-selected="true">{LANG.list_proposed_rewards}</a>
    </li>
    <!-- END: admin -->
    <li class="nav-item" role="presentation">
        <a class="nav-link" id="approved-tab" data-toggle="tab" href="#approved" role="tab" aria-controls="approved" aria-selected="false">{LANG.list_proposed_approved}</a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link" id="rejected-tab" data-toggle="tab" href="#rejected" role="tab" aria-controls="rejected" aria-selected="false">{LANG.list_proposed_rejected}</a>
    </li>
</ul>
<div class="tab-content" id="myTabContent">
    <div class="tab-pane fade active in" id="proposed" role="tabpanel" aria-labelledby="proposed-tab">
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="bg-primary">
                <tr>
                    <th class="text-center">{LANG.stt}</th>
                    <th class="text-center">{LANG.category_laudatory}</th>
                    <th class="text-center">{LANG.employed}</th>
                    <th class="text-center">{LANG.name_proposer}</th>
                    <th class="text-center">{LANG.reason}</th>
                    <th class="text-center">{LANG.time_proposed}</th>
                    <th class="text-center">{LANG.status}</th>
                </tr>
                </thead>
                <tbody>
                <!-- BEGIN: loop_proposed -->
                <tr>
                    <th class="text-center">{ROW.stt}</th>
                    <th class="text-center">{ROW.category_laudatory}</th>
                    <th class="text-center">{ROW.employed}</th>
                    <th class="text-center">{ROW.name_proposer}</th>
                    <th class="text-center">{ROW.reason}</th>
                    <th class="text-center">{ROW.time_proposed}</th>
                    <th class="text-center">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal"
                                data-id="{ROW.id_pose}"
                                data-category="{ROW.category_laudatory}"
                                data-employee="{ROW.employed}"
                                data-proposer="{ROW.name_proposer}"
                                data-reason="{ROW.reason}"
                                data-time="{ROW.time_proposed}"
                                onclick="set_data(this)">{LANG.action}
                        </button>
                    </th>
                </tr>
                <!-- END: loop_proposed -->
                </tbody>
            </table>
        </div>
    </div>
    <div class="tab-pane fade" id="approved" role="tabpanel" aria-labelledby="approved-tab">
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="bg-primary">
                <tr>
                    <th class="text-center">{LANG.stt}</th>
                    <th class="text-center">{LANG.category_laudatory}</th>
                    <th class="text-center">{LANG.employed}</th>
                    <th class="text-center">{LANG.name_proposer}</th>
                    <th class="text-center">{LANG.reason_approved}</th>
                    <th class="text-center">{LANG.bonus}</th>
                    <th class="text-center">{LANG.time_approve_proposed}</th>
                    <!-- BEGIN: admin_status -->
                    <th class="text-center">{LANG.status}</th>
                    <!-- END: admin_status -->
                    <th class="text-center">{LANG.view_detail}</th>
                </tr>
                </thead>
                <tbody>
                <!-- BEGIN: loop_approved -->
                <tr>
                    <td class="text-center">{ROW.stt}</td>
                    <td class="text-center">{ROW.category_laudatory}</td>
                    <td class="text-center">{ROW.employed}</td>
                    <td class="text-center">{ROW.name_proposer}</td>
                    <td class="text-center">{ROW.reason_approved}</td>
                    <td class="text-center">{ROW.bonus}</td>
                    <td class="text-center">{ROW.time_approved}</td>
                    <!-- BEGIN: admin -->
                    <td>
                        <select class="form-control" name="status" id="status_{ROW.id_pose}" onchange="nv_change_status_propose({ROW.id_pose}, this.value)">
                            <!-- BEGIN: status_proposed -->
                            <option value="{STATUS.key}" {STATUS.selected}>{STATUS.value}</option>
                            <!-- END: status_proposed -->
                        </select>
                    </td>
                    <!-- END: admin -->
                    <td class="text-center">
                        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#detailModal"
                                data-id="{ROW.id_pose}"
                                data-category="{ROW.category_laudatory}"
                                data-employee="{ROW.employed}"
                                data-proposer="{ROW.name_proposer}"
                                data-reason="{ROW.reason}"
                                data-time="{ROW.time_proposed}"
                                data-reason-approved="{ROW.reason_approved}"
                                data-time-approved="{ROW.time_approved}"
                                data-bonus="{ROW.bonus}"
                                data-image="{ROW.image}"
                                onclick="showDetails(this)">{LANG.view_detail}
                        </button>
                    </td>
                </tr>
                <!-- END: loop_approved -->
                </tbody>
            </table>
        </div>
    </div>
    <div class="tab-pane fade" id="rejected" role="tabpanel" aria-labelledby="rejected-tab">
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="bg-primary">
                <tr>
                    <th class="text-center">{LANG.stt}</th>
                    <th class="text-center">{LANG.category_laudatory}</th>
                    <th class="text-center">{LANG.employed}</th>
                    <th class="text-center">{LANG.name_proposer}</th>
                    <th class="text-center">{LANG.reason_reject}</th>
                    <th class="text-center">{LANG.time_reject}</th>
                    <th class="text-center">{LANG.status}</th>
                </tr>
                </thead>
                <tbody>
                <!-- BEGIN: loop_rejected -->
                <tr>
                    <th class="text-center">{ROW.stt}</th>
                    <th class="text-center">{ROW.category_laudatory}</th>
                    <th class="text-center">{ROW.employed}</th>
                    <th class="text-center">{ROW.name_proposer}</th>
                    <th class="text-center">{ROW.reason_approved}</th>
                    <th class="text-center">{ROW.time_approved}</th>
                    <!-- BEGIN: admin -->
                    <td>
                        <select class="form-control" name="status" id="status_{{ROW.id_pose}}" onchange="nv_change_status_propose({ROW.id_pose}, this.value)">
                            <!-- BEGIN: status_proposed -->
                            <option value="{STATUS.key}" {STATUS.selected}>{STATUS.value}</option>
                            <!-- END: status_proposed -->
                        </select>
                    </td>
                    <!-- END: admin -->
                </tr>
                <!-- END: loop_rejected -->
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">{LANG.approve_proposed}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert">
                    <form method="post" class="form-horizontal" id="form_modal">
                        <div class="form-group">
                            <label for="category_laudatory" class="control-label col-sm-6">{LANG.category_laudatory}</label>
                            <div class="col-sm-18">
                                <input type="text" class="form-control" id="category_laudatory" readonly="readonly" name="category_laudatory" maxlength="250">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="employee" class="control-label col-sm-6">{LANG.name_employed}</label>
                            <div class="col-sm-18">
                                <input type="text" class="form-control" id="employee"  readonly="readonly" name="employee" maxlength="250">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="proposer" class="control-label col-sm-6">{LANG.admin_id}</label>
                            <div class="col-sm-18">
                                <input type="text" class="form-control" id="proposer" readonly="readonly" name="proposer" maxlength="250">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="time_propose" class="control-label col-sm-6">{LANG.time_proposed}</label>
                            <div class="col-sm-18">
                                <input type="text" class="form-control" id="time_propose" readonly="readonly" name="time_propose" maxlength="250">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="reason_proposed" class="control-label col-sm-6">{LANG.ly_do_de_xuat}</label>
                            <div class="col-sm-18">
                                <input type="text" class="form-control" id="reason_proposed" readonly="readonly" name="reason_proposed" maxlength="250">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="reason_approved" class="control-label col-sm-6"> {LANG.reason}<sup class="required">(*)</sup></label>
                            <div class="col-sm-18">
                                <textarea class="form-control" id="reason_approved" name="reason_approved"  placeholder="{LANG.please_enter_reason}"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="bonus" class="control-label col-sm-6">{LANG.bonus}</label>
                            <div class="col-sm-18">
                                <input type="text" class="form-control" id="bonus" name="bonus" maxlength="250" placeholder="{LANG.please_enter_bonus}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="image" class="control-label col-sm-6">{LANG.image}</label>
                            <div class="col-sm-18">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="image" name="image" placeholder="{LANG.image_test}">
                                    <span class="input-group-btn">
                                        <button type="button" name="selectimg" class="btn btn-info" title="{LANG.choose_image}">
                                            <em class="fa fa-folder-open-o"></em>
                                        </button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group text-center">
                            <input type="hidden" name="save" value="{NV_CHECK_SESSION}">
                            <input type="hidden" name="id" value="">
                            <button type="submit" class="btn btn-success" id="btn-approve" name="approve">{LANG.approve_proposed}</button>
                            <button type="submit" class="btn btn-danger" id="btn-reject" name="reject">{LANG.reject}</button>
                            <button type="button" class="btn btn-facebook" data-dismiss="modal"><i class="fa fa-times text-danger"></i> {LANG.close}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailModalLabel">{LANG.info_detail}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert">
                    <form class="form-horizontal" id="update_appove">
                        <div class="form-group">
                            <label for="detail_category" class="control-label col-sm-6">{LANG.category_laudatory}</label>
                            <div class="col-sm-18">
                                <input type="text" class="form-control" id="detail_category" readonly="readonly" maxlength="250">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="detail_employee" class="control-label col-sm-6">{LANG.name_employed}</label>
                            <div class="col-sm-18">
                                <input type="text" class="form-control" id="detail_employee" readonly="readonly" maxlength="250">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="detail_proposer" class="control-label col-sm-6">{LANG.admin_id}</label>
                            <div class="col-sm-18">
                                <input type="text" class="form-control" id="detail_proposer" readonly="readonly" maxlength="250">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="detail_time" class="control-label col-sm-6">{LANG.time_proposed}</label>
                            <div class="col-sm-18">
                                <input type="text" class="form-control" id="detail_time" readonly="readonly" maxlength="250">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="detail_reason" class="control-label col-sm-6">{LANG.ly_do_de_xuat}</label>
                            <div class="col-sm-18">
                                <input type="text" class="form-control" id="detail_reason" readonly="readonly" maxlength="250">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="detail_reason_approved" class="control-label col-sm-6">{LANG.reason_approved}</label>
                            <div class="col-sm-18">
                                <input type="text" class="form-control" id="detail_reason_approved" name="reason_approved" maxlength="250">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="detail_time_approved" class="control-label col-sm-6">{LANG.time_approve_proposed}</label>
                            <div class="col-sm-18">
                                <input type="text" class="form-control" id="detail_time_approved" readonly="readonly" maxlength="250">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="detail_bonus" class="control-label col-sm-6">{LANG.bonus}</label>
                            <div class="col-sm-18">
                                <input type="text" class="form-control" id="detail_bonus" name="bonus" maxlength="250">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="image" class="control-label col-sm-6">{LANG.image}</label>
                            <div class="col-sm-18">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="image1" name="image" placeholder="{LANG.image_test}" value="">
                                    <span class="input-group-btn">
                                        <button type="button" name="select_image_edit" class="btn btn-info" title="{LANG.choose_image}">
                                            <em class="fa fa-folder-open-o"></em>
                                        </button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group text-center">
                            <input type="hidden" name="save_edit" value="{NV_CHECK_SESSION}">
                            <input type="hidden" name="id" id="id_pose">
                            <button type="button" class="btn btn-facebook" data-dismiss="modal"><i class="fa fa-times text-danger"></i> {LANG.close}</button>
                            <button type="button" class="btn btn-success" id="save_edit" name="save">{LANG.edit}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function set_data(button) {
        var id = button.getAttribute('data-id');
        document.getElementById('myModal').querySelector('input[name="id"]').value = id;
    }
    $(document).ready(function() {
        function handleFormSubmit(action) {
            var $form = $('#form_modal');
            $.ajax({
                type: 'POST',
                url: script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=president',
                data: $form.serialize() + "&" + action + "=1",
                dataType: 'json',
            }).done(function(data) {
                if (data['res'] === 'success') {
                    $('#myModal').modal('hide');
                    location.reload();
                } else {
                    alert(data['mess']);
                }
            })
        }
        $('#btn-reject').click(function(e) {
            e.preventDefault();
            handleFormSubmit('reject');
        });
        $('#btn-approve').click(function(e) {
            e.preventDefault();
            handleFormSubmit('approve');
        });
    });
</script>
<script>
    $("button[name=selectimg]").click(function() {
        var area = "image";
        var alt = "imagealt";
        var path = '{NV_UPLOADS_DIR}/{MODULE_UPLOAD}';
        var type = "";
        var currentpath = '{NV_UPLOADS_DIR}/{MODULE_UPLOAD}';
        nv_open_browse(script_name + "?" + nv_name_variable + "=upload&popup=1&area=" + area + "&alt=" + alt + "&path=" + path + "&type=" + type + "&currentpath=" + currentpath, "NVImg", 850, 420, "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
        return false;
    });
    $("button[name=select_image_edit]").click(function() {
        var area = "image1";
        var alt = "imagealt";
        var path = '{NV_UPLOADS_DIR}/{MODULE_UPLOAD}';
        var type = "";
        var currentpath = '{NV_UPLOADS_DIR}/{MODULE_UPLOAD}';
        nv_open_browse(script_name + "?" + nv_name_variable + "=upload&popup=1&area=" + area + "&alt=" + alt + "&path=" + path + "&type=" + type + "&currentpath=" + currentpath, "NVImg", 850, 420, "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
        return false;
    });
</script>
<script>
    function set_data(button) {
        var id = button.getAttribute('data-id');
        var category = button.getAttribute('data-category');
        var employee = button.getAttribute('data-employee');
        var proposer = button.getAttribute('data-proposer');
        var reason = button.getAttribute('data-reason');
        var time = button.getAttribute('data-time');
        document.getElementById('id_pose').value = id;
        document.getElementById('category_laudatory').value = category;
        document.getElementById('employee').value = employee;
        document.getElementById('proposer').value = proposer;
        document.getElementById('reason_proposed').value = reason;
        document.getElementById('time_propose').value = time;
        document.getElementById('myModal').querySelector('input[name="id"]').value = id;
    }
    function showDetails(button) {
        var id = button.getAttribute('data-id');
        var category = button.getAttribute('data-category');
        var employee = button.getAttribute('data-employee');
        var proposer = button.getAttribute('data-proposer');
        var reason = button.getAttribute('data-reason');
        var time = button.getAttribute('data-time');
        var reasonApproved = button.getAttribute('data-reason-approved');
        var timeApproved = button.getAttribute('data-time-approved');
        var bonus = button.getAttribute('data-bonus');
        var image = button.getAttribute('data-image');
        document.getElementById('id_pose').value = id;
        document.getElementById('detail_category').value = category;
        document.getElementById('detail_employee').value = employee;
        document.getElementById('detail_proposer').value = proposer;
        document.getElementById('detail_reason').value = reason;
        document.getElementById('detail_time').value = time;
        document.getElementById('detail_reason_approved').value = reasonApproved;
        document.getElementById('detail_time_approved').value = timeApproved;
        document.getElementById('detail_bonus').value = bonus;
        document.getElementById('image1').value = image;
    }
</script>
<script>
    $(document).ready(function() {
        $('#save_edit').click(function(e) {
            var $form = $('#update_appove');
            $.ajax({
                type: 'POST',
                url: script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=president&edit=1',
                data: $form.serialize(),
                dataType: 'json',
            }).done(function(data) {
                if (data['res'] === 'success') {
                    $('#detailModal').modal('hide');
                    location.reload();
                } else {
                    alert(data['mess']);
                }
            });
        });
    });
</script>
<!-- END: main -->
