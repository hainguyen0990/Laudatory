<!-- BEGIN: main -->
<!-- BEGIN: data -->
<div class="table-responsive">
    <table class="table table-striped table-bordered">
        <colgroup>
            <col span="1" style="width: 80px;">
            <col span="3">
            <col span="1" style="width: 80px;">
            <col span="1" style="width: 80px;">
            <col span="1" style="width: 80px;">
            <col span="1" style="width: 80px;">
            <col span="1" style="width: 1%;">
         </colgroup>
        <thead>
            <tr class="bg-primary">
                <th class="text-center align-middle">{LANG.weight}</th>
                <th class="text-center align-middle">{LANG.field_id}</th>
                <th class="text-center align-middle">{LANG.field_title}</th>
                <th class="text-center align-middle">{LANG.field_type}</th>
                <th class="text-center">{LANG.for_admin}</th>
                <th class="text-center">{LANG.field_required}</th>
                <th class="text-center">{LANG.field_show_register}</th>
                <th class="text-center">{LANG.field_show_profile}</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <!-- BEGIN: loop -->
            <tr>
                <td class="text-center">
                    <select class="form-control" id="id_weight_{ROW.fid}" onchange="nv_chang_field({ROW.fid});" {DISABLED_WEIGHT}>
                        <!-- BEGIN: weight -->
                        <option value="{WEIGHT.key}" {WEIGHT.selected}>{WEIGHT.title}</option>
                        <!-- END: weight -->
                    </select>
                </td>
                <td>{ROW.field}</td>
                <td>{ROW.field_lang}</td>
                <td>{ROW.field_type} </td>
                <td class="text-center"><i class="fa {ROW.for_admin}"></i></td>
                <td class="text-center"><i class="fa {ROW.required}"></i></td>
                <td class="text-center"><i class="fa {ROW.show_register}"></i></td>
                <td class="text-center"><i class="fa {ROW.show_profile}"></i></td>
                <td class="text-nowrap">
                    <button type="button" class="btn btn-default btn-sm" onclick="nv_edit_field({ROW.fid});" title="{LANG.field_edit}"><em class="fa fa-edit fa-lg"></em></button>
                    <!-- BEGIN: show_delete -->
                    <button type="button" class="btn btn-default btn-sm" onclick="nv_del_field({ROW.fid})" title="{LANG.delete}"><em class="fa fa-trash-o fa-lg"></em></button>
                    <!-- END: show_delete -->
                </td>
            </tr>
            <!-- END: loop -->
        </tbody>
    </table>
