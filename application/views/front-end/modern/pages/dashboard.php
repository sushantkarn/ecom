<?php $current_url = current_url(); ?>
<!-- breadcrumb -->
<!-- <div class="content-wrapper">
    <section class="wrapper bg-soft-grape">
        <div class="container py-3 py-md-5">
            <nav class="d-inline-block" aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 bg-transparent">
                    <li class="breadcrumb-item"><a href="<?//= base_url() ?>" class="text-decoration-none"><?//= !empty($this->lang->line('home')) ? $this->lang->line('home') : 'Home' ?></a></li>
                    <li class="breadcrumb-item"><a href="<?//= base_url('my-account') ?>"><?//= !empty($this->lang->line('my_account')) ? $this->lang->line('my_account') : 'My Account' ?></a></li>
                </ol>
            </nav>
        </div>
    </section>
</div> -->
<!-- end breadcrumb -->

<!-- <section class="my-account-section">
    <div class="container mb-15 mt-10"> -->
<!-- <div class="col-md-12 mt-5 mb-3">
            
        </div> -->
        <div class="overflow-auto">
            <div class="px-1 py-2">
                <div class="d-flex gap-2 justify-content-between">
                    <div class='card col-6 col-md-3 h-15 text-center w-15 p-0 <?= ($current_url == base_url('my-account/profile')) ? 'bg-soft-primary' : '' ?>'>
                        <a href='<?= base_url('my-account/profile') ?>' class="link-color text-decoration-none">
                            <div class='bg-transparent card-header fs-12 p-2'>
                                <?= !empty($this->lang->line('profile')) ? $this->lang->line('profile') : 'PROFILE' ?>
                            </div>
                            <div class='card-body p-2'>
                                <i class="uil uil-user-circle fs-22 dashboard-icon link-color"></i>
                            </div>
                        </a>
                    </div>
                    <div class='card col-6 col-md-3 h-15 text-center w-15 p-0 <?= ($current_url == base_url('my-account/orders')) ? 'bg-soft-primary' : '' ?>'>
                        <a href='<?= base_url('my-account/orders') ?>' class="link-color text-decoration-none">
                            <div class='bg-transparent card-header fs-12 p-2'>
                                <?= !empty($this->lang->line('orders')) ? $this->lang->line('orders') : 'ORDERS' ?>
                            </div>
                            <div class='card-body p-2'>
                                <i class="uil uil-history fs-22 dashboard-icon link-color"></i>
                            </div>
                        </a>
                    </div>
                    <!-- <div class='card col-6 col-md-3 h-15 text-center w-15 p-0 <?//= ($current_url == base_url('my-account/notifications')) ? 'bg-soft-primary' : '' ?>'>
                        <a href='<?//= base_url('my-account/notifications') ?>' class="link-color text-decoration-none">
                            <div class='bg-transparent card-header fs-12 p-2'>
                                <?//= !empty($this->lang->line('notification')) ? $this->lang->line('notification') : 'NOTIFICATION' ?>
                            </div>
                            <div class='card-body p-2'>
                                <i class="uil uil-bell fs-22 dashboard-icon link-color"></i>
                            </div>
                        </a>
                    </div> -->
                    <div class='card col-6 col-md-3 h-15 text-center w-15 p-0 <?= ($current_url == base_url('my-account/favorites')) ? 'bg-soft-primary' : '' ?>'>
                        <a href='<?= base_url('my-account/favorites') ?>' class="link-color text-decoration-none">
                            <div class='bg-transparent card-header fs-12 p-2'>
                                <?= !empty($this->lang->line('favorite')) ? $this->lang->line('favorite') : 'Favorite' ?>
                            </div>
                            <div class='card-body p-2'>
                                <i class="uil uil-heart-alt fs-22 dashboard-icon link-color"></i>
                            </div>
                        </a>
                    </div>
                    <div class='card col-6 col-md-3 h-15 text-center w-15 p-0 <?= ($current_url == base_url('my-account/manage-address')) ? 'bg-soft-primary' : '' ?>'>
                        <a href='<?= base_url('my-account/manage-address') ?>' class="link-color text-decoration-none">
                            <div class='bg-transparent card-header fs-12 p-2'>
                                <?= !empty($this->lang->line('address')) ? $this->lang->line('address') : 'ADDRESS' ?>
                            </div>
                            <div class='card-body p-2'>
                                <i class="uil uil-map fs-22 dashboard-icon link-color"></i>
                            </div>
                        </a>
                    </div>
                    <div class='card col-6 col-md-3 h-15 text-center w-15 p-0 <?= ($current_url == base_url('my-account/wallet')) ? 'bg-soft-primary' : '' ?>'>
                        <a href='<?= base_url('my-account/wallet') ?>' class="link-color text-decoration-none">
                            <div class='bg-transparent card-header fs-12 p-2'>
                                <?= !empty($this->lang->line('wallet')) ? $this->lang->line('wallet') : 'WALLET' ?>
                            </div>
                            <div class='card-body p-2'>
                                <i class="uil uil-wallet fs-22 dashboard-icon link-color"></i>
                            </div>
                        </a>
                    </div>
                    <div class='card col-6 col-md-3 h-15 text-center w-15 p-0 <?= ($current_url == base_url('my-account/transactions')) ? 'bg-soft-primary' : '' ?>'>
                        <a href='<?= base_url('my-account/transactions') ?>' class="link-color text-decoration-none">
                            <div class='bg-transparent card-header fs-12 p-2'>
                                <?= !empty($this->lang->line('transaction')) ? $this->lang->line('transaction') : 'TRANSACTION' ?>
                            </div>
                            <div class='card-body p-2'>
                                <i class="uil uil-money-bill fs-22 dashboard-icon link-color"></i>
                            </div>
                        </a>
                    </div>
                    <div class='card col-6 col-md-3 h-15 text-center w-15 p-0 <?= ($current_url == base_url('my-account/chat')) ? 'bg-soft-primary' : '' ?>'>
                        <a href='<?= base_url('my-account/chat') ?>' class="link-color text-decoration-none">
                            <div class='bg-transparent card-header fs-12 p-2'>
                                <?= !empty($this->lang->line('chat')) ? $this->lang->line('chat') : 'Chat' ?>
                            </div>
                            <div class='card-body p-2'>
                                <i class="uil uil-comments-alt fs-22 dashboard-icon link-color"></i>
                            </div>
                        </a>
                    </div>
                    <div class='card col-6 col-md-3 h-15 text-center w-15 p-0 <?= ($current_url == base_url('my-account/tickets')) ? 'bg-soft-primary' : '' ?>'>
                        <a href='<?= base_url('my-account/tickets') ?>' class="link-color text-decoration-none">
                            <div class='bg-transparent card-header fs-12 p-1'>
                                <?= !empty($this->lang->line('support_tickets')) ? $this->lang->line('support_tickets') : 'Support Tickets' ?>
                            </div>
                            <div class='card-body p-2'>
                                <i class="uil uil-ticket fs-22 dashboard-icon link-color"></i>
                            </div>
                        </a>
                    </div>
                    <div class='card col-6 col-md-3 h-15 text-center w-15 p-0 <?= ($current_url == base_url('my-account/refer_and_earn')) ? 'bg-soft-primary' : '' ?>'>
                        <a href='<?= base_url('my-account/refer_and_earn') ?>' class="link-color text-decoration-none">
                            <div class='bg-transparent card-header fs-12 px-1 py-2'>
                                <?= !empty($this->lang->line('refer_and_earn')) ? $this->lang->line('refer_and_earn') : 'Refer and Earn' ?>
                            </div>
                            <div class='card-body p-2'>
                                <i class="uil uil-coins fs-22 dashboard-icon link-color"></i>
                            </div>
                        </a>
                    </div>
                    <div class='card col-6 col-md-3 h-15 text-center w-15 p-0 <?= ($current_url == base_url('my-account/logout')) ? 'bg-soft-primary' : '' ?>'>
                        <a href='' class="link-color text-decoration-none" id="user_logout">
                            <div class='bg-transparent card-header fs-12 p-2'>
                                <?= !empty($this->lang->line('logout')) ? $this->lang->line('logout') : 'LOGOUT' ?>
                            </div>
                            <div class='card-body p-2'>
                                <i class="uil uil-signout fs-22 dashboard-icon link-color"></i>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
<!--end row-->
<!-- </div>
</section> -->