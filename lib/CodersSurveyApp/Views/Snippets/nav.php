<nav>
	<ul>
		<?php if ( $survey->isClosed() ) { ?>
		<li class="active">
			<a href="#results" data-toggle="tab">The Results</a>
		</li>
		<?php } else { ?>
		<li class="active">
			<a href="#questions" data-toggle="tab">The Questions</a>
		</li>
		<?php } ?>
		<li>
			<a href="#about" data-toggle="tab">About</a>
		</li>
	</ul>
</nav>