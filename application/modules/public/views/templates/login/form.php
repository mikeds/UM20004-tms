<?php
    $form_url = isset($form_url) ? $form_url : "#";
?>
<div class="content-wrapper d-flex align-items-stretch auth auth-img-bg">
    <div class="row flex-grow">
        <div class="col-lg-6 d-flex align-items-center justify-content-center">
        <div class="auth-form-transparent text-left p-3">
            <div class="form-group">
                <?=(isset($notification) ? (!empty($notification) ? $notification : '') : '' )?>
            </div>
            <h4>Welcome back!</h4>
            <h6 class="font-weight-light">Login your username and password</h6>
            <form class="pt-3" action="<?=$form_url?>" role="form" method="POST">
                <div class="form-group">
                    <label for="username">Username</label>
                    <div class="input-group">
                        <div class="input-group-prepend bg-transparent">
                            <span class="input-group-text bg-transparent border-right-0">
                            <i class="mdi mdi-account-outline text-primary"></i>
                            </span>
                        </div>
                        <input type="text" name="username" class="form-control form-control-lg border-left-0" id="username" placeholder="Username">
                    </div>
                    <span class="text-danger"><?=form_error('username')?></span>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-group">
                        <div class="input-group-prepend bg-transparent">
                            <span class="input-group-text bg-transparent border-right-0">
                            <i class="mdi mdi-lock-outline text-primary"></i>
                            </span>
                        </div>
                        <input type="password" name="password" class="form-control form-control-lg border-left-0" id="password" placeholder="Password">                      
                    </div>
                    <span class="text-danger"><?=form_error('password')?></span>  
                </div>
                <div class="my-3">
                    <button type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">LOGIN</button>
                </div>
            </form>
        </div>
        </div>
        <div class="col-lg-6 login-bg d-flex flex-row">
        <p class="text-white font-weight-medium text-center flex-grow align-self-end">Copyright &copy; 2020 BambuPAY All rights reserved.</p>
        </div>
    </div>
</div>
