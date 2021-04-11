<nav class="navbar navbar-expand-sm navbar-dark bg-dark pr-0">
  <a class="navbar-brand" href="project.php">
	  <b><center>CROssBAR</center></b>
	  Knowledge Graphs
  </a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#top_menu" aria-controls="top_menu" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

	<?php
		$currpg = explode('/',$_SERVER['PHP_SELF']);
		$currpg = $currpg[count($currpg)-1];
	?>

  <div class="collapse navbar-collapse" id="top_menu">
	<ul class="navbar-nav mr-auto">
      <li class="nav-item pl-3 pr-3 border-right">
        <a class="nav-link<?php if($currpg==='index.php') echo ' active'; ?> navmenuitem" href="index.php">Search</span></a>
      </li>
      <li class="nav-item pl-3 pr-3 border-right">
        <a class="nav-link<?php if($currpg==='about.php') echo ' active'; ?> navmenuitem" href="about.php">About</a>
      </li>
      <li class="nav-item pl-3 pr-3 border-right">
        <a class="nav-link<?php if($currpg==='tutorial.php') echo ' active'; ?> navmenuitem" href="tutorial.php">Tutorial</a>
      </li>
      <li class="nav-item pl-3 pr-3 border-right">
        <a class="nav-link<?php if($currpg==='covid_main.php') echo ' active'; ?> navmenuitem" href="covid_main.php">COVID-19 KGs</a>
      </li>
      <li class="nav-item pl-3">
        <a class="nav-link<?php if($currpg==='project.php') echo ' active'; ?> navmenuitem" href="project.php">Project/Team</a>
      </li>
    </ul>

	<form class="col-2 my-0 my-md-0 pr-2" action="job.php" method="GET">
		<input class="form-control" name="id" type="text" placeholder="Enter Job ID"/>
    </form>

	<img src="images/CROssBAR_all_univ_logos.svg" class="col-sm-4" height="60px" alt="logos"/>
  </div>
</nav>
