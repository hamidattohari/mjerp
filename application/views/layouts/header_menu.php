<!-- Navigation -->
<nav class="navbar navbar-default navbar-static-top m-b-0">
            <div class="navbar-header"> <a class="navbar-toggle hidden-sm hidden-md hidden-lg " href="javascript:void(0)" data-toggle="collapse" data-target=".navbar-collapse"><i class="ti-menu"></i></a>
                <div class="top-left-part"><a class="logo" href="<?=site_url('dashboard');?>"><b><img height="55" src="<?= asset_url($this->session->userdata('logo_path'));?>" alt="home" /></b><span class="hidden-xs"><img height="21" width="108" src="<?= asset_url($this->session->userdata('logo_title_path'));?>" alt="home" /></span></a></div>
                <ul class="nav navbar-top-links navbar-left hidden-xs">
                    <li><a href="javascript:void(0)" class="open-close hidden-xs waves-effect waves-light"><i class="icon-arrow-left-circle ti-menu"></i></a></li>
                    <li>
                        <form role="search" class="app-search hidden-xs">
                            <input type="text" placeholder="Search..." class="form-control">
                            <a href=""><i class="fa fa-search"></i></a>
                        </form>
                    </li>
                </ul>
                <ul class="nav navbar-top-links navbar-right pull-right">
                    <li class="dropdown">
                        <a class="dropdown-toggle profile-pic" data-toggle="dropdown" href="#"> <img src="<?= asset_url('images/default-pict.jpg');?>" alt="user-img" width="36" class="img-circle"><b class="hidden-xs"><?=$this->session->userdata('name');?></b> </a>
                        <ul class="dropdown-menu dropdown-user animated flipInY">
                            <li><a href="<?=site_url('profile');?>"><i class="ti-user"></i> My Profile</a></li>
                            <li role="separator" class="divider"></li> 
							<li>
								<a href="<?=site_url('login/logout');?>"><i class="fa fa-power-off"></i> Logout</a>
							</li>
                        </ul>
                        <!-- /.dropdown-user -->
                    </li>
                    <li class="right-side-toggle"> <a class="waves-effect waves-light" href="javascript:void(0)"><i class="icon-options-vertical"></i></a></li>
                    <!-- /.dropdown -->
                </ul>
            </div>
            <!-- /.navbar-header -->
            <!-- /.navbar-top-links -->
            <!-- /.navbar-static-side -->
        </nav>
