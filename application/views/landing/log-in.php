

    <!-- Header -->
    <header id="header" class="ex-2-header">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h1>Log In</h1>
                   <p>Belum Mempunyai Akun ? Silahkan <a class="white" href="<?=base_url('landing/register');?>">Daftar</a></p>
                    <!-- Sign Up Form -->

                    <div class="form-container">
                    <?=$this->session->flashdata('message');?>
                        <form method="post" action="<?=base_url('auth');?>">
                            <div class="form-group">
                                <input type="email" class="form-control-input" id="email" name="email">
                                <label class="label-control" for="lemail">Email</label>
                                <div class="help-block with-errors"></div>
                                <?=form_error('email', '<small class="text-danger pl-3">', '</small>');?>
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control-input" id="password" name="password">
                                <label class="label-control" for="lpassword">Password</label>
                                <div class="help-block with-errors"></div>
                                <?=form_error('password', '<small class="text-danger pl-3">', '</small>');?>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="form-control-submit-button">LOG IN</button>
                            </div>
                            <div class="form-message">
                                <div id="lmsgSubmit" class="h3 text-center hidden"></div>
                            </div>
                        </form>
                    </div> <!-- end of form container -->
                    <!-- end of sign up form -->

                </div> <!-- end of col -->
            </div> <!-- end of row -->
        </div> <!-- end of container -->
    </header> <!-- end of ex-header -->
    <!-- end of header -->


    <!-- Scripts -->
    <script src="js/jquery.min.js"></script> <!-- jQuery for Bootstrap's JavaScript plugins -->
    <script src="js/popper.min.js"></script> <!-- Popper tooltip library for Bootstrap -->
    <script src="js/bootstrap.min.js"></script> <!-- Bootstrap framework -->
    <script src="js/jquery.easing.min.js"></script> <!-- jQuery Easing for smooth scrolling between anchors -->
    <script src="js/swiper.min.js"></script> <!-- Swiper for image and text sliders -->
    <script src="js/jquery.magnific-popup.js"></script> <!-- Magnific Popup for lightboxes -->
    <script src="js/validator.min.js"></script> <!-- Validator.js - Bootstrap plugin that validates forms -->
    <script src="js/scripts.js"></script> <!-- Custom scripts -->
</body>
</html>
