	<!-- Footer -->
	<footer class="custom-footer">
        <span> &copy; Digi Resto 2024</span>
    </footer>
    <!-- End of Footer -->

	<div id="preloader"></div>
  	<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

	<!-- Vendor JS Files -->
	<script src="<?= base_url() ?>assets/vendor/aos/aos.js"></script>
	<script src="<?= base_url() ?>assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
	<script src="<?= base_url() ?>assets/vendor/glightbox/js/glightbox.min.js"></script>
	<script src="<?= base_url() ?>assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
	<script src="<?= base_url() ?>assets/vendor/swiper/swiper-bundle.min.js"></script>
	<script src="<?= base_url() ?>assets/vendor/php-email-form/validate.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>

	<!-- Template Main JS File -->
	<script src="<?= base_url() ?>assets/js/main.js"></script>
	<script src="<?= base_url() ?>assets/js/script.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const paths = document.querySelectorAll('.path');
            paths.forEach(path => {
                const length = path.getTotalLength();
                path.style.strokeDasharray = length;
                path.style.strokeDashoffset = length;
                path.getBoundingClientRect(); // Force reflow to apply styles
                path.style.animation = `dash 4s linear forwards`;
            });

            <?php if (session()->getFlashdata('add_modal_open')): ?>
                console.log('Opening modal');
                $('#addModal').modal('show'); // Open the modal
            <?php endif; ?>
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#menuToggle').on('click', function(e) {
                e.preventDefault();
                var submenu = $(this).next('.submenu');
                var caretIcon = $('#caretIcon');
                submenu.slideToggle(500, function() {
                    caretIcon.toggleClass('rotate-down', submenu.is(':visible'));
                    if (!submenu.is(':visible')) {
                        caretIcon.removeClass('rotate-down');
                    }
                });
            });
        });
    </script>

</body>
</html>