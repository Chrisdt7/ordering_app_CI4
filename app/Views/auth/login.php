<!-- Login Drawer -->
<div id="loginDrawer" class="drawer">
    <div class="drawer-close">
        <span class="close" onclick="closeDrawer('loginDrawer')">&times;</span>
    </div>
    <div class="drawer-content" data-aos="fade-left">
        <div class="section-title cust-title">
            <h2>Login</h2>
        </div>

        <?php if (session()->getFlashdata('message')) : ?>
            <div class="alert alert-danger alert-message cust-flash" style="background: red; font-family: 'Poppins', sans-serif; font-size: small; color: white;">
                <?= session()->getFlashdata('message') ?>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('success')) : ?>
            <div class="alert alert-success alert-message cust-flash" style="background: green; font-family: 'Poppins', sans-serif; font-size: small; color: white;">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>
        
        <form id="loginForm" action="<?= base_url('login') ?>" method="POST">
            <?= csrf_field() ?>
            <div class="form-group mt-1 mb-1">
                <label for="username" class="cust-label">Username:</label>
                <input type="text" id="username" name="username" class="form-control cust-control" placeholder="Your Username" value="<?= old('username') ?>" autofocus>
                <?php if (session()->getFlashdata('validation') && isset(session()->getFlashdata('validation')['username'])): ?>
                    <div class="text-danger text-center small">
                        <?= session()->getFlashdata('validation')['username'] ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="form-group mt-1 mb-1">
                <label for="password" class="cust-label">Password:</label>
                <input type="password" id="password" name="password" class="form-control cust-control" placeholder="Your Password" autofocus>
                <?php if (session()->getFlashdata('validation') && isset(session()->getFlashdata('validation')['password'])): ?>
                    <div class="text-danger text-center small">
                        <?= session()->getFlashdata('validation')['password'] ?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="submit-btn mt-4">
                <button type="submit" class="cust-btn1 cust-label">Login</button>
            </div>
        </form>
        <div>
            <h6 class="cust-label1">Don't have an account? <a href="#" onclick="openDrawer('registerDrawer')">Register</a></h6>
        </div>
    </div>
</div>