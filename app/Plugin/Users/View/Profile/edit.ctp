<div class="row">
	<div class="col-lg-12">
		<h1>Edit <small>Profile</small></h1>
		<ol class="breadcrumb">
			<li><?php echo $this->Html->link('<i class="fa fa-user"></i> '.__d('users', 'Profile'), array('action' => 'index'), array('escape' => false)) ?></li>
			<li><?php echo $this->Html->link(__d('users', 'Edit'), array('action' => 'edit'), array('escape' => false)) ?></li>
		</ol>
	</div>
</div>

<h2><?php echo $this->request->data['User']['username'] ?></h2>

<?php echo $this->Form->create('User') ?>

    <?php echo $this->Form->input('id', array('type' => 'hidden')) ?>
    <?php echo $this->Form->thumb('thumb', array('read_dir' => 'user/thumb/'.$this->request->data['User']['thumb_dir'])) ?>
    <?php echo $this->Form->input('email', array('placeholder' => __d('users', 'Email'))) ?>
    <div class="row">
        <div class="col-lg-6">
            <?php echo $this->Form->input('name', array('placeholder' => __d('users', 'Name'))) ?>
        </div>
        <div class="col-lg-6">
	       <?php echo $this->Form->input('last_name', array('placeholder' => __d('users', 'Last Name'))) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-3">
            <?php echo $this->Form->input('language_id') ?>
        </div>
        <div class="col-lg-3">
            <?php echo $this->Form->input('date_format', array('type' => 'radio', 'options' => array(
                'd F, Y' => date('d F, Y'),
                'Y/m/d' => date('Y/m/d'),
                'm/d/Y' => date('m/d/Y'),
                'd/m/Y' => date('d/m/Y')
            ), 'value' => 'd/m/Y')) ?>
        </div>
        <div class="col-lg-3">
            <?php echo $this->Form->input('time_format', array('type' => 'radio', 'options' => array(
                'g:i a' => date('g:i a'),
                'g:i A' => date('g:i A'),
                'H:i' => date('H:i')
            ), 'value' => 'H:i')) ?>
        </div>
        <div class="col-lg-3">
            <?php echo $this->Form->input('first_day', array('options' => array(
                '0' => __d('users', 'Sunday'),
                '1' => __d('users', 'Monday'),
                '2' => __d('users', 'Tuesday'),
                '3' => __d('users', 'Wednesday'),
                '4' => __d('users', 'Thursday'),
                '5' => __d('users', 'Friday'),
                '6' => __d('users', 'Saturday')
            ), 'selected' => '1')) ?>
        </div>
    </div>

    <?php echo $this->Form->submit(__d('users', 'Save'), array('class' => 'btn btn-large btn-primary')) ?>

<?php echo $this->Form->end() ?>
