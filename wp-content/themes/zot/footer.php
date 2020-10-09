<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content
 * after.  Calls sidebar-footer.php for bottom widgets.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */

$options = get_option('cOptn');
?>
<?php if(is_shop()){?>
</div>
</div>
<?php }?>


<footer>
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-7"></div>
			<div class="col-md-5">
				<div class="widget">
					<p><?php echo $options['copyright'];?> | <a href="#">Privacy Policy</a> | <a href="#">About</a></p>
					<ul class="social">
						<li><a href="<?php echo $options['facebook'];?>" target="_blank"><i class="fa fa-facebook"></i></a></li>
						<li><a href="<?php echo $options['twitter'];?>" target="_blank"><i class="fa fa-twitter"></i></a></li>
						<li><a href="<?php echo $options['instagram'];?>" target="_blank"><i class="fa fa-instagram"></i></a></li>
						<li><a href="<?php echo $options['snapchat'];?>" target="_blank"><i class="fa fa-snapchat-ghost"></i></a></li>
						<li><a href="<?php echo $options['pinterest'];?>" target="_blank"><i class="fa fa-pinterest"></i></a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</footer>


<!-- Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Please sign in to ZOT</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form_wrapper">
		
			<form id="loginForm" class="f-border" action="">
				<div class="col-md-12">
					<div class="mb-3">
					<label for="email address">1. What is your email address?</label>
					<input type="email" name="email" class="form-control" placeholder="Email address">
					</div>
					<div class="mb-3 pw-eye">
					<label for="password">2. What is your ZOT password?</label>
					<div id="show_hide_password">
					<input type="password" name="password" class="form-control" placeholder="Password (6 to 12 characters)">
					<i id="eye-pw" class="fa fa-eye-slash" aria-hidden="true"></i>
					</div>
					</div>
					<div class="row">
					<div class="col">
						<button id="fire"  onclick="getData()" type="submit" class="btn-zot mb-2">SIGN IN</button>
					</div>
					<div class="col">
						<button type="button" class="btn-zot-b btn-link">Forget?</button>
					</div>
					</div>
				</div>
				 <?php wp_nonce_field( 'ajax-login-nonce', 'security' ); ?>
			</form>


			<form id="registerForm" class="f-border" action="">
				<div class="col-md-12">
					<div class="mb-3">
					<label for="email address">1. What is your email address?</label>
					<input type="email" name="email" class="form-control" placeholder="Email address">
					</div>
					<div class="row">
					<div class="col mb-3 pw-eye">
						<label for="password">2. Set up your password?</label>
						<div id="show_hide_password">
						<input type="password" name="password" class="form-control" placeholder="Password (6 to 12 characters)">
						<i id="eye-pw" class="fa fa-eye-slash" aria-hidden="true"></i>
						</div>
					</div>

					<div class="col mb-3 pw-eye">
						<label for="password">3. Re-enter your password?</label>
						<div id="show_hide_password">
						<input type="password" name="re-password" class="form-control" placeholder="Password (6 to 12 characters)">
						<i id="eye-pw" class="fa fa-eye-slash" aria-hidden="true"></i>
						</div>
					</div>
					</div>

					<div class="row">
					<div class="col">
						<button type="submit" class="btn-zot mb-2">REGISTER</button>
					</div>
					
					</div>
				</div>
			</form>
		
		</div>
      </div>
      <div class="modal-footer">
		<div class="col-md-12">
			<div class="mb-3">
			<h3 id="footer-title" class="zot-bold-1">New to ZOT?</h3>
			</div>
		<div id="createAccountBtn" class="mb-3">
			<button id="createAccount" type="button" class="btn-zot-c btn-link">CREATE ACCOUNT</button>
		</div>

		<div id="signInBtn" class="mb-3">
			<button id="loginBtn" type="button" class="btn-zot-c btn-link">SIGN IN</button>
		</div>

		</div>
	  </div>
	  

	  <div id="disclaimer-1" class="modal-footer">
		  <p>By clicking “Join Now” you acknowledge that you are a U.S. or Canada resident and agree to Zone Of Trends’s <a href="#">Privacy Policy</a>, <a href="#">Terms of Use</a>, ZOT’s Terms, and to automatically receive ZOT’s offers and notifications via email.</p>
	  	  <p>ZOT uses Google ReCaptcha and by registering, users are subject to Google’s <a href="#">privacy policy</a> & <a href="#">terms</a>.</p>
		</div>


    </div>
  </div>
</div>




<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>

<script src="<?php bloginfo('template_directory'); ?>/js/jquery.js"></script>
<script src="<?php bloginfo('template_directory'); ?>/js/custom.js"></script>
<script>
	new WOW().init();
</script>

<?php include (TEMPLATEPATH . '/page-template/story-template.php'); ?>
</body></html>

<?php wp_footer(); ?>