</div>
<!-- END: data -->
<!-- BEGIN: load -->
<div id="module_show_list">&nbsp;</div>
<link type="text/css" href="{ASSETS_STATIC_URL}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" />
<script type="text/javascript" src="{ASSETS_STATIC_URL}/js/jquery/jquery.validate.min.js"></script>
<script type="text/javascript" src="{ASSETS_LANG_STATIC_URL}/js/language/jquery.validator-{NV_LANG_INTERFACE}.js"></script>
<script type="text/javascript" src="{ASSETS_STATIC_URL}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{ASSETS_LANG_STATIC_URL}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>
<!-- BEGIN: error -->
<div class="alert alert-danger">{ERROR}</div>
<!-- END: error -->
<form class="form-inline" action="{FORM_ACTION}" method="post" id="ffields" autocomplete="off">
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <caption><em class="fa fa-file-text-o">&nbsp;</em>{CAPTIONFORM} </caption>
            <colgroup>
                <col class="w250" />
                <col />
            </colgroup>
            <tbody>
                <!-- BEGIN: field -->
                <tr>
                    <td>{LANG.field_id} <span class="text-danger">(*)</span>:</td>
                    <td><label><input class="form-control required w100" type="text" value="{DATAFORM.field}" name="field" {DATAFORM.fielddisabled}> {LANG.field_id_note}</label></td>
                </tr>
                <!-- END: field -->
                <tr>
                    <td>{LANG.field_title} <span class="text-danger">(*)</span>:</td>
                    <td><input class="form-control w350 required" type="text" value="{DATAFORM.title}" name="title"></td>
                </tr>
                <tr>
                    <td>{LANG.field_description}:</td>
                    <td><textarea cols="60" rows="3" name="description" style="width:350px; overflow: hidden;" class="form-control">{DATAFORM.description}</textarea></td>
                </tr>
                <tr>
                    <td>{LANG.for_admin}</td>
                    <td><input name="for_admin" value="1" type="checkbox" {DATAFORM.for_admin}></td>
                </tr>
                <tr class="item {IS_HIDDEN}">
                    <td>{LANG.field_required}</td>
                    <td><input name="required" value="1" type="checkbox" {DATAFORM.required}> {LANG.field_required_note}</td>
                </tr>
                <tr class="item {IS_HIDDEN}">
                    <td>{LANG.field_show_register}</td>
                    <td><input name="show_register" value="1" type="checkbox" {DATAFORM.show_register}></td>
                </tr>
                <tr class="item {IS_HIDDEN}">
                    <td>{LANG.field_user_editable}</td>
                    <td><input name="user_editable" value="1" type="checkbox" {DATAFORM.user_editable} /></td>
                </tr>
                <tr class="item {IS_HIDDEN}">
                    <td>{LANG.field_show_profile}</td>
                    <td><input name="show_profile" value="1" type="checkbox" {DATAFORM.show_profile}></td>
                </tr>
                <tr>
                    <td>{LANG.field_type}:</td>
                    <td>
                        <!-- BEGIN: field_type -->
                        <ul style="list-style: none">
                            <!-- BEGIN: loop -->
                            <li>
                                <label for="f_{FIELD_TYPE.key}"> <input type="radio" {FIELD_TYPE.checked} id="f_{FIELD_TYPE.key}" value="{FIELD_TYPE.key}" name="field_type"> {FIELD_TYPE.value}</label>
                            </li>
                            <!-- END: loop -->
                        </ul>{LANG.field_type_note}
                        <!-- END: field_type -->
                        {FIELD_TYPE_TEXT}
                    </td>
                </tr>
                <tr id="classfields" {DATAFORM.classdisabled}>
                    <td>{LANG.field_class}</td>
                    <td><input class="form-control w300 validalphanumeric alphanumeric" type="text" value="{DATAFORM.class}" name="class" maxlength="50"></td>
                </tr>
                <tr id="editorfields" {DATAFORM.editordisabled}>
                    <td>{LANG.field_size}</td>
                    <td>width: <input class="form-control w100" type="text" value="{DATAFORM.editor_width}" name="editor_width" maxlength="5"> height: <input class="form-control w100" type="text" value="{DATAFORM.editor_height}" name="editor_height" maxlength="5"></td>
                </tr>
            </tbody>
        </table>
        <table class="table table-striped table-bordered table-hover" id="textfields" {DATAFORM.display_textfields}>
            <caption><em class="fa fa-file-text-o">&nbsp;</em>{LANG.field_options_text}</caption>
            <colgroup>
                <col class="w250" />
                <col />
            </colgroup>
            <tbody>
                <tr>
                    <td>{LANG.field_match_type}</td>
                    <td>
                        <ul style="list-style: none;">
                            <!-- BEGIN: match_type -->
                            <li id="li_{MATCH_TYPE.key}">
                                <label for="m_{MATCH_TYPE.key}"> <input type="radio" {MATCH_TYPE.checked} id="m_{MATCH_TYPE.key}" value="{MATCH_TYPE.key}" name="match_type"> {MATCH_TYPE.value}</label>
                                <!-- BEGIN: match_input -->
                                <input class="form-control" type="text" value="{MATCH_TYPE.match_value}" name="match_{MATCH_TYPE.key}" {MATCH_TYPE.match_disabled}>
                                <!-- END: match_input -->
                            </li>
                            <!-- END: match_type -->
                        </ul>
                    </td>
                </tr>
                <tr>
                    <td>{LANG.field_default_value}:</td>
                    <td><input class="form-control w300" maxlength="255" type="text" value="{DATAFORM.default_value}" name="default_value"></td>
                </tr>
                <tr id="max_length">
                    <td>{LANG.field_min_length}:</td>
                    <td><input class="form-control w100 number" type="text" value="{DATAFORM.min_length}" name="min_length"><span style="margin-left: 50px;">{LANG.field_max_length}:</span><input class="form-control w100 number" type="text" value="{DATAFORM.max_length}" name="max_length"></td>
                </tr>
            </tbody>
        </table>

        <table class="table table-striped table-bordered table-hover" id="numberfields" {DATAFORM.display_numberfields}>
            <caption><em class="fa fa-file-text-o">&nbsp;</em>{LANG.field_options_number}</caption>
            <colgroup>
                <col class="w250" />
                <col />
            </colgroup>
            <tbody>
                <tr>
                    <td>{LANG.field_number_type}:</td>
                    <td><input type="radio" value="1" name="number_type" {DATAFORM.number_type_1}>{LANG.field_integer} <input type="radio" value="2" name="number_type" {DATAFORM.number_type_2}> {LANG.field_real} </td>
                </tr>
                <tr>
                    <td>{LANG.field_default_value}:</td>
                    <td><input class="form-control w300 required number" maxlength="255" type="text" value="{DATAFORM.default_value_number}" name="default_value_number"></td>
                </tr>
                <tr>
                    <td>{LANG.field_min_value}:</td>
                    <td><input class="form-control w100 required number" type="text" value="{DATAFORM.min_number}" name="min_number_length" maxlength="11"><span style="margin-left: 50px;">{LANG.field_max_value}:</span><input class="form-control w100 required number" type="text" value="{DATAFORM.max_number}" name="max_number_length" maxlength="11"></td>
                </tr>
            </tbody>
        </table>

        <table class="table table-striped table-bordered table-hover" id="datefields" {DATAFORM.display_datefields}>
            <caption><em class="fa fa-file-text-o">&nbsp;</em>{LANG.field_options_date}</caption>
            <colgroup>
                <col class="w250" />
                <col />
            </colgroup>
            <tbody>
                <tr>
                    <td>{LANG.field_default_value}:</td>
                    <td>
                        <label>
                            <input type="radio" value="1" name="current_date" {DATAFORM.current_date_1}> {LANG.field_current_date}
                        </label>
                        <label>
                            <input type="radio" value="0" name="current_date" {DATAFORM.current_date_0}> {LANG.field_default_date}
                        </label>
                        &nbsp;
                        <input class="form-control" style="width:100px" type="text" value="{DATAFORM.default_date}" name="default_date" autocomplete="off">
                    </td>
                </tr>
                <tr>
                    <td>{LANG.field_min_date}:</td>
                    <td>
                        <input class="form-control datepicker" style="width:100px" type="text" value="{DATAFORM.min_date}" name="min_date" maxlength="10" autocomplete="off">
                        <span style="margin-left: 50px;">{LANG.field_max_date}:</span>
                        <input class="form-control datepicker" style="width:100px" type="text" value="{DATAFORM.max_date}" name="max_date" maxlength="10" autocomplete="off">
                    </td>
                </tr>
            </tbody>
        </table>
        <table class="table table-striped table-bordered table-hover" id="choicetypes" {DATAFORM.display_choicetypes}>
            <colgroup>
                <col class="w250" />
                <col />
            </colgroup>
            <tr>
                <td>{LANG.field_choicetypes_title}</td>
                <td>
                    <!-- BEGIN: choicetypes_add -->
                    <select class="form-control" name="choicetypes">
                        <!-- BEGIN: choicetypes -->
                        <option {CHOICE_TYPES.selected} value="{CHOICE_TYPES.key}"> {CHOICE_TYPES.value}</option>
                        <!-- END: choicetypes -->
                    </select>
                    <!-- END: choicetypes_add -->
                    <!-- BEGIN: choicetypes_add_hidden -->
                    {FIELD_TYPE_SQL}<input type="hidden" name="choicetypes" value="{choicetypes_add_hidden}" />
                    <!-- END: choicetypes_add_hidden -->
                </td>
            </tr>
        </table>
        <table class="table table-striped table-bordered table-hover" id="choicesql" {DATAFORM.display_choicesql}>
            <caption><em class="fa fa-file-text-o">&nbsp;</em>{LANG.field_options_choicesql}</caption>
            <colgroup>
                <col class="w250" />
                <col span="2" />
            </colgroup>
            <thead>
                <tr>
                    <th>{LANG.field_options_choicesql_module}</th>
                    <th>{LANG.field_options_choicesql_table}</th>
                    <th>{LANG.field_options_choicesql_column}</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><span id="choicesql_module">&nbsp;</span></td>
                    <td><span id="choicesql_table">&nbsp;</span></td>
                    <td><span id="choicesql_column">&nbsp;</span></td>
                </tr>
            </tbody>
        </table>
        <table class="table table-striped table-bordered table-hover" id="choiceitems" {DATAFORM.display_choiceitems}>
            <caption><em class="fa fa-file-text-o">&nbsp;</em>{LANG.field_options_choice}</caption>
            <colgroup>
                <col class="w250" />
                <col span="3" />
            </colgroup>
            <thead>
                <tr>
                    <th class="text-center">{LANG.field_number}</th>
                    <th class="text-center">{LANG.field_value}</th>
                    <th class="text-center">{LANG.field_text} (*)</th>
                    <th class="text-center">{LANG.field_default_value}</th>
                </tr>
            </thead>
            <!-- BEGIN: add_field_choice -->
            <tfoot>
                <tr>
                    <td colspan="4"><input style="margin-left: 50px;" class="btn btn-default" type="button" value="{LANG.field_add_choice}" onclick="nv_choice_fields_additem('{LANG.field_match_type_alphanumeric}');" /><br/><br/><div class="help-block">(*) {LANG.value_empty_note}</div></td>
                </tr>
            </tfoot>
            <!-- END: add_field_choice -->
            <tbody class="uncheckRadio">
                <!-- BEGIN: loop_field_choice -->
                <tr class="text-center">
                    <td>{FIELD_CHOICES.number}</td>
                    <td><input class="form-control w200 validalphanumeric alphanumeric" type="text" value="{FIELD_CHOICES.key}" name="field_choice[{FIELD_CHOICES.number}]" placeholder="{LANG.field_match_type_alphanumeric}" {FIELD_CHOICES_READONLY} /></td>
                    <td><input class="form-control w300" type="text" value="{FIELD_CHOICES.value}" name="field_choice_text[{FIELD_CHOICES.number}]" {FIELD_CHOICES_READONLY} /></td>
                    <td><input type="radio" {FIELD_CHOICES.checked} value="{FIELD_CHOICES.number}" name="default_value_choice"></td>
                </tr>
                <!-- END: loop_field_choice -->
            </tbody>
        </table>

        <table class="table table-striped table-bordered" id="filefields" {DATAFORM.display_filefields}>
            <caption><em class="fa fa-file-text-o">&nbsp;</em>{LANG.field_options_file}</caption>
            <colgroup>
                <col class="w250" />
            </colgroup>
            <tbody class="field_file">
                <tr>
                    <td>{LANG.field_file_exts}</td>
                    <td>
                        <!-- BEGIN: filetype -->
                        <div class="m-bottom filetype">
                            <input type="checkbox" class="hidden" name="filetype[]" value="{FILETYPE.key}" {FILETYPE.checked}>
                            <p><strong>{FILETYPE.key}</strong></p>
                            <!-- BEGIN: mime -->
                            <label class="btn btn-default filemime">
                                <input type="checkbox" data-toggle="mimecheck" name="mime[]" value="{MIME.key}" {MIME.checked}> {MIME.key}
                            </label>
                            <!-- END: mime -->
                        </div>
                        <!-- END: filetype -->
                    </td>
                </tr>
                <tr>
                    <td>{LANG.field_file_max_size}</td>
                    <td>
                        <select name="file_max_size" class="form-control" style="width: fit-content;">
                            <!-- BEGIN: size -->
                            <option value="{SIZE.key}" {SIZE.sel}>{SIZE.name}</option>
                            <!-- END: size -->
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>{LANG.field_file_maxnum}</td>
                    <td>
                        <select name="maxnum" class="form-control" style="width: fit-content;">
                            <!-- BEGIN: maxnum -->
                            <option value="{MAXNUM.key}" {MAXNUM.sel}>{MAXNUM.key}</option>
                            <!-- END: maxnum -->
                        </select>
                    </td>
                </tr>
                <tr class="photo_max_size" {DATAFORM.display_photo_max_size}>
                    <td>{LANG.field_photo_max_size}</td>
                    <td>
                        <div class="form-inline m-bottom">
                            <label class="w100">{LANG.field_photo_width}:</label>
                            <div class="input-group w100">
                                <span class="input-group-addon">=</span>
                                <input type="text" class="form-control number" name="widthlimit[equal]" value="{DATAFILE.widthlimit.equal}" maxlength="4">
                            </div>
                            <div class="input-group w100">
                                <span class="input-group-addon">&#8805;</span>
                                <input type="text" class="form-control number" name="widthlimit[greater]" value="{DATAFILE.widthlimit.greater}" maxlength="4">
                            </div>
                            <div class="input-group w100">
                                <span class="input-group-addon">&#8804;</span>
                                <input type="text" class="form-control number" name="widthlimit[less]" value="{DATAFILE.widthlimit.less}" maxlength="4">
                            </div>
                            <label>px</label>
                        </div>
                        <div class="form-inline">
                            <label class="w100">{LANG.field_photo_height}:</label>
                            <div class="input-group w100">
                                <span class="input-group-addon">=</span>
                                <input type="text" class="form-control number" name="heightlimit[equal]" value="{DATAFILE.heightlimit.equal}" maxlength="4">
                            </div>
                            <div class="input-group w100">
                                <span class="input-group-addon">&#8805;</span>
                                <input type="text" class="form-control number" name="heightlimit[greater]" value="{DATAFILE.heightlimit.greater}" maxlength="4">
                            </div>
                            <div class="input-group w100">
                                <span class="input-group-addon">&#8804;</span>
                                <input type="text" class="form-control number" name="heightlimit[less]" value="{DATAFILE.heightlimit.less}" maxlength="4">
                            </div>
                            <label>px</label>
                        </div>
                        <div class="help-block">{LANG.field_photo_max_size_note}</div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div style="margin-left: 350px;">
        <input type="hidden" value="{DATAFORM.system}" name="system">
        <input type="hidden" value="{DATAFORM.fid}" name="fid">
        <input type="hidden" value="{DATAFORM.field}" name="fieldid">
        <input type="hidden" value="1" name="save">
        <input class="btn btn-primary" type="submit" value="{LANG.save}">
    </div>
