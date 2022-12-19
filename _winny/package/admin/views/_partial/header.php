<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @package 	CodeIgniter
 * @subpackage 	SainSuite
 * @author 		Buddy Winangun <buddywinangun[at]gmail[dot]com>
 * @link 		https://bit.ly/buddywinangun
 * @version 	1.0.0
 */
?>

<div class="main-header bg-color1">
    <div class="logo-header" style="border:0px">
        <a href="<?=site_url()?>" class="logo text-light">
            <?=strtoupper(strtolower($this->setting->globalset("nama")))?>
        </a>
        <button class="navbar-toggler sidenav-toggler ml-auto" type="button" data-toggle="collapse" data-target="collapse" aria-controls="sidebar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <button class="topbar-toggler more"><i class="la la-ellipsis-v"></i></button>
    </div>
    <nav class="navbar navbar-header navbar-expand-lg">
        <div class="container-fluid">
            <ul class="navbar-nav topbar-nav ml-md-auto align-items-center">
                <li class="nav-item dropdown">
                    <a class="dropdown-toggle profile-pic" data-toggle="dropdown" href="#" aria-expanded="false">
                        <img src="<?=base_url()?>assets/img/user.png" alt="user-img" width="36" class="img-circle">
                        <span class="text-light"><?=$this->user_model->getUserAdmin($_SESSION["uid"],"nama")?></span></span>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <a class="dropdown-item" href="javascript:$('#modalgantipass').modal();"><i class="la la-unlock"></i> Ganti Password</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="javascript:void(0)" onclick="logout()"><i class="la la-power-off"></i> Logout</a>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
            </ul>
        </div>
    </nav>
</div>