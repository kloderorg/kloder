<!-- Side Menu -->
    <a id="menu-toggle" href="#" class="btn btn-primary btn-lg toggle"><i class="fa fa-bars"></i></a>
    <div id="sidebar-wrapper">
        <ul class="sidebar-nav">
            <a id="menu-close" href="#" class="btn btn-default btn-lg pull-right toggle"><i class="fa fa-times"></i></a>
            <li class="sidebar-brand"><a href="#top"><?php echo __('Kloder') ?></a>
            </li>
            <li><a href="#top"><?php echo __('Home') ?></a>
            </li>
            <li><a href="#about"><?php echo __('About') ?></a>
            </li>
            <li><a href="#services"><?php echo __('Reasons') ?></a>
            </li>
            <li><a href="#portfolio"><?php echo __('Prices') ?></a>
            </li>
            <li><a href="#contact"><?php echo __('Contact') ?></a>
            </li>
        </ul>
    </div>
    <!-- /Side Menu -->

    <!-- Full Page Image Header Area -->
    <div id="top" class="header">
        <div class="vert-text">
            <h1><?php echo __('Kloder') ?></h1>
            <h3><?php echo __('<em>We</em> Make Your Life Easier, <em>You</em> Make Us Better') ?></h3>
            <?php echo $this->Html->link(__('Login'), array('plugin' => 'users', 'controller' => 'users', 'action' => 'login'), array('class' => 'btn btn-default btn-lg')) ?>
            <?php echo __('or') ?>
            <?php echo $this->Html->link(__('Try Demo'), array('plugin' => 'users', 'controller' => 'users', 'action' => 'register'), array('class' => 'btn btn-default btn-lg')) ?>
        </div>
    </div>
    <!-- /Full Page Image Header Area -->

    <!-- Intro -->
    <div id="about" class="intro">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-md-offset-3 text-center">
                    <h2><?php echo __('Kloder is your perfect site for take care of your clients and your own bussiness!') ?></h2>
                    <p class="lead"><?php echo __('We are proud to introduce the tool to change the way you plan ahead and make the arrangements a pleasure.') ?></p>
                </div>
            </div>
        </div>
    </div>
    <!-- /Intro -->

    <!-- Services -->
    <div id="services" class="services">
        <div class="container">
            <div class="row">
                <div class="col-md-4 col-md-offset-4 text-center">
                    <h2><?php echo __('Reasons') ?></h2>
                    <hr>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2 col-md-offset-2 text-center">
                    <div class="service-item">
                        <i class="service-icon fa fa-rocket"></i>
                        <h4><?php echo __('Quick Response') ?></h4>
                        <p><?php echo __('We are actively working for you, so listen to any proposals or suggestions for improvement') ?></p>
                    </div>
                </div>
                <div class="col-md-2 text-center">
                    <div class="service-item">
                        <i class="service-icon fa fa-magnet"></i>
                        <h4><?php echo __('Attractive Design') ?></h4>
                        <p><?php echo __('Our clear and simple design makes the use of this platform something delicious') ?></p>
                    </div>
                </div>
                <div class="col-md-2 text-center">
                    <div class="service-item">
                        <i class="service-icon fa fa-shield"></i>
                        <h4><?php echo __('Data Protection') ?></h4>
                        <p><?php echo __('We protect your data against any loss thanks to our cloud storage') ?></p>
                    </div>
                </div>
                <div class="col-md-2 text-center">
                    <div class="service-item">
                        <i class="service-icon fa fa-pencil"></i>
                        <h4><?php echo __('Easy to Use') ?></h4>
                        <p><?php echo __('Our main goal is to make this platform as easy to use as possible') ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Services -->

    <!-- Callout -->
    <div class="callout">
        <div class="vert-text">
            <h1><?php echo __('Stop wasting time and enjoy the best') ?></h1>
        </div>
    </div>
    <!-- /Callout -->

    <!-- Portfolio -->
    <div id="portfolio" class="portfolio">
        <div class="container">
            <div class="row">
                <div class="col-md-6 text-center">
                    <div class="panel panel-info">
                        <div class="panel-heading"><h2><?php echo __('Monthly') ?></h2></div>
                        <table class="table">
                            <tr><td><?php echo __('Web store your data almost three month') ?></td></tr>
                            <tr><td><?php echo __('Wana try? this is your offer') ?></td></tr>
                            <tr><td><?php echo __('Work intermitency? this is for you') ?></td></tr>
                        </table>
                        <div class="panel-footer"><h1>2,99 €*</h1>* <?php echo __('Taxes not included') ?></div>
                    </div>
                </div>
                <div class="col-md-6 text-center">
                    <div class="panel panel-info">
                        <div class="panel-heading"><h2><?php echo __('Yearly') ?></h2></div>
                        <table class="table">
                            <tr><td><?php echo __('Relax for the rest of the year') ?></td></tr>
                        </table>
                        <div class="panel-footer"><h1>29,99 €*</h1>* <?php echo __('Taxes not included') ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Portfolio -->

    <!-- Call to Action -->
    <div id="contact" class="call-to-action">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-md-offset-3 text-center">
                    <h3><?php echo __('For any information please contact') ?></h3>
                    <h1><a href="mailto:hello@kloder.org">hello@kloder.org</a></h1>
                </div>
            </div>
        </div>
    </div>
    <!-- /Call to Action -->

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-md-offset-3 text-center">
                    <hr />
                    <p>Copyright &copy; Kloder <?php echo date('Y') ?></p>
                </div>
            </div>
        </div>
    </footer>
    <!-- /Footer -->

    <!-- Custom JavaScript for the Side Menu and Smooth Scrolling -->
    <script>
    $("#menu-close").click(function(e) {
        e.preventDefault();
        $("#sidebar-wrapper").toggleClass("active");
    });
    </script>
    <script>
    $("#menu-toggle").click(function(e) {
        e.preventDefault();
        $("#sidebar-wrapper").toggleClass("active");
    });
    </script>
    <script>
    $(function() {
        $('a[href*=#]:not([href=#])').click(function() {
            if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') || location.hostname == this.hostname) {

                var target = $(this.hash);
                target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
                if (target.length) {
                    $('html,body').animate({
                        scrollTop: target.offset().top
                    }, 1000);
                    return false;
                }
            }
        });
    });
    </script>