</form>
<script type="text/javascript">
    var items = '{FIELD_CHOICES_NUMBER}';

    $(document).ready(function() {
        if ($("input[name=fid]").val() == 0) {
            nv_show_list_field();
        }
        nv_load_current_date();

        $.validator.addMethod('validalphanumeric', function(str) {
            if (str == '') {
                return true;
            }
            var fieldCheck_rule = /^([a-zA-Z0-9_-])+$/;
            return (fieldCheck_rule.test(str)) ? true : false;
        }, '{LANG.field_match_type_alphanumeric}');

        $('#ffields').validate();
    });

    function nv_load_sqlchoice(choice_name_select, choice_seltected) {
        var getval = "";
        if (choice_name_select == "table") {
            var choicesql_module = $("select[name=choicesql_module]").val();
            var module_selected = (choicesql_module == "" || choicesql_module == undefined ) ? '{SQL_DATA_CHOICE.0}' : choicesql_module;
            getval = "&module=" + module_selected;
            $("#choicesql_column").html("");
        } else if (choice_name_select == "column") {
            var choicesql_module = $("select[name=choicesql_module]").val();
            var module_selected = (choicesql_module == "" || choicesql_module == undefined ) ? '{SQL_DATA_CHOICE.0}' : choicesql_module;
            var choicesql_table = $("select[name=choicesql_table]").val();
            var table_selected = (choicesql_table == "" || choicesql_table == undefined ) ? '{SQL_DATA_CHOICE.1}' : choicesql_table;
            getval = "&module=" + module_selected + "&table=" + table_selected;
        }
        $.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=fields&nocache=' + new Date().getTime(), 'choicesql=1&choice=' + choice_name_select + getval + '&choice_seltected=' + choice_seltected, function(res) {
            $('#choicesql_' + choice_name_select).html(res);

        });
    }
