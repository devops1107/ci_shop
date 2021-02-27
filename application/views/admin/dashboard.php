<?php $user_type = $this->site_santry->get_auth_data('user_type'); 
//pr($user_type,1);
?>
<!-- Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
<div class="page-breadcrumb bg-white">
<div class="row">
    <div class="col-lg-3 col-md-4 col-xs-12 align-self-center">
        <h5 class="font-medium text-uppercase mb-0">Dashboard</h5>
    </div>
    <div class="col-lg-9 col-md-8 col-xs-12 align-self-center">
        <?php /* <button class="btn btn-danger text-white float-right ml-3 d-none d-md-block">Buy Ample Admin</button> */ ?>
        <nav aria-label="breadcrumb" class="mt-2 float-md-right float-left">
            <ol class="breadcrumb mb-0 justify-content-end p-0 bg-white">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
            </ol>
        </nav>
    </div>
</div>
</div>
<!-- ============================================================== -->
<!-- End Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
<!-- ============================================================== -->
<!-- Container fluid  -->
<!-- ============================================================== -->
<div class="page-content container-fluid">
<!-- ============================================================== -->
<!-- Card Group  -->
<!-- ============================================================== -->
<?php if($user_type=='admin'){ ?>
<div class="card-group">
    <div class="card p-2 p-lg-3">
        <div class="p-lg-3 p-2">
            <div class="d-flex align-items-center">
                <button class="btn btn-circle btn-success text-white btn-lg" href="javascript:void(0)">
                <i class="fas fa-user"></i>
            </button>
                <div class="ml-4" style="width: 38%;">
                    <h4 class="font-light">Total Active Users</h4>
                    <div class="progress">
                        <div class="progress-bar bg-success" role="progressbar" style="width: <?=$totalActiveUser?>%" aria-valuenow="40" aria-valuemin="0" aria-valuemax="40"></div>
                    </div>
                </div>
                <div class="ml-auto">
                    <h2 class="display-7 mb-0"><?=$totalActiveUser?></h2>
                </div>
            </div>
        </div>
    </div>
    <div class="card p-2 p-lg-3">
        <div class="p-lg-3 p-2">
            <div class="d-flex align-items-center">
                <button class="btn btn-circle btn-danger text-white btn-lg" href="javascript:void(0)">
                <i class="fas fa-user"></i>
            </button>
                <div class="ml-4" style="width: 39%;">
                    <h4 class="font-light">Total Inactive Users</h4>
                    <div class="progress">
                        <div class="progress-bar bg-warning" role="progressbar" style="width: <?=$totalInactiveUser?>%" aria-valuenow="40" aria-valuemin="0" aria-valuemax="40"></div>
                    </div>
                </div>
                <div class="ml-auto">
                    <h2 class="display-7 mb-0"><?=$totalInactiveUser?></h2>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card p-2 p-lg-3">
        <div class="p-lg-3 p-2">
            <div class="d-flex align-items-center">
                <button class="btn btn-circle btn-success text-white btn-lg" href="javascript:void(0)">
                <i class="ti-wallet"></i>
            </button>
                <div class="ml-4" style="width: 39%;">
                    <h4 class="font-light">Total Products</h4>
                    <div class="progress">
                        <div class="progress-bar bg-success" role="progressbar" style="width: <?=$totalProducts?>%" aria-valuenow="40" aria-valuemin="0" aria-valuemax="40"></div>
                    </div>
                </div>
                <div class="ml-auto">
                    <h2 class="display-7 mb-0"><?=$totalProducts?></h2>
                </div>
            </div>
        </div>
    </div>
</div>
<?php } ?>

