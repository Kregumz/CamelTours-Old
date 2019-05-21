    <!-- Navigation Bar -->
    <div class="contain-to-grid">
      <nav class="top-bar" data-topbar role="navigation">
        <ul class="title-area">
          <li class="name">
            <h1><a href="<?php echo base_url();?>"><span class="glyphicon glyphicon-qrcode"></span> CamelTours</a></h1>
          </li>
          <li class="toggle-topbar menu-icon">
            <a href="#"><span></span></a>
          </li>
        </ul>
        <section class="top-bar-section">
          <!-- Left Nav Section -->
          <ul class="left">
            <li>
              <a href="<?php echo base_url();?>whatis">What is a CamelTour?</a>
            </li>
            <li>
              <a href="<?php echo base_url();?>about">About Us</a>
            </li>
            <li>
              <a href="<?php echo base_url();?>faq">FAQ</a>
            </li>
            <li>
              <a href="<?php echo base_url();?>contact">Contact</a>
            </li>
            <li>
              <a href="<?php echo base_url();?>catalog">Catalog</a>
            </li>
          </ul>
          <!-- Right Nav Section -->
          <ul class="right">
            <?php if (!$this->session->userdata('validated')): ?><!-- Inactive session links -->
              <li class="has-form">
                <a href="<?php echo base_url();?>login" class="button">Log In</a>
              </li>
              <li>
                <a href="<?php echo base_url();?>signup">or <b>Sign Up</b></a>
              </li>
            <?php else: ?><!-- Active session links -->
              <li>
                <a href="<?php echo base_url();?>cms/home"><b>My Home</b></a>
              </li>
              <li class="has-dropdown not-click">
                <a href="#"><b>My Account</b></a>
                <ul class="dropdown">
                  <li><a href="<?php echo base_url();?>cms/account-settings">Settings</a></li>
                  <li><a href="<?php echo base_url();?>cms/logout">Logout</a></li>
                </ul>
              </li>
            <?php endif; ?><!-- End session links -->
          </ul>
        </section>
      </nav>
    </div>
    <div class="top-bar-spacer row"></div>