</script>
<!-- END: load -->
<!-- BEGIN: nv_load_sqlchoice -->
<script type="text/javascript">
    nv_load_sqlchoice('module', '{SQL_DATA_CHOICE.0}');
    nv_load_sqlchoice('table', '{SQL_DATA_CHOICE.1}');
    nv_load_sqlchoice('column', '{SQL_DATA_CHOICE.2}|{SQL_DATA_CHOICE.3}|{SQL_DATA_CHOICE.4}|{SQL_DATA_CHOICE.5}');
</script>
<!-- END: nv_load_sqlchoice -->
<!-- END: main -->
<!-- BEGIN: choicesql -->
<select class="form-control" onchange="nv_load_sqlchoice( '{choicesql_next}', '' )" name="{choicesql_name}">
    <!-- BEGIN: loop -->
    <option {SQL.sl} value="{SQL.key}">{SQL.val}</option>
    <!-- END: loop -->
</select>
<!-- END: choicesql -->
<!-- BEGIN: column -->
<div class="m-bottom">
    {LANG.field_options_choicesql_key}:
    <select class="form-control" name="choicesql_column_key" id="choicesql_column_key">
        <!-- BEGIN: loop1 -->
        <option {SQL.sl_key} value="{SQL.key}">{SQL.val}</option>
        <!-- END: loop1 -->
    </select>
    {LANG.field_options_choicesql_val}:
    <select class="form-control" name="choicesql_column_val" id="choicesql_column_val">
        <!-- BEGIN: loop2 -->
        <option {SQL.sl_val} value="{SQL.key}">{SQL.val}</option>
        <!-- END: loop2 -->
    </select>
</div>
<div>
    {LANG.field_options_choicesql_order}:
    <select class="form-control" name="choicesql_column_order" id="choicesql_column_order">
        <option value="">--</option>
        <!-- BEGIN: loop3 -->
        <option {SQL.sl_order} value="{SQL.key}">{SQL.val}</option>
        <!-- END: loop3 -->
    </select>
    {LANG.field_options_choicesql_sort}:
    <select class="form-control" name="choicesql_sort_type" id="choicesql_sort_type">
        <!-- BEGIN: sort -->
        <option value="{SORT.key}" {SORT.selected}>{SORT.title}</option>
        <!-- END: sort -->
    </select>
</div>
<!-- END: column -->
