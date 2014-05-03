<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">

    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
            <span class="sr-only"><?php echo __('Toggle navigation') ?></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="index.html" style="width:250px;"><?php echo __('Kloder') ?></a>
    </div>
    <!-- /.navbar-header -->

    <ul class="nav navbar-top-links navbar-right">

        <?php echo $this->fetch('submenu') ?>

        <li class="dropdown user-dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <?php $thumb = AuthComponent::user('thumb'); if (!empty($thumb)) : ?>
                    <img src="<?php echo $this->Html->url('/files/user/thumb/'.AuthComponent::user('thumb_dir').'/'.AuthComponent::user('thumb')) ?>" alt="<?php echo __('Avatar') ?>" class="img-rounded" style="height:16px;" />
                <?php else: ?>
                    <i class="fa fa-user"></i>
                <?php endif; ?>
                <?php echo AuthComponent::user('username') ?> <b class="caret"></b>
            </a>
            <ul class="dropdown-menu">
                <li><a href="#"><?php echo $this->Html->link('<i class="fa fa-user fa-fw"></i> '.__('Profile'), array('plugin' => 'users', 'controller' => 'profile', 'action' => 'index'), array('escape' => false)) ?>
                <li><a href="#"><?php echo $this->Html->link('<i class="fa fa-envelope fa-fw"></i> '.__('Inbox'), array('plugin' => 'mails', 'controller' => 'mails', 'action' => 'index'), array('escape' => false)) ?>
                <li class="divider"></li>
                <li><a href="<?php echo $this->Html->url('/logout') ?>"><i class="fa fa-power-off fa-fw"></i> <?php echo __('Log Out') ?></a></li>
            </ul>
        </li>

        <?php if (AuthComponent::user('users_group_id') == 'admin') : ?>
        <li class="dropdown user-dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-gears"></i> <b class="caret"></b>
            </a>
            <ul class="dropdown-menu">
                <li><?php echo $this->Html->link('<i class="fa fa-user fa-fw"></i> '.__('Users'), array('plugin' => 'users', 'controller' => 'users', 'action' => 'index'), array('escape' => false)) ?></li>
                <li><?php echo $this->Html->link('<i class="fa fa-users fa-fw"></i> '.__('Groups'), array('plugin' => 'users', 'controller' => 'users_groups', 'action' => 'index'), array('escape' => false)) ?></li>
                <!--<li><?php echo $this->Html->link('<i class="fa fa-gear fa-fw"></i> '.__('Repositories'), array('plugin' => '', 'controller' => 'repositories', 'action' => 'index'), array('escape' => false)) ?></li>-->
                <li><?php echo $this->Html->link('<i class="fa fa-flag fa-fw"></i> '.__('Languages'), array('plugin' => 'languages', 'controller' => 'languages', 'action' => 'index'), array('escape' => false)) ?></li>
                <!--<li><?php echo $this->Html->link('<i class="fa fa-gear fa-fw"></i> '.__('Plugins'), array('plugin' => '', 'controller' => 'plugins', 'action' => 'index'), array('escape' => false)) ?></li>-->
                <li><?php echo $this->Html->link('<i class="fa fa-exclamation fa-fw"></i> '.__('Notices'), array('plugin' => 'notices', 'controller' => 'notices', 'action' => 'index'), array('escape' => false)) ?></li>
            </ul>
        </li>
        <?php endif; ?>
    </ul>
    <!-- /.navbar-top-links -->
</nav>
<!-- /.navbar-static-top -->
