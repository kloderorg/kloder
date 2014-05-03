<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
		<ul class="nav" id="side-menu">

            <?php foreach (Configure::read('Sidebar.plugins') as $plugin) echo $this->element($plugin.'.subsidebar'); ?>

		</ul>
    </div>
</nav>
