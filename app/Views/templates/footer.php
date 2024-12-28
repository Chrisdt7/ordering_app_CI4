  <!-- ======= Footer ======= -->
  <footer id="footer">
    <div class="container">
      <div class="copyright">
        &copy; Copyright <strong><span>Digi Resto</span></strong>. All Rights Reserved
      </div>
      <div class="credits">
        <!-- All the links in the footer should remain intact. -->
        <!-- You can delete the links only if you purchased the pro version. -->
        <!-- Licensing information: https://bootstrapmade.com/license/ -->
        <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/restaurantly-restaurant-template/ -->
        Designed by <a href="#">Christy Dany Tallane</a>
      </div>
    </div>
  </footer><!-- End Footer -->

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
        window.onclick = function(event) {
            var drawers = document.getElementsByClassName('drawer');
            for (var i = 0; i < drawers.length; i++) {
                var drawer = drawers[i];
                if (event.target == drawer) {
                    drawer.style.width = "0";
                }
            }
        }

        function openDrawer(drawerId) {
            var drawers = document.getElementsByClassName('drawer');
            var isAnyDrawerOpen = false;
            var openDrawerId = null;

            // Check if any drawer is currently open
            for (var i = 0; i < drawers.length; i++) {
                var drawer = drawers[i];
                if (drawer.style.width === "300px") {
                    isAnyDrawerOpen = true;
                    openDrawerId = drawer.id;
                    break;
                }
            }

            if (isAnyDrawerOpen) {
                // If a drawer is open, close it and then open the new drawer with delay
                closeDrawer(openDrawerId);
                setTimeout(function() {
                    var drawerToOpen = document.getElementById(drawerId);
                    drawerToOpen.style.width = "300px";
                }, 500);
            } else {
                // If no drawer is open, open the new drawer immediately
                var drawerToOpen = document.getElementById(drawerId);
                drawerToOpen.style.width = "300px";
            }
        }

        function closeDrawer(drawerId) {
            var drawer = document.getElementById(drawerId);
            drawer.style.width = "0";
        }

        document.addEventListener('DOMContentLoaded', function () {
            <?php if (session()->getFlashdata('loginDrawer')): ?>
                openDrawer('loginDrawer');
            <?php endif; ?>
            <?php if (session()->getFlashdata('registerDrawer')): ?>
                openDrawer('registerDrawer');
            <?php endif; ?>
        });
    </script>

    <script>
        document.getElementById('qrButton').addEventListener('click', function() {
            const readerElement = document.getElementById('reader');
            readerElement.style.display = 'block'; // Show the reader element

            Quagga.init({
                inputStream : {
                    name : "Live",
                    type : "LiveStream",
                    target: readerElement
                },
                decoder : {
                    readers : ["code_128_reader", "ean_reader", "ean_8_reader", "code_39_reader", "code_39_vin_reader", "codabar_reader", "upc_reader", "upc_e_reader", "i2of5_reader"]
                }
            }, function(err) {
                if (err) {
                    console.error("Error initializing Quagga: ", err);
                    return;
                }
                Quagga.start();
            });

            Quagga.onDetected(function(result) {
                alert(`QR Code scanned: ${result.codeResult.code}`);
                Quagga.stop(); // Stop the scanner
                readerElement.style.display = 'none'; // Hide the reader element
            });
        });
    </script>
</body>
</html>