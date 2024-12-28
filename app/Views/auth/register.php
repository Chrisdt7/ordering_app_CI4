
<!-- Register Drawer -->
<div id="registerDrawer" class="drawer">
        <div class="drawer-close">
            <span class="close" onclick="closeDrawer('registerDrawer')">&times;</span>
        </div>
        <div class="drawer-content" data-aos="fade-left">
            <div class="section-title cust-title">
                <h2>Register</h2>
            </div>
            
            <form id="registerForm" action="<?= base_url('register') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="form-group mt-1 mb-1">
                    <label for="reg-username" class="cust-label">Username:</label>
                    <input type="text" id="reg-username" name="reg-username" class="form-control cust-control" placeholder="Your Username" value="<?= old('username') ?>" autofocus>
                    <?php if (session()->getFlashdata('validation') && isset(session()->getFlashdata('validation')['reg-username'])): ?>
                        <div class="text-danger text-center small">
                            <?= session()->getFlashdata('validation')['reg-username'] ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="form-group mt-1 mb-1">
                    <label for="reg-password" class="cust-label">Password:</label>
                    <input type="password" id="reg-password" name="reg-password" class="form-control cust-control" placeholder="Your Password" autofocus>
                    <?php if (session()->getFlashdata('validation') && isset(session()->getFlashdata('validation')['reg-password'])): ?>
                        <div class="text-danger text-center small">
                            <?= session()->getFlashdata('validation')['reg-password'] ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="form-group mt-1 mb-1">
                    <label for="name" class="cust-label">Full Name:</label>
                    <input type="text" id="name" name="name" class="form-control cust-control" placeholder="Your Full Name" value="<?= old('name') ?>" autofocus>
                    <?php if (session()->getFlashdata('validation') && isset(session()->getFlashdata('validation')['name'])): ?>
                        <div class="text-danger text-center small">
                            <?= session()->getFlashdata('validation')['reg-name'] ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="form-group mt-1 mb-1">
                    <label for="email" class="cust-label">E-mail:</label>
                    <input type="email" id="email" name="email" class="form-control cust-control" placeholder="Your E-mail" value="<?= old('email') ?>" autofocus>
                    <?php if (session()->getFlashdata('validation') && isset(session()->getFlashdata('validation')['email'])): ?>
                        <div class="text-danger text-center small">
                            <?= session()->getFlashdata('validation')['reg-email'] ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="form-group mt-1 mb-1">
                    <label for="contact" class="cust-label">Contact Number:</label>
                    <input type="text" id="contact" name="contact" class="form-control cust-control" placeholder="Your Contact Number" value="<?= old('contact') ?>" autofocus>
                    <?php if (session()->getFlashdata('validation') && isset(session()->getFlashdata('validation')['contact'])): ?>
                        <div class="text-danger text-center small">
                            <?= session()->getFlashdata('validation')['reg-contact'] ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="submit-btn mt-4">
                    <button type="submit" class="cust-btn1 cust-label">Register</button>
                </div>
            </form>
            <div>
                <h6 class="cust-label1">Already have an account? <a href="#" onclick="openDrawer('loginDrawer')">Login</a></h6>
            </div>
        </div>
    </div>
</div>