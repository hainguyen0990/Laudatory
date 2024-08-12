<!-- BEGIN: main -->

<div class="panel panel-default">
    <div class="panel-heading">
        <strong>{LANG.cfg_step1}</strong>
    </div>
    <div class="panel-body">
        <div class="text-center">
            <img alt="QR" src="{QR_SRC}" class="twostep-qrimg"/>
        </div>
        <hr />
        {LANG.cfg_step1_manual} <a href="#manualsecretkey" data-toggle="manualsecretkey">{LANG.cfg_step1_manual1}</a> {LANG.cfg_step1_manual2}.
    </div>
    <div class="hidden" id="manualsecretkey" title="{LANG.setup_key}">
        <div class="twostep-manualsecretkey">
            <div class="text-center">
                <strong>{SECRETKEY}</strong>
            </div>
            <hr />
            {LANG.cfg_step1_note}
        </div>
    </div>
</div>

<p>{LANG.cfg_step2_info}</p>

<div class="panel panel-default">
    <div class="panel-heading">
        <strong>{LANG.cfg_step2}</strong>
    </div>
    <div class="panel-body">
        <form action="{FORM_ACTION}" method="post" data-toggle="opt_validForm" autocomplete="off" novalidate>
            <div class="nv-info margin-bottom" data-default="" style="display: none"></div>
            <div class="form-detail">
                <div class="step1">
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon"><em class="fa fa-key fa-lg"></em></span>
                            <input type="text" class="required form-control" placeholder="123456" value="" name="opt" maxlength="6" data-pattern="/^(.){6,}$/" data-toggle="validErrorHidden" data-mess="">
                        </div>
                    </div>
                </div>

                <!-- BEGIN: forcedrelogin -->
                <div class="text-center margin-bottom-lg">
                    <div class="checkbox">
                        <label><input type="checkbox" class="form-control" name="forcedrelogin" value="1" style="margin-top:2px"> {LANG.forcedrelogin}</label>
                    </div>
                </div>
                <!-- END: forcedrelogin -->
                
                <div class="text-center margin-bottom-lg">
                     <input type="hidden" name="checkss" value="{NV_CHECK_SESSION}" />
                     <input type="hidden" name="nv_redirect" value="{NV_REDIRECT}" />
                    <button class="bsubmit btn btn-primary" type="submit">{LANG.confirm}</button>
               	</div>
            </div>
        </form>
    </div>
</div>

<!-- BEGIN: main -->